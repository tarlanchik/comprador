<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;

class CategoryManager extends Component
{
    public $name_ru;
    public $name_en;
    public $name_az;

// Для редактирования
    public $editNameRu;
    public $editNameEn;
    public $editNameAz;
    public $parent_id;

    public $editId;

    public $editParentId;

    protected string $layout = 'layouts.app';

    protected $rules = [
        'name_ru' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
        'name_az' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:categories,id',
        'editNameRu' => 'required|string|max:255',
        'editNameEn' => 'required|string|max:255',
        'editNameAz' => 'required|string|max:255',
        'editParentId' => 'nullable|exists:categories,id',
    ];

    public function addCategory()
    {
        $this->validate([
            'name_ru' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_az' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create([
            'name_ru' => $this->name_ru,
            'name_en' => $this->name_en,
            'name_az' => $this->name_az,
            'parent_id' => $this->parent_id ?: null,
        ]);

        $this->name_ru = $this->name_en = $this->name_az = '';
        $this->parent_id = null;

        session()->flash('success', 'Категория добавлена');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        session()->flash('success', 'Категория удалена');
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->editId = $category->id;
        $this->editNameRu = $category->name_ru;
        $this->editNameEn = $category->name_en;
        $this->editNameAz = $category->name_az;
        $this->editParentId = $category->parent_id;

        $this->dispatch('open-edit-category-modal');
    }

    public function updateCategory()
    {
        $this->validate([
            'editNameRu' => 'required|string|max:255',
            'editNameEn' => 'required|string|max:255',
            'editNameAz' => 'required|string|max:255',
            'editParentId' => 'nullable|exists:categories,id',
        ]);

        $category = Category::findOrFail($this->editId);

        if ($this->editParentId == $this->editId || $this->isDescendant($category, $this->editParentId)) {
            $this->addError('editParentId', 'Нельзя выбрать текущую категорию или её потомка в качестве родителя.');
            return;
        }

        $category->update([
            'name_ru' => $this->editNameRu,
            'name_en' => $this->editNameEn,
            'name_az' => $this->editNameAz,
            'parent_id' => $this->editParentId ?: null,
        ]);

        $this->resetEdit();
        $this->dispatch('close-edit-category-modal');
        session()->flash('success', 'Категория обновлена');
    }

    protected function isDescendant(Category $category, $possibleParentId)
    {
        if (!$possibleParentId) return false;
        $children = $category->children()->pluck('id')->toArray();

        if (in_array($possibleParentId, $children)) return true;

        foreach ($children as $childId) {
            $child = Category::find($childId);
            if ($child && $this->isDescendant($child, $possibleParentId)) return true;
        }

        return false;
    }

    public function resetEdit()
    {
        $this->editId = null;
        $this->editName = null;
        $this->editParentId = null;
        $this->resetValidation('editName', 'editParentId');
    }

    public function getCategoriesProperty()
    {
        return Category::with('children.children')->whereNull('parent_id')->get();
    }

    public function render()
    {
        //return view('livewire.admin.category-manager')->layout('layouts.app');
        return view('livewire.admin.category-manager')->layout('admin.layouts.admin');
    }
}
