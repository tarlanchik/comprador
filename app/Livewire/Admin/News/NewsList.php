<?php
namespace App\Livewire\Admin\News;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;
//use Illuminate\Support\Facades\Log;

class NewsList extends Component
{
    use WithPagination;

    public string $search = '';
    protected array $updatesQueryString = ['search'];

    public function searchNews(): void
    {
       // Log::debug('searchNews вызван, search = ' . $this->search);
        $this->resetPage(); // если используешь пагинацию
    }

    public function delete($id): void
    {
        $news = News::findOrFail($id);
        // удалим связанные фото и файлы
        foreach ($news->images as $image) {
            \Illuminate\Support\Facades\Storage::delete('public/' . str_replace('storage/', '', $image->image_path));
            $image->delete();
        }
        $news->delete();
        session()->flash('success', 'Новость удалена.');
    }

    public function render()
    {
        //Log::debug('Поисковый запрос:', ['search' => $this->search]);
        $newsItems = News::when($this->search, function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->where('title_az', 'like', "%{$this->search}%")
                    ->orWhere('title_ru', 'like', "%{$this->search}%")
                    ->orWhere('title_en', 'like', "%{$this->search}%");
            });
        })->orderByDesc('created_at')->paginate(10);
        return view('livewire.admin.news.index', [
            'newsItems' => $newsItems,
        ]);
    }
}
