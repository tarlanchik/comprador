<?php

namespace App\Livewire\Admin\Goods;

use App\Models\Good;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function delete($id): void
    {
        $good = Good::findOrFail($id);

        // Удаляем связанные изображения
        foreach ($good->images as $image) {
            if (\Illuminate\Support\Facades\Storage::exists(str_replace('storage/', 'public/', $image->path))) {
                \Illuminate\Support\Facades\Storage::delete(str_replace('storage/', 'public/', $image->path));
            }
            $image->delete();
        }
        // Удаляем параметры
        $good->parameterValues()->delete();

        // Удаляем сам товар
        $good->delete();

        session()->flash('success', 'Товар успешно удалён!');
    }



    public function render()
    {
        $goods = Good::with('category')->latest()->paginate(10);

        return view('livewire.admin.goods.index', compact('goods'))
            ->layout('admin.layouts.admin');
    }
}
