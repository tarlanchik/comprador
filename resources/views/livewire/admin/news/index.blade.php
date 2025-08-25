<div class="container py-4">
    <h2>Управления новостями</h2>
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif


    @if (count($newsItems) == 0)
        <div class="card">
            <div class="card-header">⚡️ Внимание!</div>
            <div class="card-body">
                <div class="alert alert-warning text-center mx-auto" role="alert">
                    Пока нет доступных новостей для отображения.
                </div>
            </div>
            <div class="card-footer">
                &nbsp;
            </div>
        </div>

    @else

    <div class="card">
        <div class="card-header"><i class="bi bi-search"></i>  Поиск товаров по заголовку</div>
        <div class="card-body">
            <fieldset class="border rounded p-3 mb-4">
                <legend class="float-none w-auto px-2">Введите запрос в строку поиска и нажмите Поиск</legend>
                <div class="input-group mb-3" style="max-width: 100%">
                    <input type="text" class="form-control" wire:model.defer="search" placeholder="🔍 Поиск новостей по заголовкам...">
                    <button class="btn btn-primary" wire:click="searchNews"><i class="bi bi-search"></i> Поиск</button>
                </div>
            </fieldset>
        </div>
        <div class="card-footer">
            &nbsp;
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header"><i class="bi bi-card-checklist"></i> Список новостей</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Заголовок (AZ)</th>
                        <th scope="col">Создано</th>
                        <th scope="col" class="text-end">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($newsItems as $news)
                        <tr>
                            <td>{{ $news->id }}</td>
                            <td>{{ Str::limit($news->title_ru, 60) }}</td>
                            <td>{{ $news->created_at->format('d.m.Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil-square"></i> Редактировать</a>
                                <button wire:click="delete({{ $news->id }})" onclick="confirm('Удалить эту новость?') || event.stopImmediatePropagation()" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Удалить</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $newsItems->links('pagination::bootstrap-5') }} &nbsp;
            </div>
        </div>
    </div>
    @endif
</div>
