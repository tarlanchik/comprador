<?php
namespace App\Livewire\Admin\News;

use App\Models\News;
//use Illuminate\Support\Facades\Log;
//use App\Rules\TrixContentRequired;
//use Illuminate\Container\Attributes\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;

class EditNews extends Component
{
    use WithFileUploads;

    public News $news;
    public string $title_az;
    public string $content_az;
    public string $title_ru;
    public string $content_ru;
    public string $title_en;
    public string $content_en;
    public ?string $youtube_link = null;
    public ?string $keywords_az = null;
    public ?string $keywords_ru = null;
    public ?string $keywords_en = null;
    public ?string $description_az = null;
    public ?string $description_ru = null;
    public ?string $description_en = null;
    public $existingPhotos = [];
    public array $photoOrder = [];
    public array $photos = [];
    protected array $rules = [];
    public array $locales = [];
    public function mount(News $news): void
    {
        $this->locales = config('app.locales');

        $this->news = $news;
        $this->existingPhotos = $news->images()->orderBy('sort_order')->get();
        $this->photoOrder = $this->existingPhotos->pluck('id')->toArray();

        foreach ($this->locales as $lang => $name) {
            $this->{"title_{$lang}"} = $news->{"title_{$lang}"};
            $this->{"content_{$lang}"} = $news->{"content_{$lang}"};
            $this->{"keywords_{$lang}"} = $news->{"keywords_{$lang}"};
            $this->{"description_{$lang}"} = $news->{"description_{$lang}"};
        }
        $this->youtube_link = $news->youtube_link;
    }

    #[On('updateExistingPhotoOrder')]
    public function updateExistingPhotoOrder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            $this->news->images()->where('id', $id)->update(['sort_order' => $index + 1]);
        }
        $this->photoOrder = $orderedIds;
        $this->news->refresh();
        $this->existingPhotos = $this->news->images()->orderBy('sort_order')->get();
        $this->dispatch('$refresh');
    }
    #[On('updatePhotoOrder')]
    public function updatePhotoOrder(array $orderedKeys): void
    {
        $this->photoOrder = $orderedKeys;
        $this->photos = collect($orderedKeys)->map(fn($key) => $this->photos[$key])->toArray();
    }

    public function deleteExistingPhoto(int $photoId): void
    {
        $photo = $this->news->images()->findOrFail($photoId);
        $relativePath = str_replace('storage/', '', $photo->image_path);

        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }

        $photo->delete();

        $this->news->refresh();
        $this->existingPhotos = $this->news->images()->orderBy('sort_order')->get();
        $this->photoOrder = $this->existingPhotos->pluck('id')->toArray();
    }
    public function update()
    {
        foreach ($this->locales as $lang => $name) {
            $rules["content_{$lang}"] = [new \App\Rules\TrixContentRequired()];
            $rules["title_{$lang}"] = 'required';
            $rules["keywords_{$lang}"] = 'required';
            $rules["description_{$lang}"] = 'required';
        }

        $rules['youtube_link'] = 'nullable|url';
        $rules['photos.*'] = 'nullable|image|max:2048';

        $this->validate($rules);

        foreach ($this->locales as $lang => $name) {
            \Log::info($this->news->{"title_{$lang}"});
            $this->news->{"title_{$lang}"} = $this->{"title_{$lang}"};
            $this->news->{"content_{$lang}"} = $this->{"content_{$lang}"};
            $this->news->{"keywords_{$lang}"} = $this->{"keywords_{$lang}"};
            $this->news->{"description_{$lang}"} = $this->{"description_{$lang}"};
        }

        $this->news->youtube_link = $this->youtube_link;
        $this->news->save();

        $disk = Storage::disk('public');
        $dir = 'news/' . $this->news->id;

        if (!$disk->exists($dir)) {
            if (!$disk->makeDirectory($dir)) {
                throw new \Exception('Ошибка: не удалось создать директорию для хранения фото.');
            }
            chmod($disk->path($dir), 0755);
        }

        $sortOrder = $this->news->images()->max('sort_order') + 1;

        foreach ($this->photos as $index => $photo) {
            $imageName = uniqid() . '.' . $photo->getClientOriginalExtension();
            $storedPath = $photo->storeAs($dir, $imageName, 'public');
            if ($storedPath) {
                $this->news->images()->create([
                    'image_path' => "storage/{$storedPath}",
                    'sort_order' => $sortOrder++
                ]);
                chmod($disk->path($storedPath), 0644);
            }
        }

        session()->flash('success', 'Новость обновлена.');
        return redirect()->route('admin.news.index');
    }

    protected function storePhoto(UploadedFile $photo): void
    {
        if ($this->news->images()->count() >= 10) return;

        $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
        $path = $photo->storeAs("public/news/{$this->news->id}", $filename);

        $this->news->images()->create([
            'image_path' => 'storage/news/' . $this->news->id . '/' . $filename,
            'sort_order' => $this->news->images()->count()
        ]);
    }

    #[Layout('admin.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.news.create-edit', [
            'existingPhotos' => $this->existingPhotos,
            'photos' => $this->photos,
        ]);
    }
}
