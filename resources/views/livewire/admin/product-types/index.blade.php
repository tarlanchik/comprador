<div class="container py-4">
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

        <div class="card"><div class="card-header"><h5 class="modal-title">Типы товаров</h5></div>
            <div class="card-body">
                <fieldset class="border rounded-3 p-4 shadow-sm bg-light mb-4">
                    <legend class="float-none w-auto px-3 fw-bold text-primary fs-5">Типы товаров</legend>
                    <form wire:submit.prevent="addType" class="align-items-center mb-4">
                        <div class="input-group mb-3">
                            <input type="text" wire:model="name" class="form-control" placeholder="Введите имя новый тип товара">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-bag-plus-fill"></i> Добавить</button>
                        </div>
                    </form>
                </fieldset>
                @if(sizeof($types) > 0)
                <fieldset class="border rounded-3 p-4 shadow-sm bg-light mb-4">
                    <legend class="float-none w-auto px-3 fw-bold text-primary fs-5">Список типов товаров</legend>
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">Имена типов товаров</th>
                            <th scope="col" class="text-end">Управление типами</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($types as $type)
                            <tr>
                                <td><span>📌 {{ $type->name }}</span></td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button wire:click="editType({{ $type->id }})" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Редактировать</button>
                                        <button wire:click="manageParameters({{ $type->id }})" class="btn btn-sm btn-success"><i class="bi bi-caret-down-fill"></i> Управление параметрами</button>
                                        <button wire:click="deleteType({{ $type->id }})" class="btn btn-sm btn-danger" wire:confirm="Вы уверены, что хотите удалить данный тип ?"><i class="bi bi-trash"></i> Удалить</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <div class="card-footer">&nbsp;</div>
            @endif
        </div>

        @if($selectedTypeId)
        <hr class="border border-primary border-1 opacity-75">
            <div class="card">
                <div class="card-header">
                    <h5 class="modal-title">
                        Управление параметрами для типа товара: {{ $selectedTypeName ?? 'Неизвестный тип' }}
                    </h5>
                </div>
                <div class="card-body">
                    <fieldset class="border rounded-3 p-4 shadow-sm bg-light mb-4">
                        <legend class="float-none w-auto px-3 fw-bold text-primary fs-5"> Добавить новый параметр для типа товара: {{ $selectedTypeName ?? 'Неизвестный тип' }}</legend>
                        <form wire:submit.prevent="addParameter" class="row g-2 align-items-center mb-3">
                            @foreach($locales as $lang=>$label)
                                @php $lang = ucfirst($lang) @endphp
                            <div class="col-md-3">
                                <input type="text" wire:model.defer="parameterName{{$lang}}" class="form-control" placeholder="Название ({{$label}})">
                            </div>
                            @endforeach
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success w-100"><i class="bi bi-plus-circle"></i> Добавить</button>
                            </div>
                        </form>
                    </fieldset>
                    @if(count($parameterList) > 0)
                    <fieldset class="border rounded-3 p-4 shadow-sm bg-light mb-4">
                            <legend class="float-none w-auto px-3 fw-bold text-primary fs-5"> Список параметров для типа товаров: {{ $selectedTypeName ?? 'Неизвестный тип' }}</legend>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th scope="col">Имена параметров товара</th>
                                        <th scope="col" class="text-end">Управление параметрами</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($parameterList as $param)
                                        <tr>
                                            <td>🔑 {{ $param['name_ru'] }}</td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                <button wire:click="startEditingParameter({{ $param->id }})" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Редактировать</button>
                                                <button wire:click="deleteParameter({{ $param['id'] }})" class="btn btn-sm btn-danger" wire:confirm="Вы уверены, что хотите удалить данный параметр ?"><i class="bi bi-trash"></i> Удалить</button>
                                            </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                    </fieldset>
                    @endif
                </div>
                <div class="card-footer">&nbsp;</div>
            </div>
        @endif


    <!-- Edit Parameter Modal -->
    <div wire:ignore.self class="modal fade" id="editParameterModal" tabindex="-1" aria-labelledby="editParameterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Редактировать параметр</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    @foreach($locales as $lang=>$label)
                        @php $lang = ucfirst($lang) @endphp
                    <div class="mb-3">
                        <label class="form-label">Название ({{$label}})</label>
                        <input type="text" wire:model.defer="editingParameterName{{$lang}}" class="form-control">
                        @error('editingParameterName$lang') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-octagon"></i> Отмена</button>
                    <button type="button" wire:click="updateParameter" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Обновить</button>
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
                        <button type="button" wire:click="$set('editTypeId', null)" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-octagon"></i> Отмена</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Обновить</button>
                    </div>
                </form>
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

    window.addEventListener('open-edit-type-modal', () => {
        let modal = new bootstrap.Modal(document.getElementById('editTypeModal'));
        modal.show();
    });

    window.addEventListener('close-edit-type-modal', () => {
        const modalEl = document.getElementById('editTypeModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        }    });
</script>
</div>


