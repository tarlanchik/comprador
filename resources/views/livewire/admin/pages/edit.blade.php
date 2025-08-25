<div class="container py-4">
    <style>
        fieldset {
            border: solid 1px gray;
            padding-top: 5px;
            padding-right: 12px;
            padding-bottom: 10px;
            padding-left: 12px;
        }
        legend {
            float: none;
            width: inherit;
        }
        </style>
    <h1 class="mb-4">Редактирование страницы: {{ $page->slug }}</h1>

    {{-- Навигация по языкам --}}
    <ul class="nav nav-tabs" role="tablist">
        @foreach($locales as $locale => $label)
            <li class="nav-item">
                <button class="nav-link @if($loop->first) active @endif" data-bs-toggle="tab" data-bs-target="#tab-{{ $locale }}" type="button" role="tab">
                    {{ $label }}
                </button>
            </li>
        @endforeach
    </ul>

    <form wire:submit.prevent="save" class="needs-validation" novalidate id="page-edit-form">
        <div class="tab-content p-3 border border-top-0">
            @foreach($locales as $locale => $label)
                <div class="tab-pane fade @if($loop->first) show active @endif" id="tab-{{ $locale }}" role="tabpanel">

                    {{-- Название --}}
                    <div class="mb-3">
                        <label for="titles.{{ $locale }}" class="form-label">Название ({{ $label }})</label>
                        <input id="titles.{{ $locale }}" type="text" class="form-control" wire:model.defer="titles.{{ $locale }}" required>
                        @error("titles.{$locale}")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- SEO --}}
                    <hr class="border border-primary border-1 opacity-75">
                    <fieldset class="border rounded-3 p-4 shadow-sm bg-light mb-4">
                        <legend class="float-none w-auto px-3 fw-bold text-primary fs-5">SEO optimization area</legend>
                    <div class="mb-3">
                        <label for="seo_titles.{{ $locale }}" class="form-label">SEO Title ({{ $label }})</label>
                        <input maxlength="60" id="seo_titles.{{ $locale }}" type="text" class="form-control" wire:model.defer="seo_titles.{{ $locale }}" required>
                        <small class="text-muted">
                            {{ strlen($seo_titles[$locale] ?? '') }}/60 Символы
                        </small>
                        @error("seo_titles.{$locale}")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="seo_descriptions.{{ $locale }}" class="form-label">SEO Description ({{ $label }})</label>
                        <textarea maxlength="160" id="seo_descriptions.{{ $locale }}" class="form-control" rows="3" wire:model.defer="seo_descriptions.{{ $locale }}" required></textarea>
                        <small class="text-muted">
                            {{ strlen($seo_descriptions[$locale] ?? '') }}/160 Символы
                        </small>
                        @error("seo_descriptions.{$locale}")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="seo_keywords.{{ $locale }}seo_keywords.{{ $locale }}" class="form-label">SEO Keywords ({{ $label }})</label>
                        <input maxlength="255" type="text" id="seo_keywords.{{ $locale }}" class="form-control" wire:model.defer="seo_keywords.{{ $locale }}" required>
                        <small class="text-muted">Укажите через запятую. {{ strlen($seo_keywords[$locale] ?? '') }}/255 Символы</small>
                        @error("seo_keywords.{$locale}")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    </fieldset>
                    {{-- Контент --}}
                    <hr class="border border-primary border-1 opacity-75">
                    <div class="mb-3">
                        <label for="contents.{{ $locale }}" class="form-label">Контент ({{ $label }})</label>
                        <textarea placeholder="Опционально - для вывода контента в нижней части страницы" id="contents.{{ $locale }}" class="form-control" rows="8" wire:model.defer="contents.{{ $locale }}"></textarea>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Bootstrap validation script
            const form = document.getElementById('page-edit-form');
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
                form.classList.add('was-validated');
            }, true);
            // Bootstrap validation script

            const triggerTabList = [].slice.call(document.querySelectorAll('#tablist button'))
            triggerTabList.forEach(function (triggerEl) {
                const tabTrigger = new bootstrap.Tab(triggerEl)
                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            })
        });
    </script>

</div>
