<?php

namespace App\Livewire\Admin\ProductTypes;

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
    public string $parameterNameRu = '';
    public string $parameterNameEn = '';
    public string $parameterNameAz = '';
    public array $parameterList = [];
    public string $editingParameterName = '';
    public ?string $editId = '';
    public ?string $editName = '';
    public ?string $editParentId = '';
    public ?string $editTypeId = null;
    public ?int $parent_id = null;
    public string $editTypeName = '';
    public ?string $editingParameterId = null;
    public string $editingParameterNameRu = '';
    public string $editingParameterNameEn = '';
    public string $editingParameterNameAz = '';
    public function mount(): void
    {
        $this->loadTypes();
    }
    public function editCategory($id): void
    {
        $category = Category::findOrFail($id);
        $this->editId = $category->id;
        $this->editName = $category->name;
        $this->editParentId = $category->parent_id;

        $this->dispatch('open-edit-category-modal');
    }

    public function updateCategory(): void
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

    public function startEditingParameter($id): void
    {
        $param = Parameter::find($id);
        if ($param) {
            $this->editingParameterId = $param->id;
            $this->editingParameterNameRu = $param->name_ru ?? '';
            $this->editingParameterNameEn = $param->name_en ?? '';
            $this->editingParameterNameAz = $param->name_az ?? '';

            $this->dispatch('open-edit-parameter-modal');
        }
    }

    public function updateParameter(): void
    {
        $this->validate([
            'editingParameterNameRu' => 'required|string|max:255',
            'editingParameterNameEn' => 'required|string|max:255',
            'editingParameterNameAz' => 'required|string|max:255',
        ]);

        $param = Parameter::find($this->editingParameterId);
        if ($param) {
            $param->update([
                'name_ru' => $this->editingParameterNameRu,
                'name_en' => $this->editingParameterNameEn,
                'name_az' => $this->editingParameterNameAz,
            ]);
        }

        $this->editingParameterId = null;
        $this->editingParameterNameRu = '';
        $this->editingParameterNameEn = '';
        $this->editingParameterNameAz = '';

        $this->dispatch('close-edit-parameter-modal');
        $this->manageParameters($this->selectedTypeId);
    }

    public function cancelEditing(): void
    {
        $this->editingParameterId = null;
        $this->editingParameterName = '';
    }

    public function loadTypes(): void
    {
        $this->types = ProductType::with('parameters')->get();
    }

    public function addType(): void
    {
        $this->validate(['name' => 'required|string']);
        ProductType::create(['name' => $this->name]);
        $this->name = '';
        $this->loadTypes();
    }

    public function deleteType($id): void
    {
        ProductType::find($id)?->delete();
        $this->loadTypes();
        if ($this->selectedTypeId == $id) {
            $this->selectedTypeId = null;
            $this->parameterList = [];
        }
    }

    public function editType($id): void
    {
        $type = ProductType::findOrFail($id);
        $this->editTypeId = $type->id;
        $this->editTypeName = $type->name;

        $this->dispatch('open-edit-type-modal');
    }

    public function updateType(): void
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

    public function manageParameters($typeId): void
    {
        $this->selectedTypeId = $typeId;
        $type = ProductType::find($typeId);
        $this->selectedTypeName = $type?->name;

        $this->parameterList = Parameter::where('product_type_id', $typeId)->get()->toArray();
        $this->showModal = true;

        $this->dispatch('open-parameters-modal');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->dispatch('close-parameters-modal');
    }

    public function addParameter(): void
    {
        //logger('Добавление параметра: '.$this->parameterName.' для типа: '.$this->selectedTypeId);
        /*$this->validate(['parameterName' => 'required']);
        if (! $this->selectedTypeId) {
            $this->dispatch('error', message: 'Тип товара не выбран!');
            return;
        }
        */
        $this->validate([
            'parameterNameRu' => 'required|string|max:255',
            'parameterNameEn' => 'required|string|max:255',
            'parameterNameAz' => 'required|string|max:255',
        ]);
        if (! $this->selectedTypeId) {
            $this->dispatch('error', message: 'Тип товара не выбран!');
            return;
        }

        Parameter::create([
            'product_type_id' => $this->selectedTypeId,
            'name_ru' => $this->parameterNameRu,
            'name_en' => $this->parameterNameEn,
            'name_az' => $this->parameterNameAz,
        ]);

        $this->reset(['parameterNameRu', 'parameterNameEn', 'parameterNameAz']);
        $this->manageParameters($this->selectedTypeId);
    }

    public function deleteParameter($paramId): void
    {
        Parameter::find($paramId)?->delete();
        $this->manageParameters($this->selectedTypeId);
    }

    public function render()
    {
        return view('livewire.admin.product-types.index')->layout('admin.layouts.admin');
    }
}
