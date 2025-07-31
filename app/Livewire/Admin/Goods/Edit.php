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
    public function mount(Goods $good): void
    {
        $this->existingPhotos = $this->good->images()->orderBy('sort_order')->get();
        $this->good = $good->load('images');
        $this->good = $good->load('category.productType');

        if ($this->productTypeId) {
            $allParams = Parameter::where('product_type_id', $this->productTypeId)->pluck('name', 'id')->toArray();
            $existingValues = $good->parameterValues()->pluck('value', 'parameter_id')->toArray();

            foreach ($allParams as $paramId => $name) {
                $this->parameters[$paramId] = $existingValues[$paramId] ?? '';
            }
        }

        $this->isEdit = true;
        $this->name_ru = $good->name_ru; // Property accessed via magic method
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
        // $this->productTypeId = $this->good->category?->productType?->id;

        // ✅ ВАЖНО: подтягиваем тип товара
        $this->productTypeId = $good->category?->productType?->id;

        $this->photoOrder = $good->images->pluck('id')->toArray();

        // ✅ Загружаем параметры для типа товара
        $allParams = Parameter::where('product_type_id', $this->productTypeId)->pluck('name', 'id')->toArray();
        $existingValues = $good->parameterValues()->pluck('value', 'parameter_id')->toArray();

        // ✅ Объединяем: все параметры + существующие значения
        $this->parameters = [];
        foreach ($allParams as $paramId => $name) {
            $this->parameters[$paramId] = $existingValues[$paramId] ?? '';
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
                ->where('goods_id', $this->good->id) // Безопасность: только у текущего товара
                ->update(['sort_order' => $index + 1]);
        }

        $this->photoOrder = $orderedIds;
        $this->good->refresh(); // Обновить модель товара и его связи
        $this->dispatch('$refresh'); // Обновить компонент Livewire
    }

    public function deleteExistingPhoto($photoId): void
    {
        Log::alert('deleteExistingPhoto');

        $photo = $this->good->images()->findOrFail($photoId);

        // Удаляем файл с диска (disk('public'))
        $relativePath = str_replace('storage/', '', $photo->image_path); // ex: "goods/10/photo.jpg"
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }

        // Удаляем запись из базы
        $photo->delete();

        // Обновляем модель good
        $this->good->refresh();

        // Обновляем коллекцию существующих фото
        $this->existingPhotos = $this->good->images()->orderBy('sort_order')->get();

        // Обновляем порядок
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

            // Берем значения из товара
            $existingValues = $this->good->parameterValues()->pluck('value', 'parameter_id')->toArray();
            //Log::info('params', $params->toArray());
            //Log::info('existingValues', $existingValues);
            // Объединяем: все параметры → если есть значение, подставляем, если нет — пусто
            $this->parameters = [];
            foreach ($params as $paramId) {
                $this->parameters[$paramId] = $existingValues[$paramId] ?? '';
            }
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
            'old_price' => 'nullable|numeric',
            'count' => 'required|numeric',
            'productTypeId' => 'required|exists:product_types,id',
            'youtube_link' => 'nullable|string|max:160',
            'category_id' => 'required|exists:categories,id',
            'photos.*' => 'image|max:2048',
        ]);

        $category = Category::find($this->category_id);
        if ($category && !$category->product_type_id) {
            $category->update(['product_type_id' => $this->productTypeId]);
        }
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
            $this->good->parameterValues()->updateOrCreate(
                [
                    'goods_id' => $this->good->id, // добавляем goods_id
                    'parameter_id' => $paramId,
                ],
                [
                    'value' => $value,
                ]
            );
        }

        $disk = Storage::disk('public');
        $dir = 'goods/' . $this->good->id;

// Создаём папку, если не существует
        if (!$disk->exists($dir)) {
            if (!$disk->makeDirectory($dir)) {
                throw new \Exception('Ошибка: не удалось создать директорию для хранения фото.');
            }
            chmod($disk->path($dir), 0755);
        }

// Определим начальный порядок сортировки
        $sortOrder = $this->good->images()->max('sort_order') + 1;

// Сохраняем новые фото
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

    public function render()
    {
        $existingPhotos = $this->good->images()->orderBy('sort_order')->get();
        return view('livewire.admin.goods.edit-create', [
            'categories' => $this->categories,
            'productTypes' => ProductType::all(),
            'existingPhotos' => $existingPhotos,
        ])->layout('admin.layouts.admin');
    }
}
