<?php

namespace App\Livewire\Admin\Goods;

use App\Models\Category;
use App\Models\Goods;
use App\Models\Parameter;
use App\Models\ProductType;
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
    public array $locales = [];

    protected array $rules = [
        'photos' => 'required|array|max:10', // Чтобы при ошибке валидации не терялись фото
        'photos.*' => 'image|max:10240', // каждый файл ≤ 10MB
    ];

    public array $categories = [];

    public function mount(): void
    {
        $this->locales = config('app.locales');
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

    public function updatedPhotos(): void
    {
        if (count($this->photos) > 10) {
            $this->photos = array_slice($this->photos, 0, 10);
        }
        $this->photoOrder = array_keys($this->photos);
        $this->dispatch('photosUpdated');
    }

    public function loadParameters(): void
    {
        if (! $this->productTypeId) {
            session()->flash('parameter_error', 'Пожалуйста, выберите шаблон товара.');
            return;
        }
        $this->parameters = Parameter::where('product_type_id', $this->productTypeId)->pluck('name_ru', 'id')->mapWithKeys(fn ($name, $id) => [$id => ''])->toArray();
    }

    #[On('updatePhotoOrder')]
    public function updatePhotoOrder(array $orderedKeys): void
    {
        $reordered = [];
        foreach ($orderedKeys as $key) {
            $intKey = (int)$key;
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

        $updateData = [];

        // Динамическая валидация и сбор данных для создания/обновления
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
        $this->validate($rules);

        // Добавляем остальные поля
        $updateData['price'] = $this->price;
        $updateData['old_price'] = $this->old_price;
        $updateData['count'] = $this->count;
        $updateData['youtube_link'] = $this->youtube_link;
        $updateData['category_id'] = $this->category_id;

        // ✅ Обновляем product_type_id у категории, если он не задан
        $category = Category::find($this->category_id);
        if ($category && !$category->product_type_id) {
            $category->update(['product_type_id' => $this->productTypeId]);
        }
        // ✅ Создаём товар
        $good = Goods::create($updateData);

        // ✅ Сохраняем параметры
        foreach ($this->parameters as $paramId => $value) {
            $good->parameterValues()->create([
                'parameter_id' => $paramId,
                'value' => $value,
            ]);
        }

        $disk = Storage::disk('public');
        $dir = 'goods/' . $good->id;

        if (!$disk->exists($dir)) {
            if (!$disk->makeDirectory($dir)) {
                throw new \Exception('Ошибка: не удалось создать директорию для хранения фото.');
            }
            chmod($disk->path($dir), 0755);
        }

        foreach ($this->photos as $index => $photo) {
            $imageName = uniqid() . '.' . $photo->getClientOriginalExtension();
            $storedPath = $photo->storeAs($dir, $imageName, 'public');
            if ($storedPath) {
                $good->images()->create([
                    'image_path' => "storage/{$storedPath}",
                    'sort_order' => $index
                ]);
                chmod($disk->path($storedPath), 0644);
            }
        }
        session()->flash('success', 'Товар добавлен!');
        return redirect()->route('admin.goods.index');
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
