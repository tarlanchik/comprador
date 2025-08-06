<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Collection;

class CategoryManager extends Component
{
    public string  $name_ru;
    public string $name_en;
    public string $name_az;
    public string $editNameRu;
    public string $editNameEn;
    public string $editNameAz;
    public ?int $parent_id = null;
    public ?int $editId = null;
    public ?int $editParentId = null;

    protected array $rules = [
        'name_ru' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
        'name_az' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:categories,id',
        'editNameRu' => 'required|string|max:255',
        'editNameEn' => 'required|string|max:255',
        'editNameAz' => 'required|string|max:255',
        'editParentId' => 'nullable|exists:categories,id',
    ];



    public function addCategory(): void
    {
        $this->validate([
            'name_ru' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(fn($query) => $query->where('parent_id', $this->parent_id)),
            ],
            'name_en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(fn($query) => $query->where('parent_id', $this->parent_id)),
            ],
            'name_az' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(fn($query) => $query->where('parent_id', $this->parent_id)),
            ],
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Проверка глубины вложенности (до 3 уровней)
        if ($this->parent_id) {
            $parent = Category::find($this->parent_id);
            if ($parent && $parent->parent_id) {
                $grandParent = Category::find($parent->parent_id);
                if ($grandParent && $grandParent->parent_id) {
                    session()->flash('error', 'Нельзя создать категорию глубже 3 уровней.');
                    return;
                }
            }
        }

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

    public function deleteCategory($id): void
    {
        $category = Category::query()->findOrFail($id);
        // Проверка: есть ли дочерние категории
        if ($category->children()->exists()) {
            session()->flash('error', 'Невозможно удалить категорию, так как у неё есть дочерние категории.');
            return;
        }
        // Проверка: привязаны ли товары
        if ($category->goods()->exists()) {
            session()->flash('error', 'Невозможно удалить категорию, к ней привязаны товары.');
            return;
        }
        $category->delete();
        session()->flash('success', 'Категория успешно удалена');
    }


    public function editCategory($id): void
    {
        $category = Category::query()->findOrFail($id);
        $this->editId = $category->id;
        $this->editNameRu = $category->name_ru;
        $this->editNameEn = $category->name_en;
        $this->editNameAz = $category->name_az;
        $this->editParentId = $category->parent_id;
        $this->dispatch('open-edit-category-modal');
    }



    public function updateCategory(): void
    {
        $this->validate([
            'editNameRu' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(fn($query) => $query->where('parent_id', $this->editParentId))
                    ->ignore($this->editId),
            ],
            'editNameEn' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(fn($query) => $query->where('parent_id', $this->editParentId))
                    ->ignore($this->editId),
            ],
            'editNameAz' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(fn($query) => $query->where('parent_id', $this->editParentId))
                    ->ignore($this->editId),
            ],
            'editParentId' => 'nullable|exists:categories,id',
        ]);

        $category = Category::findOrFail($this->editId);

        // Проверка глубины вложенности (до 3 уровней)
        if ($this->editParentId) {
            $parent = Category::find($this->editParentId);
            if ($parent && $parent->parent_id) {
                $grandParent = Category::find($parent->parent_id);
                if ($grandParent && $grandParent->parent_id) {
                    session()->flash('error', 'Нельзя создать категорию глубже 3 уровней.');
                    return;
                }
            }
        }

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

    /*
    public function updateCategory(): void
    {
        $this->validate([
            'editNameRu' => 'required|string|max:255',
            'editNameEn' => 'required|string|max:255',
            'editNameAz' => 'required|string|max:255',
            'editParentId' => 'nullable|exists:categories,id',
        ]);
        $category = Category::query()->findOrFail($this->editId);
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
*/
    protected function isDescendant(Category $category, $possibleParentId): bool
    {
        if (! $possibleParentId) {
            return false;
        }
        $children = $category->children()->pluck('id')->toArray();
        if (in_array($possibleParentId, $children)) {
            return true;
        }
        foreach ($children as $childId) {
            $child = Category::find($childId);
            if ($child instanceof Category && $this->isDescendant($child, $possibleParentId)) {
                return true;
            }
        }
        return false;
    }

    public function resetEdit(): void
    {
        $this->editId = null;
        $this->editParentId = null;
        $this->resetValidation(['editNameRu', 'editNameEn', 'editNameAz', 'editParentId']);
    }

    public function getCategoriesProperty(): Collection
    {
        return Category::with('children.children')->whereNull('parent_id')->get();
    }
    #[Layout('admin.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.categories.index');
    }
}
