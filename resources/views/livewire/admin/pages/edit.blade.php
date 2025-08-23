<div class="container-fluid">
    <h1 class="mb-4">Редактирование страницы: {{ $page->slug }}</h1>

    {{-- Навигация по языкам --}}
    <ul class="nav nav-tabs" role="tablist">
        @foreach($locales as $locale => $label)
            <li class="nav-item">
                <button class="nav-link @if($loop->first) active @endif"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-{{ $locale }}"
                        type="button"
                        role="tab">
                    {{ $label }}
                </button>
            </li>
        @endforeach
    </ul>

    <form wire:submit.prevent="save">
        <div class="tab-content p-3 border border-top-0">
            @foreach($locales as $locale => $label)
                <div class="tab-pane fade @if($loop->first) show active @endif"
                     id="tab-{{ $locale }}" role="tabpanel">

                    {{-- Название --}}
                    <div class="mb-3">
                        <label class="form-label">Название ({{ $label }})</label>
                        <input type="text" class="form-control" wire:model.defer="titles.{{ $locale }}">
                        @error("titles.{$locale}")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- SEO --}}
                    <hr>
                    <h5 class="mt-3">SEO ({{ $label }})</h5>

                    <div class="mb-3">
                        <label class="form-label">SEO Title</label>
                        <input type="text" class="form-control" wire:model.defer="seo_titles.{{ $locale }}">
                        @error("seo_titles.{$locale}")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SEO Description</label>
                        <textarea class="form-control" rows="3" wire:model.defer="seo_descriptions.{{ $locale }}"></textarea>
                        @error("seo_descriptions.{$locale}")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SEO Keywords</label>
                        <input type="text" class="form-control" wire:model.defer="seo_keywords.{{ $locale }}">
                        <small class="text-muted">Укажите через запятую</small>
                        @error("seo_keywords.{$locale}")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Контент --}}
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Контент ({{ $label }})</label>
                        <textarea class="form-control" rows="8" wire:model.defer="contents.{{ $locale }}"></textarea>
                        @error("contents.{$locale}")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Общие поля --}}
        <hr>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Статус</label>
                <select class="form-select" wire:model="is_active">
                    <option value="1">Активна</option>
                    <option value="0">Скрыта</option>
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Порядок сортировки</label>
                <input type="number" class="form-control" wire:model="sort_order">
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3">
            <i class="fas fa-save"></i> Сохранить
        </button>
    </form>
</div>
