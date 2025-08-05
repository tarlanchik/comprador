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

    <form wire:submit.prevent="save" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header">
                <h5 class="modal-title">Добавление товара</h5>
            </div>
            <div class="card-body">

                <ul class="nav nav-tabs mb-3" id="langTabs" role="tablist">
                    @foreach(['ru' => 'Русский', 'en' => 'English', 'az' => 'Azərbaycanca'] as $lang => $label)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($loop->first) active @endif" id="{{ $lang }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $lang }}" type="button" role="tab">
                                {{ $label }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content mb-4">
                    @foreach(['ru' => 'Русский', 'en' => 'English', 'az' => 'Azərbaycanca'] as $lang => $label)
                        <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $lang }}" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">Название ({{ $label }})</label>
                                <input type="text" wire:model.defer="name_{{ $lang }}" class="form-control">
                                @error("name_$lang") <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Заголовок ({{ $label }})</label>
                                <input type="text" wire:model.defer="title_{{ $lang }}" class="form-control">
                                @error("title_$lang") <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ключевые слова ({{ $label }})</label>
                                <input type="text" wire:model.defer="keywords_{{ $lang }}" class="form-control">
                                @error("keywords_$lang") <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Описание ({{ $label }})</label>
                                <textarea wire:model.defer="description_{{ $lang }}" class="form-control" rows="3"></textarea>
                                @error("description_$lang") <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Цена</label>
                        <input type="number" wire:model.defer="price" step="0.01" class="form-control">
                        @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Старая цена</label>
                        <input type="number" wire:model.defer="old_price" step="0.01" class="form-control">
                        @error('old_price') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Количество</label>
                        <input type="number" wire:model.defer="count" class="form-control">
                        @error('count') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ссылка на YouTube</label>
                    <input type="url" wire:model.defer="youtube_link" class="form-control">
                    @error('youtube_link') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Категория</label>
                    <select wire:model.defer="category_id" class="form-select">
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">
                                {{ str_repeat('➤ ', $cat->level) . $cat->name_ru }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <div class="mb-3 d-flex align-items-center gap-2">
                        <div style="flex-grow: 1;">
                            <label class="form-label">Выберите шаблон товара</label>
                            <select wire:model="productTypeId" class="form-select">
                                <option value="">-- Выберите шаблон --</option>
                                @foreach($productTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" wire:click="loadParameters" class="btn btn-primary" style="height: fit-content; margin-top: 32px;">
                            Загрузить параметры
                        </button>
                    </div>
                    @error('productTypeId') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                @if($parameters && count($parameters))
                    @php
                        $params = \App\Models\Parameter::whereIn('id', array_keys($parameters))->get()->keyBy('id');
                    @endphp
                    <div class="p-3 mb-4" style="border: 1px solid #ccc; border-radius: 6px; background-color: #f9f9f9;">
                        <h5 class="mb-3 text-secondary">
                            <i class="fas fa-cog me-1"></i> Параметры товара
                        </h5>

                        @foreach($parameters as $paramId => $value)
                            <div class="mb-3">
                                <label for="param_{{ $paramId }}" class="form-label">{{ \App\Models\Parameter::find($paramId)?->name_ru }}</label>
                                <input type="text" wire:model.defer="parameters.{{ $paramId }}" id="param_{{ $paramId }}" class="form-control">
                            </div>
                        @endforeach
                    </div>
                @endif

                        <div class="mb-3">
                            <label class="form-label">Фотографии (до 10 шт.)</label>
                            <input type="file" wire:model="photos" multiple class="form-control">
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
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">{{ $isEdit ? 'Сохранить изменения' : 'Добавить' }}</button>
                </div>

    </form>

</div>




@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
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
