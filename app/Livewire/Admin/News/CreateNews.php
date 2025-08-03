<?php
namespace App\Livewire\Admin\News;

use App\Models\News;
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

    protected array $rules = [
        'photos' => 'required|array|max:10',
        'photos.*' => 'image|max:10240',
    ];
    public function mount($news = null): void
    {
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
        $this->validate([
            'title_az' => 'required', 'content_az' => 'required',
            'title_ru' => 'required', 'content_ru' => 'required',
            'title_en' => 'required', 'content_en' => 'required',
            'youtube_link' => 'nullable|url',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $news = News::create([
            'title_az' => $this->title_az,
            'content_az' => $this->content_az,
            'title_ru' => $this->title_ru,
            'content_ru' => $this->content_ru,
            'title_en' => $this->title_en,
            'content_en' => $this->content_en,
            'keywords_az' => $this->keywords_az,
            'keywords_ru' => $this->keywords_ru,
            'keywords_en' => $this->keywords_en,
            'description_az' => $this->description_az,
            'description_ru' => $this->description_ru,
            'description_en' => $this->description_en,
            'youtube_link' => $this->youtube_link,
        ]);

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
        return redirect()->route('admin.news.list');
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

    public function render()
    {
        return view('livewire.admin.news.create-edit');
    }
}
