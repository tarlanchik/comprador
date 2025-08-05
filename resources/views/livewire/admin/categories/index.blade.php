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

    <form wire:submit.prevent="addCategory" class="row g-3 mb-4">
            <div class="card">
            <div class="card-header">
                <h5 class="modal-title">Добавление категории</h5>
            </div>
            <div class="card-body">
                <div class="row g-2 align-items-start">
                    <div class="col-md-3">
                        <input type="text" wire:model="name_ru" class="form-control" placeholder="Название категории (RU)">
                        @error('name_ru') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3">
                        <input type="text" wire:model="name_en" class="form-control" placeholder="Category Name (EN)">
                        @error('name_en') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3">
                        <input type="text" wire:model="name_az" class="form-control" placeholder="Kateqoriya adı (AZ)">
                        @error('name_az') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3">
                        <select wire:model="parent_id" class="form-select">
                            <option value="">-- Нет родителя (корневая) --</option>
                            @foreach($this->categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name_ru }}</option>
                                @foreach($cat->children as $child)
                                    <option value="{{ $child->id }}">↳ {{ $child->name_ru }}</option>
                                    @foreach($child->children as $grandchild)
                                        <option value="{{ $grandchild->id }}" disabled>⮑ {{ $grandchild->name_ru }}</option>
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
                    <button type="submit" class="btn btn-primary float-end">Добавить</button>
                </div>
            </div>
        </div>
    </form>

    <hr>
        <div class="card">
            <div class="card-header">
                <h5 class="modal-title">
                    Список категорий
                </h5>
            </div>
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
                                    <button wire:click="editCategory({{ $category->id }})" class="btn btn-sm btn-warning ms-2"><i class="bi bi-pencil-square"></i> Редактировать</button>
                                    <button wire:click="deleteCategory({{ $category->id }})" class="btn btn-sm btn-danger ms-2" onclick="confirm('Удалить эту категорию?') || event.stopImmediatePropagation()"><i class="bi bi-trash"></i> Удалить</button>
                                </td>
                            </tr>
                            @if($category->children->count())
                                @foreach($category->children as $child)
                            <tr>
                                <td><div class="mx-3 text-info">↳ {{ $child->name_ru }}</div></td>
                                <td class="text-end">
                                    <button wire:click="editCategory({{ $child->id }})" class="btn btn-sm btn-warning ms-2"><i class="bi bi-pencil-square"></i> Редактировать</button>
                                    <button wire:click="deleteCategory({{ $child->id }})" class="btn btn-sm btn-danger ms-2" onclick="confirm('Удалить эту категорию?') || event.stopImmediatePropagation()"><i class="bi bi-trash"></i> Удалить</button>
                                </td>
                            </tr>
                                @if($child->children->count())
                                    @foreach($child->children as $grandchild)
                            <tr>
                                <td><div class="mx-4 text-secondary">⮑ {{ $grandchild->name_ru }}</div></td>
                                <td class="text-end">
                                    <button wire:click="editCategory({{ $grandchild->id }})" class="btn btn-sm btn-warning ms-2"><i class="bi bi-pencil-square"></i> Редактировать</button>
                                    <button wire:click="deleteCategory({{ $grandchild->id }})" class="btn btn-sm btn-danger ms-2" onclick="confirm('Удалить эту категорию?') || event.stopImmediatePropagation()"><i class="bi bi-trash"></i> Удалить</button>
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
        </div>

    <!-- Модальное окно для редактирования -->
    @if($editId)
        <div wire:ignore.self class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form wire:submit.prevent="updateCategory" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Редактировать категорию</h5>
                        <button type="button" class="btn-close" wire:click="$set('editId', null)" aria-label="Закрыть" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" wire:model.defer="editNameRu" class="form-control mb-3" placeholder="Название категории (Русский)">
                        @error('editNameRu') <small class="text-danger">{{ $message }}</small> @enderror

                        <input type="text" wire:model.defer="editNameEn" class="form-control mb-3" placeholder="Category Name (English)">
                        @error('editNameEn') <small class="text-danger">{{ $message }}</small> @enderror

                        <input type="text" wire:model.defer="editNameAz" class="form-control mb-3" placeholder="Kateqoriya adı (Azərbaycan)">
                        @error('editNameAz') <small class="text-danger">{{ $message }}</small> @enderror

                        <select wire:model.defer="editParentId" class="form-select">
                            <option value="">-- Нет родителя (корневая) --</option>
                            @foreach($this->categories as $cat)
                                @if($cat->id != $editId)
                                <option value="{{ $cat->id }}">{{ $cat->name_ru }}</option>
                                    @foreach($cat->children as $child)
                                        @if($child->id != $editId)
                                        <option value="{{ $child->id }}">↳ {{ $child->name_ru }}</option>
                                            @foreach($child->children as $grandchild)
                                                @if($grandchild->id != $editId)
                                                    <option value="{{ $grandchild->id }}" disabled>⮑ {{ $grandchild->name_ru }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                        @error('editParentId') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="$set('editId', null)" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
