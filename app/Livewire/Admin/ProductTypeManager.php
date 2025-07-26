<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Parameter;
use App\Models\ProductType;
use Livewire\Component;

class ProductTypeManager extends Component
{
    public bool $showModal = false;

    public object $types;

    public string $name;

    public ?string $selectedTypeName = null;

    public ?string $selectedTypeId = null;

    public string $parameterName;

    public array $parameterList = [];

    public ?string $editingParameterId = null;

    public string $editingParameterName = '';

    public ?string $editTypeId = null;

    public string $editTypeName = '';

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->editId = $category->id;
        $this->editName = $category->name;
        $this->editParentId = $category->parent_id;

        $this->dispatch('open-edit-category-modal');
    }

    public function updateCategory()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editParentId' => 'nullable|exists:categories,id',
        ]);

        $category = Category::findOrFail($this->editId);

        // Проверка, чтобы категория не была своим же родителем
        if ($this->editParentId == $this->editId) {
            session()->flash('error', 'Категория не может быть родителем самой себя');

            return;
        }

        $category->update([
            'name' => $this->editName,
            'parent_id' => $this->editParentId,
        ]);

        // Закрываем модальное окно
        $this->dispatch('close-edit-category-modal');

        // Сброс полей и обновление списка
        $this->resetInput();
        $this->loadCategories();

        session()->flash('success', 'Категория обновлена');
    }

    public function startEditingParameter($id)
    {
        $param = Parameter::find($id);
        if ($param) {
            $this->editingParameterId = $param->id;
            $this->editingParameterName = $param->name;

            $this->dispatch('open-edit-parameter-modal');
        }
    }

    public function updateParameter()
    {
        $this->validate([
            'editingParameterName' => 'required|string|max:255',
        ]);

        $param = Parameter::find($this->editingParameterId);
        if ($param) {
            $param->update([
                'name' => $this->editingParameterName,
            ]);
        }

        $this->dispatch('close-edit-parameter-modal');
        $this->manageParameters($this->selectedTypeId);
    }

    public function cancelEditing()
    {
        $this->editingParameterId = null;
        $this->editingParameterName = '';
    }

    public function mount()
    {
        $this->loadTypes();
    }

    public function loadTypes()
    {
        $this->types = ProductType::with('parameters')->get();
    }

    public function addType()
    {
        $this->validate(['name' => 'required|string']);
        ProductType::create(['name' => $this->name]);
        $this->name = '';
        $this->loadTypes();
    }

    public function deleteType($id)
    {
        ProductType::find($id)?->delete();
        $this->loadTypes();
        if ($this->selectedTypeId == $id) {
            $this->selectedTypeId = null;
            $this->parameterList = [];
        }
    }

    public function editType($id)
    {
        $type = ProductType::findOrFail($id);
        $this->editTypeId = $type->id;
        $this->editTypeName = $type->name;

        $this->dispatch('open-edit-type-modal');
    }

    public function updateType()
    {
        $this->validate([
            'editTypeName' => 'required|string|max:255',
        ]);

        $type = ProductType::findOrFail($this->editTypeId);
        $type->name = $this->editTypeName;
        $type->save();

        $this->editTypeId = null;
        $this->editTypeName = '';

        $this->loadTypes(); // обновляем список

        $this->dispatch('close-edit-type-modal');
    }

    public function manageParameters($typeId)
    {
        /*
        $this->selectedTypeId = $typeId;
        $this->parameterList = Parameter::where('product_type_id', $typeId)->get()->toArray();
        $this->showModal = true;

        $this->dispatch('open-parameters-modal');
        */

        $this->selectedTypeId = $typeId;
        $type = ProductType::find($typeId);
        $this->selectedTypeName = $type?->name;

        $this->parameterList = Parameter::where('product_type_id', $typeId)->get()->toArray();
        $this->showModal = true;

        $this->dispatch('open-parameters-modal');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->dispatch('close-parameters-modal');
    }

    public function addParameter()
    {
        logger('Добавление параметра: '.$this->parameterName.' для типа: '.$this->selectedTypeId);
        $this->validate(['parameterName' => 'required']);
        if (! $this->selectedTypeId) {
            // session()->flash('error', 'Тип товара не выбран!');
            $this->dispatch('error', message: 'Тип товара не выбран!');

            return;
        }

        Parameter::create([
            'product_type_id' => $this->selectedTypeId,
            'name' => $this->parameterName,
        ]);

        $this->parameterName = '';
        $this->manageParameters($this->selectedTypeId);
    }

    public function deleteParameter($paramId)
    {
        Parameter::find($paramId)?->delete();
        $this->manageParameters($this->selectedTypeId);
    }

    public function render()
    {
        return view('livewire.admin.product-type-manager')->layout('admin.layouts.admin');
    }
}
