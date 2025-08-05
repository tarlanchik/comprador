<?php
namespace App\Livewire\Admin\News;

use App\Models\News;
//use Illuminate\Support\Facades\Log;
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
    protected $listeners = ['updateExistingPhotoOrder', 'refreshComponent' => '$refresh'];
    public function mount(News $news): void
    {
        $this->news = $news;
        $this->existingPhotos = $news->images()->orderBy('sort_order')->get();
        $this->photoOrder = $this->existingPhotos->pluck('id')->toArray();

        $this->title_az = $news->title_az;
        $this->content_az = $news->content_az;
        $this->title_ru = $news->title_ru;
        $this->content_ru = $news->content_ru;
        $this->title_en = $news->title_en;
        $this->content_en = $news->content_en;
        $this->youtube_link = $news->youtube_link;

        $this->keywords_az = $news->keywords_az;
        $this->keywords_ru = $news->keywords_ru;
        $this->keywords_en = $news->keywords_en;

        $this->description_az = $news->description_az;
        $this->description_ru = $news->description_ru;
        $this->description_en = $news->description_en;
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
        $this->validate([
            'news.title_az' => 'required', 'news.content_az' => 'required',
            'news.title_ru' => 'required', 'news.content_ru' => 'required',
            'news.title_en' => 'required', 'news.content_en' => 'required',
            'news.youtube_link' => 'nullable|url',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $this->news->title_az = $this->title_az;
        $this->news->content_az = $this->content_az;
        $this->news->title_ru = $this->title_ru;
        $this->news->content_ru = $this->content_ru;
        $this->news->title_en = $this->title_en;
        $this->news->content_en = $this->content_en;
        $this->news->youtube_link = $this->youtube_link;

        $this->news->keywords_az = $this->keywords_az;
        $this->news->keywords_ru = $this->keywords_ru;
        $this->news->keywords_en = $this->keywords_en;
        $this->news->description_az = $this->description_az;
        $this->news->description_ru = $this->description_ru;
        $this->news->description_en = $this->description_en;

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
