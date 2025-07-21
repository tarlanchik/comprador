<div class="container py-5">
    @if(session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h2>Все Товары</h2>
    <div class="card">
        <div class="card-header">
            <h5 class="modal-title">Список товаров</h5>
        </div>
        <div class="card-body">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped table-hover align-middle text-center">
                        <thead class="table-secondary">
                        <tr>
                            <!--<th scope="col">#ID</th>-->
                            <th scope="col">Название</th>
                            <th scope="col">Категория</th>
                            <th scope="col">Цена</th>
                            <th scope="col">Кол-во</th>
                            <!-- <th scope="col">Создан</th> -->
                            <th scope="col">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($goods as $good)
                            <tr>
                               <!--<td>{{ $good->id }}</td>-->
                                <td class="text-start">{{ $good->name_ru }}</td>
                                <td>{{ $good->category->name_ru ?? '-' }}</td>
                                <td>{{ number_format($good->price, 2, '.', ' ') }} ₽</td>
                                <td>{{ $good->count }}</td>
                                <!-- <td>{{ $good->created_at->format('d.m.Y') }}</td> -->
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.goods-edit', $good->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i> Редактировать
                                        </a>
                                        <form action="{{ route('admin.goods-index', $good->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот товар?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Удалить
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="card-footer">
                    {{ $goods->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
