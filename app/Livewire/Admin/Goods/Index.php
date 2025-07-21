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
        Good::findOrFail($id)->delete();
        session()->flash('success', 'Удалено');
    }

    public function render()
    {
        $goods = Good::with('category')->latest()->paginate(10);

        return view('livewire.admin.goods.index', compact('goods'))
            ->layout('admin.layouts.admin');
    }
}
