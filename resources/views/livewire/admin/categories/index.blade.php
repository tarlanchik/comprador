<div class="container py-4">
    <h2>Управление категориями</h2>
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form wire:submit.prevent="addCategory" class="mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="modal-title"> {{ $isEditing ? 'Редактировать категорию' : 'Добавление категории' }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-2 align-items-start">
                    @foreach($locales as $lang=>$label)
                        <div class="col-md-3">
                            <input type="text" wire:model="form.name_{{ $lang }}" class="form-control"
                                   placeholder="Название категории ({{$label}})">
                            @error("form.name_$lang") <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    @endforeach
                    <div class="col-md-3">
                        <select wire:model="form.parent_id" class="form-select">
                            <option value="">-- Нет родителя (корневая) --</option>
                            @foreach($this->categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name_ru }}</option>
                                @foreach($cat->children as $child)
                                    <option value="{{ $child->id }}">↳ {{ $child->name_ru }}</option>
                                    @foreach($child->children as $grandchild)
                                        <option value="{{ $grandchild->id }}" disabled>
                                            ⮑ {{ $grandchild->name_ru }}</option>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                        @error('parent_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="col">
                    <button type="submit" class="btn btn-{{ $isEditing ? 'warning' : 'primary'}} float-end">
                        @if($isEditing)
                            <i class="bi bi-pencil-square"></i> Обновить
                        @else
                            <i class="bi bi-folder-plus"></i> Добавить
                        @endif
                    </button>
                    @if($isEditing)
                        <button type="button" wire:click="resetForm" class="btn btn-secondary float-end me-2">
                            <i class="bi bi-x-octagon"></i> Отмена
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </form>

    @if(sizeof($this->categories) > 0)
    <hr class="border border-primary border-1 opacity-75">
    <div class="card">
        <div class="card-header"><h5 class="modal-title">Список категорий</h5></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Имена категорий</th>
                            <th scope="col"><div class="text-end">Управление категориями</div></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($this->categories as $category)
                        <tr>
                            <td><div class="text-primary">{{ $category->name_ru }}</div></td>
                            <td class="text-end">
                                <button wire:click="editCategory({{ $category->id }})"
                                        class="btn btn-sm btn-warning ms-2"><i class="bi bi-pencil-square"></i>
                                    Редактировать
                                </button>
                                <button wire:click="deleteCategory({{ $category->id }})"
                                        class="btn btn-sm btn-danger ms-2"
                                        onclick="confirm('Удалить эту категорию?') || event.stopImmediatePropagation()">
                                    <i class="bi bi-trash"></i> Удалить
                                </button>
                            </td>
                        </tr>
                        @if($category->children->count())
                            @foreach($category->children as $child)
                                <tr>
                                    <td>
                                        <div class="mx-3 text-info">↳ {{ $child->name_ru }}</div>
                                    </td>
                                    <td class="text-end">
                                        <button wire:click="editCategory({{ $child->id }})"
                                                class="btn btn-sm btn-warning ms-2"><i class="bi bi-pencil-square"></i>
                                            Редактировать
                                        </button>
                                        <button wire:click="deleteCategory({{ $child->id }})"
                                                class="btn btn-sm btn-danger ms-2"
                                                onclick="confirm('Удалить эту категорию?') || event.stopImmediatePropagation()">
                                            <i class="bi bi-trash"></i> Удалить
                                        </button>
                                    </td>
                                </tr>
                                @if($child->children->count())
                                    @foreach($child->children as $grandchild)
                                        <tr>
                                            <td>
                                                <div class="mx-4 text-secondary">⮑ {{ $grandchild->name_ru }}</div>
                                            </td>
                                            <td class="text-end">
                                                <button wire:click="editCategory({{ $grandchild->id }})"
                                                        class="btn btn-sm btn-warning ms-2"><i
                                                        class="bi bi-pencil-square"></i> Редактировать
                                                </button>
                                                <button wire:click="deleteCategory({{ $grandchild->id }})"
                                                        class="btn btn-sm btn-danger ms-2"
                                                        onclick="confirm('Удалить эту категорию?') || event.stopImmediatePropagation()">
                                                    <i class="bi bi-trash"></i> Удалить
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">&nbsp;</div>
    </div>
    @endif
</div>
