<div class="container py-4">
    <h2>Список Товаров</h2>
    @if(session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-header">
            <h5 class="modal-title">Список товаров</h5>
        </div>
        <div class="card-body">
            <div class="card">
                <div class="card-body table-responsive">

                    @if (!empty($goods) && count($goods) > 0)
                    <table class="table table-striped table-hover align-middle text-center">
                        <thead class="table-secondary">
                        <tr>
                            <!--<th scope="col">#ID</th>-->
                            <th scope="col">Название</th>
                            <th scope="col">Категория</th>
                            <th scope="col">Цена</th>
                            <th scope="col">Кол-во</th>
                            <!-- <th scope="cosl">Создан</th> -->
                            <th scope="col">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($goods as $good)
                            <tr>
                               <!--<td>{{ $good->id }}</td>-->
                                <td class="text-start">{{ $good->name_ru }}</td>
                                <td>{{ $good->category->name_ru ?? '-' }}</td>
                                <td>{{ number_format($good->price, 2, '.', ' ') }} ₼</td>
                                <td>{{ $good->count }}</td>
                                <!-- <td>{{ $good->created_at->format('d.m.Y') }}</td> -->
                                <td>
                                    <div  class="float-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.goods.edit', $good->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-square"></i> Редактировать
                                            </a>
                                            <button wire:click="delete({{ $good->id }})"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Вы уверены, что хотите удалить этот товар?')">
                                                <i class="bi bi-trash"></i> Удалить
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="alert alert-warning text-center mx-auto" role="alert">
                            <strong>Обратите внимание!</strong>  <br>Пока нет доступных товаров для отображения.
                        </div>
                    @endif

                </div>
                <div class="card-footer">
                    {{ $goods->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
