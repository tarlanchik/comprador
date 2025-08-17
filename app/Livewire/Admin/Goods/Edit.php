<?php

namespace App\Livewire\Admin\Goods;

use App\Models\Category;
use App\Models\Goods;
use App\Models\GoodsImage;
use App\Models\Parameter;
use App\Models\ProductType;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;

class Edit extends Component
{
    use WithFileUploads;

    public bool $isEdit = false;
    public Goods $good;
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
    public ?float $price = null;
    public ?float $old_price = null;
    public ?int $count = null;
    public ?int $category_id = null;
    public ?int $productTypeId = null;
    public ?string $youtube_link = null;
    public ?string $productType;
    public array $photos = [];
    public array $photoOrder = [];
    public array $parameters = [];
    public array $categories = [];
    public array $orderedKeys = [];
    public Collection $existingPhotos;
    public array $locales = [];
    public function mount(Goods $good): void
    {
        $this->locales = config('app.locales');
        $this->good = $good;
        $this->good = $good->load(['images', 'category.productType', 'parameterValues']);
        $this->existingPhotos = $this->good->images->sortBy('sort_order');
        $this->photoOrder = $this->good->images->pluck('id')->toArray();

        $this->isEdit = true;

        foreach ($this->locales as $lang => $label) {
            $this->{"name_$lang"} = $good->{"name_$lang"};
            $this->{"title_$lang"} = $good->{"title_$lang"};
            $this->{"keywords_$lang"} = $good->{"keywords_$lang"};
            $this->{"description_$lang"} = $good->{"description_$lang"};
        }

        $this->price = $good->price;
        $this->old_price = $good->old_price;
        $this->count = $good->count;
        $this->youtube_link = $good->youtube_link;
        $this->category_id = $good->category_id;

        // Тип товара
        $this->productTypeId = $good->category?->productType?->id;
        $this->parameters = [];

        if ($this->productTypeId) {
            // Загружаем параметры для выбранного типа товара
            $allParams = Parameter::where('product_type_id', $this->productTypeId)
                ->pluck('name_ru', 'id')
                ->toArray();

            // Получаем текущие значения параметров
            $existingValues = $good->parameterValues
                ->pluck('value', 'parameter_id')
                ->toArray();

            // Формируем массив параметров для формы
            foreach ($allParams as $paramId => $name) {
                $this->parameters[$paramId] = $existingValues[$paramId] ?? '';
            }
        }
      // Категории
        $this->categories = Category::getOrderedCategories();
    }

    public function updatedPhotos(): void
    {
        if (count($this->photos) > 10) {
            $this->photos = array_slice($this->photos, 0, 10);
        }
        $this->photoOrder = array_keys($this->photos);
    }
    #[On('updateExistingPhotoOrder')]
    public function updateExistingPhotoOrder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            GoodsImage::where('id', $id)
                ->where('goods_id', $this->good->id)
                ->update(['sort_order' => $index + 1]);
        }
        $this->photoOrder = $orderedIds;
        $this->good->refresh();
        $this->existingPhotos = $this->good->images()->orderBy('sort_order')->get();
        $this->dispatch('$refresh');
    }

    public function deleteExistingPhoto($photoId): void
    {
        //Log::alert('deleteExistingPhoto');
        $photo = $this->good->images()->findOrFail($photoId);
        $relativePath = str_replace('storage/', '', $photo->image_path); // ex: "goods/10/photo.jpg"
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
        $photo->delete();
        $this->good->refresh();
        $this->existingPhotos = $this->good->images()->orderBy('sort_order')->get();
        $this->photoOrder = $this->existingPhotos->pluck('id')->toArray();
    }

    #[On('updatePhotoOrder')]
    public function updatePhotoOrder($orderedKeys): void
    {
        $this->photoOrder = $orderedKeys;
        $this->photos = collect($orderedKeys)->map(fn($key) => $this->photos[$key])->toArray();
    }

    public function removePhoto($index): void
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
        $this->photoOrder = array_keys($this->photos);
    }

    public function loadParameters(): void
    {
        //Log::info('productTypeId', [$this->productTypeId]);
        if ($this->productTypeId) {
            // Получаем все параметры для данного типа товара
            $params = Parameter::where('product_type_id', $this->productTypeId)->pluck('id');
            $existingValues = $this->good->parameterValues()->pluck('value', 'parameter_id')->toArray();
            $this->parameters = [];
            foreach ($params as $paramId) {
                $this->parameters[$paramId] = $existingValues[$paramId] ?? '';
            }
        }
    }

    public function save()
    {
        $rules = [
            'price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'count' => 'required|numeric',
            'productTypeId' => 'required|exists:product_types,id',
            'youtube_link' => 'nullable|string|max:160',
            'category_id' => 'required|exists:categories,id',
            'photos.*' => 'image|max:2048',
        ];

        $validatedData = [];
        $updateData = [];

        // Динамическая валидация и сбор данных для обновления
        foreach ($this->locales as $lang => $label) {
            $rules["name_$lang"] = 'required|string|max:255';
            $rules["title_$lang"] = 'required|string|max:60';
            $rules["keywords_$lang"] = 'required|string|max:255';
            $rules["description_$lang"] = 'required|string|max:160';

            $updateData["name_$lang"] = $this->{"name_$lang"};
            $updateData["title_$lang"] = $this->{"title_$lang"};
            $updateData["keywords_$lang"] = $this->{"keywords_$lang"};
            $updateData["description_$lang"] = $this->{"description_$lang"};
        }

        // Применяем динамические и статические правила валидации
        $validatedData = $this->validate($rules);

        // Добавляем остальные поля
        $updateData['price'] = $this->price;
        $updateData['old_price'] = $this->old_price;
        $updateData['count'] = $this->count;
        $updateData['youtube_link'] = $this->youtube_link;
        $updateData['category_id'] = $this->category_id;

        $category = Category::find($this->category_id);
        if ($category && !$category->product_type_id) {
            $category->update(['product_type_id' => $this->productTypeId]);
        }

        $this->good->update($updateData);

        foreach ($this->parameters as $paramId => $value) {
            try {
                $this->good->parameterValues()->updateOrCreate(
                    [
                        'goods_id' => $this->good->id,
                        'parameter_id' => $paramId,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Ошибка сохранения параметра {$paramId}: " . $e->getMessage());
            }
        }

        $disk = Storage::disk('public');
        $dir = 'goods/' . $this->good->id;

        if (!$disk->exists($dir)) {
            if (!$disk->makeDirectory($dir)) {
                throw new \Exception('Ошибка: не удалось создать директорию для хранения фото.');
            }
            chmod($disk->path($dir), 0755);
        }

        $sortOrder = $this->good->images()->max('sort_order') + 1;

        foreach ($this->photos as $index => $photo) {
            $imageName = uniqid() . '.' . $photo->getClientOriginalExtension();
            $storedPath = $photo->storeAs($dir, $imageName, 'public');
            if ($storedPath) {
                $this->good->images()->create([
                    'image_path' => "storage/{$storedPath}",
                    'sort_order' => $sortOrder++
                ]);
                chmod($disk->path($storedPath), 0644);
            }
        }
        session()->flash('success', 'Товар обновлён!');
        return redirect()->route('admin.goods.index');
    }

    #[Layout('admin.layouts.admin')]
    public function render()
    {
        $existingPhotos = $this->good->images()->orderBy('sort_order')->get();
        return view('livewire.admin.goods.edit-create', [
            'categories' => $this->categories,
            'productTypes' => ProductType::all(),
            'existingPhotos' => $existingPhotos,
        ]);
    }
}
