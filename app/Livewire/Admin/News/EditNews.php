<?php
namespace App\Livewire\Admin\News;

use App\Models\News;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class EditNews extends Component
{
    use WithFileUploads;

    public News $news;
    public $photos = [];

    public function mount(News $news)
    {
        $this->news = $news;
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

        $this->news->save();

        foreach ($this->photos as $photo) {
            $this->storePhoto($photo);
        }

        session()->flash('success', 'Новость обновлена.');
        return redirect()->route('admin.news.index');
    }

    protected function storePhoto(UploadedFile $photo)
    {
        if ($this->news->images()->count() >= 10) return;

        $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
        $path = $photo->storeAs("public/news/{$this->news->id}", $filename);

        $this->news->images()->create([
            'image_path' => 'storage/news/' . $this->news->id . '/' . $filename,
            'sort_order' => $this->news->images()->count()
        ]);
    }

    public function render()
    {
        return view('livewire.admin.news.create-edit');
    }
}
