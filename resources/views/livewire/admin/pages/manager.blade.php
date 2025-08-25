<div class="container py-4">
    <h1 class="mb-3">Управление страницами</h1>
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h5 class="modal-title"> Список  страниц</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th scope="col">Slug</th>
                    @foreach($locales as $locale => $label)
                        <th scope="col">Название ({{ $label }})</th>
                    @endforeach
                    <th scope="col" class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>

                @foreach($pages as $page)
                    <tr>
                        <td>{{ ucfirst($page->slug) }}</td>
                        @foreach($locales as $locale => $label)
                            <td>{{ $page->title }}</td>
                        @endforeach
                        <td class="text-end">
                            <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>  Редактировать
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">

    </div>

</div>
