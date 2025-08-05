<div class="container py-4">
    <div>
        <h2>Управление шаблонами</h2>

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
                                <span>📌 {{ $type->name }}</span>
                                <div class="btn-group">
                                    <button wire:click="editType({{ $type->id }})" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Редактировать</button>
                                    <button wire:click="manageParameters({{ $type->id }})" class="btn btn-sm btn-success"><i class="bi bi-caret-down-fill"></i> Параметры</button>
                                    <button wire:click="deleteType({{ $type->id }})" class="btn btn-sm btn-danger" wire:confirm="Вы уверены, что хотите удалить данный тип ?"><i class="bi bi-trash"></i> Удалить</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        @if($selectedTypeId)
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="modal-title">
                        Управление параметрами для типа товара: {{ $selectedTypeName ?? 'Неизвестный тип' }}
                    </h5>
                </div>
                <div class="card-body">
                    <fieldset class="border rounded p-3 mb-4">
                        <legend class="float-none w-auto px-2"> Добавить новый параметр для типа товара: {{ $selectedTypeName ?? 'Неизвестный тип' }}</legend>
                        <form wire:submit.prevent="addParameter" class="row g-2 align-items-center mb-3">
                            <div class="col-md-3">
                                <input type="text" wire:model.defer="parameterNameRu" class="form-control" placeholder="Название (RU)">
                            </div>
                            <div class="col-md-3">
                                <input type="text" wire:model.defer="parameterNameEn" class="form-control" placeholder="Название (EN)">
                            </div>
                            <div class="col-md-3">
                                <input type="text" wire:model.defer="parameterNameAz" class="form-control" placeholder="Название (AZ)">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success w-100">Добавить</button>
                            </div>
                        </form>
                    </fieldset>
                    <fieldset class="border rounded p-3 mb-4 mt-5">
                        <legend class="float-none w-auto px-2"> Список параметров для типа товаров: {{ $selectedTypeName ?? 'Неизвестный тип' }}</legend>
                        <ul class="list-group">
                            @foreach($parameterList as $param)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>🔧 {{ $param['name_ru'] }}</span>
                                    <div class="btn-group">
                                        <button wire:click="startEditingParameter({{ $param['id'] }})" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Редактировать</button>
                                        <button wire:click="deleteParameter({{ $param['id'] }})" class="btn btn-sm btn-danger" wire:confirm="Вы уверены, что хотите удалить данный параметр ?"><i class="bi bi-trash"></i> Удалить</button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </fieldset>
                </div>
            </div>
        @endif
    </div>

    <!-- Edit Parameter Modal --><div wire:ignore.self class="modal fade" id="editParameterModal" tabindex="-1" aria-labelledby="editParameterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Редактировать параметр</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Название (RU)</label>
                        <input type="text" wire:model.defer="editingParameterNameRu" class="form-control">
                        @error('editingParameterNameRu') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Название (EN)</label>
                        <input type="text" wire:model.defer="editingParameterNameEn" class="form-control">
                        @error('editingParameterNameEn') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Название (AZ)</label>
                        <input type="text" wire:model.defer="editingParameterNameAz" class="form-control">
                        @error('editingParameterNameAz') <small class="text-danger">{{ $message }}</small> @enderror
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
</div>
<script>
    window.addEventListener('open-edit-parameter-modal', () => {
        let modal = new bootstrap.Modal(document.getElementById('editParameterModal'));
        modal.show();
    });

    window.addEventListener('close-edit-parameter-modal', () => {
        const modalEl = document.getElementById('editParameterModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        }
    });
</script>


