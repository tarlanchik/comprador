<?php
namespace App\Livewire\Admin\News;

use App\Models\News;
use App\Rules\TrixContentRequired;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;


class CreateNews extends Component
{
    use WithFileUploads;

    public string $title_az = '';
    public string $content_az = '';
    public string $title_ru = '';
    public string $content_ru = '';
    public string $title_en = '';
    public string $content_en = '';
    public ?string $keywords_az = null;
    public ?string $keywords_ru = null;
    public ?string $keywords_en = null;
    public ?string $description_az = null;
    public ?string $description_ru = null;
    public ?string $description_en = null;
    public ?string $youtube_link = null;
    public array $photoOrder = [];
    public array $photos = [];
    public News $news;
    protected array $rules = [];
    public array $locales = [];
    public function mount($news = null): void
    {
        $this->locales = config('app.locales');
        $this->news = $news ?? new News();
    }

    public function updatedPhotos(): void
    {
        if (count($this->photos) > 10) {
            $this->photos = array_slice($this->photos, 0, 10);
        }
        $this->photoOrder = array_keys($this->photos);
        $this->dispatch('photosUpdated');
    }

    #[On('updatePhotoOrder')]
    public function updatePhotoOrder(array $orderedKeys): void
    {
        $reordered = [];
        foreach ($orderedKeys as $key) {
            $intKey = (int)$key;
            if (isset($this->photos[$intKey])) {
                $reordered[] = $this->photos[$intKey];
            }
        }
        $this->photos = $reordered;
    }
    public function removePhoto($index): void
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
        $this->photoOrder = array_keys($this->photos);
    }

    public function save()
    {
        foreach ($this->locales as $lang => $name) {
            $rules["content_{$lang}"] = [new \App\Rules\TrixContentRequired()];
            $rules["title_{$lang}"] = 'required|string|max:60';
            $rules["keywords_{$lang}"] = 'required|string|max:255';
            $rules["description_{$lang}"] = 'required|string|max:160';
        }
        $rules['youtube_link'] = 'nullable|url';
        $rules['photos.*'] = 'nullable|image|max:2048';

        $this->validate($rules);
        foreach ($this->locales as $lang => $name) {
            $newsData["title_{$lang}"] = $this->{"title_{$lang}"};
            $newsData["content_{$lang}"] = $this->{"content_{$lang}"};
            $newsData["keywords_{$lang}"] = $this->{"keywords_{$lang}"};
            $newsData["description_{$lang}"] = $this->{"description_{$lang}"};
        }
        $newsData['youtube_link'] = $this->youtube_link;
        $news = News::create($newsData);

        $disk = Storage::disk('public');
        $dir = 'news/' . $news->id;

        if (!$disk->exists($dir)) {
            $disk->makeDirectory($dir);
            chmod($disk->path($dir), 0755);
        }

        foreach ($this->photos as $index => $photo) {
            $imageName = uniqid() . '.' . $photo->getClientOriginalExtension();
            $storedPath = $photo->storeAs($dir, $imageName, 'public');

            if ($storedPath) {
                $news->images()->create([
                    'image_path' => "storage/{$storedPath}",
                    'sort_order' => $index
                ]);
                chmod($disk->path($storedPath), 0644);
            }
        }
        session()->flash('success', 'Новость добавлена.');
        return redirect()->route('admin.news.index');
    }

    protected function storePhoto(News $news, UploadedFile $photo): void
    {
        if ($news->images()->count() >= 10) return;

        $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
        $path = $photo->storeAs("public/news/{$news->id}", $filename);

        $news->images()->create([
            'image_path' => 'storage/news/' . $news->id . '/' . $filename,
            'sort_order' => $news->images()->count()
        ]);
    }
    #[Layout('admin.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.news.create-edit', [
            'existingPhotos' => '',
        ]);
    }
}
