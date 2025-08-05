<?php

namespace App\Livewire\Admin\Goods;

use App\Models\Goods;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    public function delete($id): void
    {
        $good = Goods::findOrFail($id);

        foreach ($good->images as $image) {
            if (\Illuminate\Support\Facades\Storage::exists(str_replace('storage/', 'public/', $image->path))) {
                \Illuminate\Support\Facades\Storage::delete(str_replace('storage/', 'public/', $image->path));
            }
            $image->delete();
        }
        $good->parameterValues()->delete();
        $good->delete();
        session()->flash('success', 'Товар успешно удалён!');
    }

    public function render()
    {
        $goods = Goods::query()
            ->when($this->search, function ($query) {
                $query->where('name_ru', 'like', '%' . $this->search . '%')
                    ->orWhere('name_en', 'like', '%' . $this->search . '%')
                    ->orWhere('name_az', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.goods.index', compact('goods'))
            ->layout('admin.layouts.admin');
    }
}
