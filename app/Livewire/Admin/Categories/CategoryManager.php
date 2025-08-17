<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Collection;

class CategoryManager extends Component
{
    public bool $isEditing = false;
    public array $locales = [];
    public array $form = [
        'id' => null,
        'parent_id' => null,
    ];

    protected function rules(): array
    {
        $rules = [
            'form.parent_id' => 'nullable|exists:categories,id',
        ];

        foreach ($this->locales as $lang => $label) {
            $rules["form.name_$lang"] = [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', "name_$lang")
                    ->where(fn($query) => $query->where('parent_id', $this->form['parent_id']))
                    ->ignore($this->form['id']),
            ];
        }

        return $rules;
    }

    public function mount(): void
    {
        $this->locales = config('app.locales');
        foreach (array_keys($this->locales) as $lang) {
            $this->form["name_$lang"] = '';
        }
    }

    public function addCategory(): void
    {
        $this->isEditing = false;
        $this->form['id'] = null;
        $this->validate();

        if ($this->checkDepth()) {
            return;
        }

        $data = [
            'parent_id' => $this->form['parent_id'] ?: null,
        ];
        foreach ($this->locales as $lang => $label) {
            $data["name_$lang"] = $this->form["name_$lang"];
        }

        Category::create($data);
        $this->resetForm();
        session()->flash('success', 'Категория добавлена');
    }

    public function editCategory($id): void
    {
        $this->isEditing = true;
        $category = Category::findOrFail($id);
        $this->form['id'] = $category->id;
        $this->form['parent_id'] = $category->parent_id;

        foreach ($this->locales as $lang => $label) {
            $this->form["name_$lang"] = $category->{"name_$lang"};
        }

        $this->dispatch('open-edit-category-modal');
    }

    public function updateCategory(): void
    {
        $this->isEditing = true;
        $this->validate();

        $category = Category::findOrFail($this->form['id']);

        if ($this->checkDepth() || $this->isDescendant($category, $this->form['parent_id'])) {
            return;
        }

        $data = [
            'parent_id' => $this->form['parent_id'] ?: null,
        ];
        foreach ($this->locales as $lang => $label) {
            $data["name_$lang"] = $this->form["name_$lang"];
        }
        $category->update($data);

        $this->resetForm();
        $this->dispatch('close-edit-category-modal');
        session()->flash('success', 'Категория обновлена');
    }

    public function deleteCategory($id): void
    {
        $category = Category::query()->findOrFail($id);
        if ($category->children()->exists() || $category->goods()->exists()) {
            session()->flash('error', 'Невозможно удалить категорию. У неё есть дочерние категории или привязанные товары.');
            return;
        }
        $category->delete();
        session()->flash('success', 'Категория успешно удалена');
    }

    private function checkDepth(): bool
    {
        if ($this->form['parent_id']) {
            $parent = Category::find($this->form['parent_id']);
            if ($parent && $parent->parent_id) {
                $grandParent = Category::find($parent->parent_id);
                if ($grandParent && $grandParent->parent_id) {
                    session()->flash('error', 'Нельзя создать категорию глубже 3 уровней.');
                    return true;
                }
            }
        }
        return false;
    }

    protected function isDescendant(Category $category, $possibleParentId): bool
    {
        if (!$possibleParentId || $possibleParentId === $category->id) {
            $this->addError('form.parent_id', 'Нельзя выбрать текущую категорию в качестве родителя.');
            return true;
        }

        $parent = Category::find($possibleParentId);
        if ($parent && $parent->parent_id === $category->id) {
            $this->addError('form.parent_id', 'Нельзя выбрать потомка в качестве родителя.');
            return true;
        }

        return false;
    }

    public function resetForm(): void
    {
        $this->reset(['form', 'isEditing']);
        $this->resetValidation();
        foreach (array_keys($this->locales) as $lang) {
            $this->form["name_$lang"] = '';
        }
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
