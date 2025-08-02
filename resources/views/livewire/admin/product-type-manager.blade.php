<div>
    <div class="container">
        <h2 class="h4 mb-4">Управление шаблонами</h2>

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="modal-title">
                    Типы товаров
                </h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="addType" class="row g-2 align-items-center mb-4">
                    <div class="col-auto">
                        <input type="text" wire:model="name" class="form-control" placeholder="Новый тип товара">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </form>
                    <ul class="list-group mb-4">
                        @foreach($types as $type)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="nav-icon bi bi-patch-check-fill text-primary"></i>  {{ $type->name }}</span>
                                <div class="btn-group">
                                    <button wire:click="editType({{ $type->id }})" class="btn btn-sm btn-outline-primary">Редактировать</button>
                                    <button wire:click="manageParameters({{ $type->id }})" class="btn btn-sm btn-outline-success">Параметры</button>
                                    <button wire:click="deleteType({{ $type->id }})" class="btn btn-sm btn-outline-danger">Удалить</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        @if($selectedTypeId)
            <div class="card">
                <div class="card-header">
                    <h5 class="modal-title">
                        Параметры для типа: {{ $selectedTypeName ?? 'Неизвестный тип' }}
                    </h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="addParameter" class="row g-2 align-items-center mb-3">
                        <div class="col-auto">
                            <input type="text" wire:model="parameterName" class="form-control" placeholder="Новый параметр">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">Добавить</button>
                        </div>
                    </form>

                    <ul class="list-group">
                        @foreach($parameterList as $param)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $param['name'] }}</span>
                                <div class="btn-group">
                                    <button wire:click="startEditingParameter({{ $param['id'] }})" class="btn btn-sm btn-outline-primary">Редактировать</button>
                                    <button wire:click="deleteParameter({{ $param['id'] }})" class="btn btn-sm btn-outline-danger">Удалить</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <!-- Edit Parameter Modal -->
    <div class="modal fade" id="editParameterModal" tabindex="-1" aria-labelledby="editParameterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Редактировать параметр</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Название параметра</label>
                        <input type="text" wire:model.defer="editingParameterName" class="form-control">
                        @error('editingParameterName') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" wire:click="updateParameter" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </div>


    <div wire:ignore.self class="modal fade" id="editTypeModal" tabindex="-1" aria-labelledby="editTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="updateType">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTypeModalLabel">Редактировать тип продукта</h5>
                        <button type="button" class="btn-close" wire:click="$set('editTypeId', null)" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" wire:model.defer="editTypeName" class="form-control" placeholder="Название типа продукта">
                        @error('editTypeName') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="$set('editTypeId', null)" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
