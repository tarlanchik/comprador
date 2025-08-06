<?php

namespace App\Livewire\Admin\ProductTypes;

use App\Models\Category;
use App\Models\Parameter;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Collection;
//use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
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
    public Collection $parameterList;

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

        if ($this->editParentId == $this->editId) {
            session()->flash('error', 'Категория не может быть родителем самой себя');
            return;
        }

        $category->update([
            'name' => $this->editName,
            'parent_id' => $this->editParentId,
        ]);

        $this->dispatch('close-edit-category-modal');
        $this->resetInput();
        $this->loadCategories();

        session()->flash('success', 'Категория обновлена');
    }

    public function startEditingParameter($id): void
    {
        //Log::info('method startEditingParameter called');
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
            'editingParameterNameRu' => [
                'required', 'string', 'max:255',
                Rule::unique('parameters', 'name_ru')
                    ->ignore($this->editingParameterId)
                    ->where(fn ($query) => $query->where('product_type_id', $this->selectedTypeId)),
            ],
            'editingParameterNameEn' => [
                'required', 'string', 'max:255',
                Rule::unique('parameters', 'name_en')
                    ->ignore($this->editingParameterId)
                    ->where(fn ($query) => $query->where('product_type_id', $this->selectedTypeId)),
            ],
            'editingParameterNameAz' => [
                'required', 'string', 'max:255',
                Rule::unique('parameters', 'name_az')
                    ->ignore($this->editingParameterId)
                    ->where(fn ($query) => $query->where('product_type_id', $this->selectedTypeId)),
            ],
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
        $type = ProductType::with('parameters')->find($id);

        if ($type && $type->parameters->count() > 0) {
            session()->flash('error', 'Нельзя удалить тип товара, к которому привязаны параметры.');
            return;
        }

        $type?->delete();
        $this->loadTypes();

        if ($this->selectedTypeId == $id) {
            $this->selectedTypeId = null;
            $this->parameterList = new \Illuminate\Database\Eloquent\Collection();
        }
    }

    public function editType($id): void
    {
        $type = ProductType::query()->findOrFail($id);
        //$type = ProductType::findOrFail($id);
        $this->editTypeId = $type->id;
        $this->editTypeName = $type->name;

        $this->dispatch('open-edit-type-modal');
    }

    public function updateType(): void
    {
        $this->validate([
            'editTypeName' => 'required|string|max:255',
        ]);

        $type = ProductType::query()->findOrFail($this->editTypeId);
        $type->name = $this->editTypeName;
        $type->save();

        $this->editTypeId = null;
        $this->editTypeName = '';

        $this->loadTypes();
        $this->dispatch('close-edit-type-modal');
    }

    public function manageParameters($typeId): void
    {
        $this->selectedTypeId = $typeId;
        $type = ProductType::find($typeId);
        $this->selectedTypeName = $type?->name;

        $this->parameterList = Parameter::where('product_type_id', $typeId)->get();
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
        $this->validate([
            'parameterNameRu' => [
                'required', 'string', 'max:255',
                Rule::unique('parameters', 'name_ru')
                    ->where(fn ($query) => $query->where('product_type_id', $this->selectedTypeId)),
            ],
            'parameterNameEn' => [
                'required', 'string', 'max:255',
                Rule::unique('parameters', 'name_en')
                    ->where(fn ($query) => $query->where('product_type_id', $this->selectedTypeId)),
            ],
            'parameterNameAz' => [
                'required', 'string', 'max:255',
                Rule::unique('parameters', 'name_az')
                    ->where(fn ($query) => $query->where('product_type_id', $this->selectedTypeId)),
            ],
        ]);

        if (!$this->selectedTypeId) {
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
        $param = Parameter::with('parameterValues')->find($paramId);

        if ($param && $param->parameterValues()->exists()) {
            session()->flash('error', 'Нельзя удалить параметр, связанный с товарами.');
            return;
        }

        $param?->delete();
        $this->manageParameters($this->selectedTypeId);
    }


    public function render()
    {
        return view('livewire.admin.product-types.index')->layout('admin.layouts.admin');
    }
}
