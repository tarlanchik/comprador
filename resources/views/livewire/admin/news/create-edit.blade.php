<div class="container py-5" x-data="{ activeTab: 'az' }">
    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
        <style>
            .list-group-item {
                cursor: move;
                user-select: none;
                touch-action: none;
                position: relative;
                z-index: 10;
            }
        </style>
    @endpush

    <h2 class="mb-4">Управление новостями</h2>

    @if(session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form wire:submit.prevent="{{ $news->exists ? 'update' : 'save' }}" enctype="multipart/form-data" class="needs-validation" novalidate id="news-edit-form">
    <div class="card">
        <div class="card-header"><h5 class="modal-title">{{ $news->exists ? 'Редактировать новость' : 'Добавить новость'}}</h5></div>
        <div class="card-body">
                <fieldset class="border rounded-3 p-4 shadow-sm bg-light mb-4">
                <legend class="float-none w-auto px-3 fw-bold text-primary fs-5">{{ $news->exists ? 'Редактировать новость' : 'Добавить новость' }}</legend>
                     {{-- Языковые табы --}}
                    <ul class="nav nav-tabs mb-3" id="langTabs" role="tablist">
                        @foreach($locales as $lang=>$name)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" :class="{ 'active': activeTab === '{{ $lang }}' }" x-on:click.prevent="activeTab = '{{ $lang }}'" id="tab-{{ $lang }}" type="button" role="tab">
                                    {{ $name }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    {{-- Контент табов --}}
                    <div class="tab-content mb-3">
                        @foreach($locales as $lang=>$name)
                        <div x-show="activeTab === '{{ $lang }}'" id="content-{{ $lang }}" role="tabpanel">
                            <div class="mb-3">
                                <label for="title_{{ $lang }}" class="form-label">SEO Title ({{ $name}})</label>
                                <input required maxlength="60" id="title_{{ $lang }}" type="text" class="form-control" wire:model="title_{{ $lang }}">
                                <small class="text-muted">
                                    {{ strlen($titles[$lang] ?? '') }}/60 Символы
                                </small>
                                @error('title_'.$lang)<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="keywords_{{ $lang }}" class="form-label">SEO Keywords ({{ $name }})</label>
                                <input required maxlength="160" id="keywords_{{ $lang }}" type="text" class="form-control" wire:model="keywords_{{ $lang }}">
                                <small class="text-muted">
                                    Укажите через запятую. {{ strlen($titles[$lang] ?? '') }}/160 Символы
                                </small>
                                @error('keywords_'.$lang)<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="description_{{ $lang }}" class="form-label">SEO Description ({{ $name }})</label>
                                <textarea required maxlength="255" id="description_{{ $lang }}" class="form-control" rows="3" wire:model="description_{{ $lang }}"></textarea>
                                <small class="text-muted">
                                    {{ strlen($titles[$lang] ?? '') }}/255 Символы
                                </small>
                                @error('description_'.$lang)<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            {{-- Content --}}
                            @php $contentField = "content_$lang"; @endphp
                            <div class="mb-3" wire:ignore>
                                <label class="form-label">Content ({{ $name }})</label>
                                <input id="{{ $contentField }}" type="hidden" wire:model.live="{{ $contentField }}">
                                <trix-editor input="{{ $contentField }}" x-on:trix-change="$wire.set('{{ $contentField }}', $event.target.value)"></trix-editor>
                                @error($contentField)<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{-- YouTube --}}
                    <div class="mb-3">
                        <label class="form-label">YouTube Link</label>
                        <input placeholder="https://www.youtube.com/watch?v=VtnvFdyvGUQ" type="url" class="form-control" wire:model="youtube_link">
                        @error('youtube_link')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    {{-- Загрузка новых фото --}}
                    <div class="mb-3">
                        <label class="form-label">Фотографии (до 10 шт.)</label>
                        <input type="file" wire:model="photos" multiple class="form-control">
                        <small class="form-text text-muted">Вы можете загрузить до 10 изображений.</small>
                    </div>
                    @if(count($photos) > 0)
                    <h5 class="mt-4">Новые фотографии</h5>
                    <div x-data="sortableComponent">
                        <ul id="newPhotosList" class="list-group mt-3">
                        @foreach($photos as $key=>$photo)
                            <li class="handle list-group-item d-flex align-items-center gap-3" data-key="{{ $key }}">
                                <span style="font-size: 20px;">⇅</span>
                                <img alt="" src="{{ $photo->temporaryUrl() }}" width="100" class="img-thumbnail">
                                <span class="flex-grow-1">Фото {{ $loop->iteration }}</span>
                                <button type="button" wire:click="removePhoto({{ $key }})" class="btn btn-sm btn-danger"> <i class="bi bi-trash"></i>  Удалить</button>
                            </li>
                         @endforeach
                        </ul>
                    </div>
                    @endif
                    {{-- Существующие фото --}}
                    @if($existingPhotos && $existingPhotos->count())
                    <h5 class="mt-4">Существующие фотографии</h5>
                    <div x-data="sortableComponentExistingPhotos">
                        <ul id="existingPhotosList" class="list-group mb-3">
                        @foreach($existingPhotos->sortBy('sort_order') as $image)
                            <li class="handle list-group-item d-flex align-items-center gap-3" data-id="{{ $image->id }}">
                                <span style="font-size: 20px;">⇅</span>
                                <img alt="" src="/{{ $image->image_path }}" width="100" class="img-thumbnail">
                                <span class="flex-grow-1">Фото {{ $loop->iteration }}</span>
                                <button type="button" wire:click="deleteExistingPhoto({{ $image->id }})" class="btn btn-sm btn-danger">Удалить</button>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    @endif
                </fieldset>
        </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-{{ $news->exists ? 'warning' : 'primary'}}"><i class="bi bi-folder-plus"></i> {{ $news->exists ? 'Обновить' : 'Добавить новость' }}</button>
        </div>
    </div>
    </form>


            @push('scripts')
                <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
                <script>
                    document.addEventListener("trix-file-accept", event => {
                        event.preventDefault();
                        alert("В целях безопасности, загрузка файлов отключена со стороны Тарлана.");
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
                                            //Livewire.find(this.$el.closest('[wire\\:id]').getAttribute('wire:id')).call('updatePhotoOrder', order);
                                            Livewire.dispatch('updatePhotoOrder', {orderedKeys: order});
                                        }
                                    });
                                    el._sortable = true;
                                }
                            }
                        }));
                        Alpine.data("sortableComponentExistingPhotos", () => ({
                            init() {
                                const el = this.$el.querySelector('#existingPhotosList');
                                if (el && !el._sortable) {
                                    new Sortable(el, {
                                        animation: 150,
                                        handle: '.handle',
                                        onEnd: () => {
                                            const order = Array.from(el.querySelectorAll('li')).map(li => li.dataset.id);
                                            // Livewire.find(this.$el.closest('[wire\\:id]').getAttribute('wire:id')).call('updateExistingPhotoOrder', order);
                                            Livewire.dispatch('updateExistingPhotoOrder', {orderedIds: order});
                                        }
                                    });
                                    el._sortable = true;
                                }
                            }
                        }));
                    });
                    document.addEventListener('DOMContentLoaded', function () {
                        // Bootstrap validation script
                        const form = document.getElementById('news-edit-form');
                        form.addEventListener('submit', function (event) {
                            if (!form.checkValidity()) {
                                event.preventDefault();
                                event.stopImmediatePropagation();
                            }
                            form.classList.add('was-validated');
                        }, true);
                        // Bootstrap validation script
                    });

                </script>
            @endpush
        </div>

