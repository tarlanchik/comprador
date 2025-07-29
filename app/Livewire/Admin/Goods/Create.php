<?php

namespace App\Livewire\Admin\Goods;

use App\Models\Category;
use App\Models\Goods;
use App\Models\Parameter;
use App\Models\ProductType;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;

class Create extends Component
{
    use WithFileUploads;

    public bool $isEdit = false;

    public string $name_ru;

    public string $name_en;

    public string $name_az;

    public string $title_ru;

    public string $title_en;

    public string $title_az;

    public string $keywords_ru;

    public string $keywords_en;

    public string $keywords_az;

    public string $description_ru;

    public string $description_en;

    public string $description_az;

    public ?float $price = null;

    public ?float $old_price = null;

    public ?int $count = null;

    public ?int $category_id = null;

    public ?int $productTypeId = null;

    public ?string $youtube_link = null;

    public array $photos = [];

    public array $photoOrder = [];

    public array $parameters = [];
    public array $orderedKeys = [];

    protected array $rules = [
        'photos' => 'nullable|array|max:10', // Чтобы при ошибке валидации не терялись фото
        'photos.*' => 'image|max:10240', // каждый файл ≤ 10MB
    ];

    public array $categories = [];

    public function mount()
    {
        $this->isEdit = false;
        $this->categories = Category::getOrderedCategories();
    }

    public function updatedCategoryId(): void
    {
        $this->parameters = [];
        $category = Category::with('productType.parameters')->find($this->category_id);
        if ($category && $category->productType) {
            foreach ($category->productType->parameters as $param) {
                $this->parameters[$param->id] = ''; // пустое значение по умолчанию
            }
        }
    }

    public function updatedPhotos()
    {
        Log::alert('updatedPhotos called');
        if (count($this->photos) > 10) {
            $this->photos = array_slice($this->photos, 0, 10);
        }
        $this->photoOrder = array_keys($this->photos);

        // Отправляем событие на frontend
        $this->dispatch('photosUpdated');
    }

    public function loadParameters(): void
    {
        if (! $this->productTypeId) {
            session()->flash('parameter_error', 'Пожалуйста, выберите шаблон товара.');
            return;
        }

        if ($this->productTypeId) {
            $this->parameters = Parameter::where('product_type_id', $this->productTypeId)
                ->pluck('name', 'id')
                ->mapWithKeys(fn ($name, $id) => [$id => ''])
                ->toArray();

            logger('Параметры загружены вручную для шаблона ID: '.$this->productTypeId);
        } else {
            $this->parameters = [];
        }
    }

    #[On('updatePhotoOrder')]
    public function updatePhotoOrder(array $orderedKeys): void
    {
        $reordered = [];

        foreach ($orderedKeys as $key) {
            $intKey = (int)$key; // 🔧 важное изменение
            if (isset($this->photos[$intKey])) {
                $reordered[] = $this->photos[$intKey];
            }
        }

        $this->photos = $reordered;
    }

    public function removePhoto($index): void
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
        $this->photoOrder = array_keys($this->photos);
    }

    public function save(): void
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
        if (empty($this->photos)) {
            $this->addError('photos', 'Добавьте хотя бы одно фото.');
            return;
        }
        // ✅ Обновляем product_type_id у категории, если он не задан
        $category = Category::find($this->category_id);
        if ($category && !$category->product_type_id) {
            $category->update(['product_type_id' => $this->productTypeId]);
        }
        // ✅ Создаём товар
        $good = Goods::create([
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
        // ✅ Сохраняем параметры
        foreach ($this->parameters as $paramId => $value) {
            $good->parameterValues()->create([
                'parameter_id' => $paramId,
                'value' => $value,
            ]);
        }
        foreach ($this->parameters as $paramId => $value) {
            $good->parameterValues()->create([
                'parameter_id' => $paramId,
                'value' => $value,
            ]);
        }

        $disk = Storage::disk('public');
        $dir = 'goods/' . $good->id;

// Создаём папку (не обязательно, но если хочешь контролировать chmod)
        if (!$disk->exists($dir)) {
            if (!$disk->makeDirectory($dir)) {
                throw new \Exception('Ошибка: не удалось создать директорию для хранения фото.');
            }
            chmod($disk->path($dir), 0755);
        }

// ✅ Сохраняем фот
        foreach ($this->photos as $index => $photo) {
            $imageName = uniqid() . '.' . $photo->getClientOriginalExtension();

            // Сохраняем на диск public (Laravel сам создаёт поддиректории)
            $storedPath = $photo->storeAs($dir, $imageName, 'public');

            if ($storedPath) {
                $good->images()->create([
                    'image_path' => "storage/{$storedPath}",
                    'sort_order' => $index
                ]);
                chmod($disk->path($storedPath), 0644);
            //} else {
            //    logger()->error('Ошибка при сохранении фото', [
            //        'image' => $photo->getClientOriginalName(),
            //    ]);
            }
        }


        session()->flash('success', 'Товар добавлен!');
        //return redirect()->route('admin.goods.index'); //A void function must not return a value
        redirect()->route('admin.goods.index')->send();
        /*
                //$this->reset();
                // После reset() желательно оставить photos пустым вручную, иначе могут быть проблемы с повторной загрузкой:
                //$this->photos = [];
                //$this->photoOrder = [];
                */
    }

    public function render()
    {
        return view('livewire.admin.goods.edit-create', [
            'categories' => Category::getOrderedCategories(),
            'productTypes' => ProductType::all(),
            'existingPhotos' => collect(),
        ])->layout('admin.layouts.admin');
    }
}
