<div class="container py-5">
    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    @endpush

    @push('scripts')
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
            <script>
                document.addEventListener("trix-file-accept", function (event) {
                    event.preventDefault();
                    alert("Загрузка файлов отключена.");
                });
            </script>
    @endpush
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
    <h2 class="mb-4">{{ $news && $news->exists ? 'Редактировать новость' : 'Добавить новость' }}</h2>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="{{ $news && $news->exists ? 'update' : 'save' }}" enctype="multipart/form-data">
        <ul class="nav nav-tabs mb-3" id="langTabs" role="tablist">
            @foreach (['az', 'ru', 'en'] as $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if($loop->first) active @endif" id="tab-{{ $lang }}" data-bs-toggle="tab" data-bs-target="#content-{{ $lang }}" type="button" role="tab">
                        {{ strtoupper($lang) }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content mb-3">
            @foreach (['az', 'ru', 'en'] as $lang)
                <div class="tab-pane fade @if($loop->first) show active @endif" id="content-{{ $lang }}" role="tabpanel">
                    <div class="mb-3">
                        <label class="form-label">Title ({{ strtoupper($lang) }})</label>
                        <input type="text" class="form-control" wire:model.defer="title_{{ $lang }}">
                        @error('title_' . $lang) <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keywords ({{ strtoupper($lang) }})</label>
                        <input type="text" class="form-control" wire:model.defer="keywords_{{ $lang }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description ({{ strtoupper($lang) }})</label>
                        <textarea class="form-control" rows="3" wire:model.defer="description_{{ $lang }}"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content ({{ strtoupper($lang) }})</label>
                        <input id="content_{{ $lang }}" type="hidden" wire:model.defer="content_en">
                        <trix-editor input="content_{{ $lang }}"></trix-editor>
                        @error('content_' . $lang) <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mb-3">
            <label class="form-label">YouTube Link</label>
            <input type="url" class="form-control" wire:model.defer="youtube_link">
            @error('youtube_link') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Фотографии (до 10 шт.)</label>
            <input type="file" wire:model="photos" multiple class="form-control">
            <small class="form-text text-muted">Вы можете загрузить до 10 изображений.</small>
        </div>

        @if(count($photos) > 0)
            <h5 class="mt-4">Новые фотографии</h5>
            <div x-data="sortableComponent">
                <ul class="list-group mt-3" id="newPhotosList">
                    @foreach ($photos as $key => $photo)
                        <li class="handle list-group-item d-flex align-items-center gap-3" data-key="{{ $key }}">
                            <span style="font-size: 20px;">⇅</span>
                            <img alt="" src="{{ $photo->temporaryUrl() }}" width="100" class="img-thumbnail">
                            <span class="flex-grow-1">Фото {{ $loop->iteration }}</span>
                            <button wire:click="removePhoto({{ $key }})" class="btn btn-sm btn-danger">Удалить</button>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($news->images && $news->images->count())
            <div x-data="sortableComponentExistingPhotos">
                <ul id="existingPhotosList" class="list-group mb-3">
                    @foreach ($news->images->sortBy('sort_order') as $image)
                        <li class="handle list-group-item d-flex align-items-center gap-3" data-id="{{ $image->id }}">
                            <span style="font-size: 20px;">⇅</span>
                            <img alt="" src="/{{ $image->image_path }}" width="100" class="img-thumbnail">
                            <span class="flex-grow-1">Фото {{ $loop->iteration }}</span>
                            <button type="button" class="btn btn-sm btn-danger" wire:click="deleteExistingPhoto({{ $image->id }})">Удалить</button>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button class="btn btn-primary" type="submit">
            {{ $news && $news->exists ? 'Сохранить изменения' : 'Добавить новость' }}
        </button>
    </form>

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
                }
            }));
        });

        document.addEventListener("alpine:init", () => {
            Alpine.data("sortableComponentExistingPhotos", () => ({
                init() {
                    const el = this.$el.querySelector('#existingPhotosList');
                    if (el && !el._sortable) {
                        new Sortable(el, {
                            animation: 150,
                            handle: '.handle',
                            onEnd: () => {
                                const order = Array.from(el.querySelectorAll('li')).map(li => li.dataset.id);
                                const livewireComponent = Livewire.find(this.$el.closest('[wire\\:id]').getAttribute('wire:id'));
                                livewireComponent.call('updateExistingPhotoOrder', order);
                            }
                        });
                        el._sortable = true;
                    }
                }
            }));
        });
    </script>
</div>
