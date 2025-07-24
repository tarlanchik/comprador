<?php

namespace App\Livewire\Admin\Goods;

use App\Models\Category;
use App\Models\Good;
use App\Models\Parameter;
use App\Models\ProductType;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;
    public bool $isEdit = false;
    public Good $good;

    public ?string $name_ru = '';
    public ?string $name_en;
    public ?string $name_az;
    public ?string $title_ru;
    public ?string $title_en;
    public ?string $title_az;
    public ?string $keywords_ru;
    public ?string $keywords_en;
    public ?string $keywords_az;
    public ?string $description_ru;
    public ?string $description_en;
    public ?string $description_az;
    public float|null $price = null;
    public float|null $old_price = null;
    public int|null $count = null;
    public int|null $category_id = null;
    public ?int $productTypeId = null;
    public string|null $youtube_link = null;

    public ?string $productType;
    public array $photos = [];
    public array $photoOrder = [];

    public array $parameters = [];
    public array $categories = [];

     public function mount(Good $good)
    {
        $this->good = $good->load('category.productType');
        $this->good = $good->load('images', 'category.productType.parameters');

        $this->isEdit = true;

        //$this->good = $good;

        $this->name_ru = $good->name_ru;
        $this->name_en = $good->name_en;
        $this->name_az = $good->name_az;
        $this->title_ru = $good->title_ru;
        $this->title_en = $good->title_en;
        $this->title_az = $good->title_az;
        $this->keywords_ru = $good->keywords_ru;
        $this->keywords_en = $good->keywords_en;
        $this->keywords_az = $good->keywords_az;
        $this->description_ru = $good->description_ru;
        $this->description_en = $good->description_en;
        $this->description_az = $good->description_az;
        $this->price = $good->price;
        $this->old_price = $good->old_price;
        $this->count = $good->count;
        $this->youtube_link = $good->youtube_link;
        $this->category_id = $good->category_id;
        #$this->productTypeId = $this->good->category?->productType?->id;
        $this->productTypeId = $good->category && $good->category->productType
            ? $good->category->productType->id
            : null;

        $this->parameters = $good->parameterValues()->pluck('value', 'parameter_id')->toArray();
        //$this->photoOrder = $good->images->pluck('id')->toArray();
        $this->photoOrder = $this->good->images->pluck('id')->toArray();
        $this->categories = Category::getOrderedCategories();
    }

    public function updatedPhotos()
    {
        if (count($this->photos) > 10) {
            $this->photos = array_slice($this->photos, 0, 10);
        }
        $this->photoOrder = array_keys($this->photos);
    }

    public function updatePhotoOrder($orderedKeys): void
    {
        $this->photoOrder = $orderedKeys;
        $this->photos = collect($orderedKeys)->map(fn ($key) => $this->photos[$key])->toArray();
    }

    public function removePhoto($index): void
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
        $this->photoOrder = array_keys($this->photos);
    }

    public function loadParameters()
    {
        if ($this->productTypeId) {
            $this->parameters = Parameter::where('product_type_id', $this->productTypeId)
                ->pluck('name', 'id')
                ->mapWithKeys(fn ($_, $id) => [$id => ''])
                ->toArray();
        }
    }


    public function save()
    {
        $this->validate([
            'name_ru' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_az' => 'required|string|max:255',
            'title_az' => 'required|string|max:60',
            'title_ru' => 'required|string|max:60',
            'title_en' => 'required|string|max:60',
            'description_ru' => 'required|string|max:160',
            'description_en' => 'required|string|max:160',
            'description_az' => 'required|string|max:160',
            'price' => 'required|numeric',
            'old_price' => 'required|numeric',
            'count' => 'required|numeric',
            'productTypeId' => 'required|exists:product_types,id',
            'youtube_link' => 'nullable|string|max:160',
            'category_id' => 'required|exists:categories,id',
            'photos.*' => 'image|max:2048',
        ]);

        $this->good->update([
            'name_ru' => $this->name_ru,
            'name_en' => $this->name_en,
            'name_az' => $this->name_az,
            'title_ru' => $this->title_ru,
            'title_en' => $this->title_en,
            'title_az' => $this->title_az,
            'keywords_ru' => $this->keywords_ru,
            'keywords_en' => $this->keywords_en,
            'keywords_az' => $this->keywords_az,
            'description_ru' => $this->description_ru,
            'description_en' => $this->description_en,
            'description_az' => $this->description_az,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'count' => $this->count,
            'youtube_link' => $this->youtube_link,
            'category_id' => $this->category_id,
        ]);

        foreach ($this->parameters as $paramId => $value) {
            $this->good->parameterValues()->updateOrCreate([
                'parameter_id' => $paramId,
            ], [
                'value' => $value,
            ]);
        }

        foreach ($this->photos as $photo) {
            $path = $photo->store('goods', 'public');
            $this->good->images()->create(['path' => $path]);
        }

        session()->flash('success', 'Товар обновлён!');
    }



    public function render()
    {

        $existingPhotos = $this->photoOrder && count($this->photoOrder)
            ? $this->good->images()->orderByRaw("FIELD(id, " . implode(',', $this->photoOrder) . ")")->get()
            : $this->good->images()->orderBy('id')->get();

        dd($existingPhotos);

        return view('livewire.admin.goods.edit-create', [
            'categories' => $this->categories,
            'productTypes' => ProductType::all(),
            'existingPhotos' => $existingPhotos,
        ])->layout('admin.layouts.admin');
    }
}

