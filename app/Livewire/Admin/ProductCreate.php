<?php

namespace App\Livewire\Admin;

use App\Models\Parameter;
use App\Models\Product;
use App\Models\ProductParameterValue;
use App\Models\ProductType;
use Livewire\Component;

class ProductCreate extends Component
{
    public $name;

    public $price;

    public $stock;

    public $description;

    public $category_id;

    public $product_type_id;

    public $parameters = [];

    public function mount()
    {
        dd('ProductCreate is called');
        logger('Product Компонент вызван');
    }

    public function updatedProductTypeId($value)
    {
        logger('ProductCreate Компонент вызван');
        // Подгрузим параметры при выборе типа
        $this->parameters = Parameter::where('product_type_id', $value)->get()->map(function ($param) {
            return ['id' => $param->id, 'name' => $param->name, 'value' => ''];
        })->toArray();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'product_type_id' => 'required|exists:product_types,id',
            'parameters.*.value' => 'required',
        ]);

        $product = Product::create([
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'product_type_id' => $this->product_type_id,
        ]);

        foreach ($this->parameters as $param) {
            ProductParameterValue::create([
                'product_id' => $product->id,
                'parameter_id' => $param['id'],
                'value' => $param['value'],
            ]);
        }

        session()->flash('success', 'Товар успешно создан!');

        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        return view('livewire.admin.product-create', [
            'types' => ProductType::all(),
        ]);
    }
}
