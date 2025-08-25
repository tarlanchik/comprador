<div class="container py-5">
    <style>
        #newPhotosList .list-group-item {
            cursor: move;
            user-select: none;
            touch-action: none;
        }
        #newPhotosList .list-group-item {
            position: relative;
            z-index: 10;
        }
        #existingPhotosList .list-group-item {
            cursor: move;
            user-select: none;
            touch-action: none;
        }
        #existingPhotosList .list-group-item {
            position: relative;
            z-index: 10;
        }
    </style>
    <h2>{{ $isEdit ? 'Редактировать товар' : 'Добавить товар' }}</h2>
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session()->has('parameter_error'))
        <div tabindex="-1" class="alert alert-warning mt-2" role="alert">
            {{ session('parameter_error') }}
        </div>
    @endif

    <form wire:submit.prevent="save" enctype="multipart/form-data" class="needs-validation" novalidate id="goods-edit-form">
        <div class="card">
            <div class="card-header"><h5 class="modal-title">{{ $isEdit ? 'Редактирование товара' : 'Добавление товара' }}</h5></div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-3" id="langTabs" role="tablist">
                    @foreach($locales as $lang=>$label)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($loop->first) active @endif" id="{{ $lang }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $lang }}" type="button" role="tab">
                                {{ $label }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content mb-4">
                    @foreach($locales as $lang=>$label)
                        <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $lang }}" role="tabpanel">
                            <div class="mb-3">
                                <label for="name_{{ $lang }}" class="form-label">Название ({{ $label }})</label>
                                <input id="name_{{ $lang }}" required type="text" wire:model="name_{{ $lang }}" class="form-control">
                                @error("name_$lang") <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label for=title_{{ $lang }}" class="form-label">SEO Заголовок ({{ $label }})</label>
                                <input id="title_{{ $lang }}" maxlength="60" required type="text" wire:model="title_{{ $lang }}" class="form-control">
                                <small class="text-muted">
                                    {{ strlen($titles[$lang] ?? '') }}/60 Символы
                                </small>
                                @error("title_$lang") <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="keywords_{{ $lang }}" class="form-label">SEO Ключевые слова ({{ $label }})</label>
                                <input id="keywords_{{ $lang }}" maxlength="160" type="text" required wire:model="keywords_{{ $lang }}" class="form-control">
                                <small class="text-muted">
                                    Укажите через запятую. {{ strlen($keywords[$lang] ?? '') }}/160 Символы
                                </small>
                                @error("keywords_$lang") <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description_{{ $lang }}" class="form-label">SEO Описание ({{ $label }})</label>
                                <textarea id="description_{{ $lang }}" maxlength="255" required wire:model="description_{{ $lang }}" class="form-control" rows="3"></textarea>
                                <small class="text-muted">
                                    {{ strlen($description[$lang] ?? '') }}/255 Символы
                                </small>
                                @error("description_$lang") <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="price" class="form-label">Цена</label>
                        <input id="price" required type="number" wire:model="price" step="0.01" class="form-control">
                        @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Старая цена</label>
                        <input type="number" wire:model="old_price" step="0.01" class="form-control">
                        @error('old_price') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="count" class="form-label">Количество</label>
                        <input id="count" required type="number" wire:model="count" class="form-control">
                        @error('count') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ссылка на YouTube</label>
                    <input placeholder="https://www.youtube.com/watch?v=VtnvFdyvGUQ" type="url" wire:model="youtube_link" class="form-control">
                    @error('youtube_link') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Категория</label>
                    <select id="category_id" required wire:model="category_id" class="form-select">
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">
                                {{ $cat->name_ru }}
                            </option>
                            @foreach($cat->children as $child)
                                <option value="{{ $child->id }}">
                                    {{ '👉 ' . $child->name_ru }}
                                </option>
                                @foreach($child->children as $grandchild)
                                    <option value="{{ $grandchild->id }}">
                                        {{ '👉👉 ' . $grandchild->name_ru }}
                                    </option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                    @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <div class="mb-3 d-flex align-items-center gap-2">
                        <div style="flex-grow: 1;">
                            <div class="mb-3">
                                <label for="productTypeId" class="form-label">Выберите шаблон товара</label>
                                <div class="input-group">
                                    <select id="productTypeId" required wire:model="productTypeId" class="form-select">
                                        <option value="">-- Выберите шаблон --</option>
                                        @foreach($productTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" wire:click="loadParameters" class="btn btn-primary">
                                        <i class="bi bi-box-arrow-in-down"></i> Загрузить параметры
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('productTypeId') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                @if($parameters && count($parameters))
                    @php
                        $params = \App\Models\Parameter::whereIn('id', array_keys($parameters))->get()->keyBy('id');
                    @endphp
                    <fieldset class="border rounded-3 p-4 shadow-sm bg-light mb-4">
                        <legend class="float-none w-auto px-3 fw-bold text-primary fs-5">
                        <i class="fas fa-cog me-1"></i> Параметры товара</legend>
                        @foreach($parameters as $paramId => $value)
                        <div class="mb-3">
                            <label for="param_{{ $paramId }}" class="form-label">{{ \App\Models\Parameter::find($paramId)?->name_ru }}</label>
                            <input required type="text" wire:model="parameters.{{ $paramId }}" id="param_{{ $paramId }}" class="form-control">
                        </div>
                         @endforeach
                    </fieldset>
                @endif

                <div class="mb-3">
                    <label for="photos" class="form-label">Фотографии (до 10 шт.)</label>
                    <input id="photos" required type="file" wire:model="photos" multiple class="form-control">
                    <small class="form-text text-muted">Вы можете загрузить до 10 изображений.</small>
                </div>

                @error('photos')
                <div class="text-danger mt-2 text-sm">{{ $message }}</div>
                @enderror

                {{-- Существующие фото --}}
                @if(isset($existingPhotos) && count($existingPhotos) > 0)
                    <h5 class="mt-4">Загруженные фотографии</h5>
                    <div x-data="sortableComponent">
                        <ul class="list-group mt-3" id="existingPhotosList">
                            @foreach($existingPhotos as $photo)
                                <li class="handle list-group-item d-flex align-items-center gap-3" data-key="{{ $photo->id }}">
                                    <span style="font-size: 20px;">⇅</span>
                                    <img alt="" src="{{ asset($photo->image_path) }}" width="100" class="img-thumbnail">
                                    <span class="flex-grow-1">Фото #{{ $loop->iteration }}</span>
                                    <button type="button" wire:click="deleteExistingPhoto({{ $photo->id }})" class="btn btn-sm btn-outline-danger">Удалить</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Новые фото --}}
                @if(count($photos) > 0)
                    <h5 class="mt-4">Новые фотографии</h5>
                    <div x-data="sortableComponent">
                        <ul class="list-group mt-3" id="newPhotosList">
                            @foreach ($photos as $key => $photo)
                                <li class="handle list-group-item d-flex align-items-center gap-3" data-key="{{ $key }}">
                                    <span style="font-size: 20px;">⇅</span>
                                    <img alt="" src="{{ $photo->temporaryUrl() }}" width="100" class="img-thumbnail">
                                    <span class="flex-grow-1">Фото {{ $loop->iteration }}</span>
                                    <button wire:click="removePhoto({{ $key }})" class="btn btn-sm btn-outline-danger">Удалить</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="text-end">
                    <button type="submit" class="btn btn-{{ $isEdit ? 'warning' : 'primary'}}"><i class="bi bi-folder-plus"></i> {{ $isEdit ? 'Обновить' : 'Добавить' }}</button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Bootstrap validation script
            const form = document.getElementById('goods-edit-form');
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
                form.classList.add('was-validated');
            }, true);
            // Bootstrap validation script
        });

        document.addEventListener("alpine:init", () => {
            Alpine.data("sortableComponent", () => ({
                init() {
                    const el = this.$el.querySelector('#newPhotosList');
                    if (el && !el._sortable) {
                        new Sortable(el, {
                            animation: 150,
                            handle: '.handle',
                            onEnd: () => {
                                const order = Array.from(el.querySelectorAll('li')).map(li => li.dataset.key);
                                const livewireComponent = Livewire.find(this.$el.closest('[wire\\:id]').getAttribute('wire:id'));
                                livewireComponent.call('updatePhotoOrder', order);
                            }
                        });
                        el._sortable = true;
                    }

                    const existingPhotosList = this.$el.querySelector('#existingPhotosList');
                    if (existingPhotosList && !existingPhotosList._sortable) {
                        new Sortable(existingPhotosList, {
                            animation: 150,
                            handle: '.handle',
                            onEnd: () => {
                                const order = Array.from(existingPhotosList.querySelectorAll('li')).map(li => li.dataset.key);
                                this.$wire.call('updateExistingPhotoOrder', order);
                            }
                        });
                        existingPhotosList._sortable = true;
                    }
                }
            }));
        });
    </script>
@endpush
