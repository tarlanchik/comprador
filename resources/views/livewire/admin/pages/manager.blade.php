<div class="container-fluid">
    <h1 class="mb-3">Управление страницами</h1>
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Slug</th>
            <th>Название (AZ)</th>
            <th>Название (RU)</th>
            <th>Название (EN)</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($pages as $page)
            <tr>
                <td>{{ $page->slug }}</td>
                <td>{{ $page->title_az }}</td>
                <td>{{ $page->title_ru }}</td>
                <td>{{ $page->title_en }}</td>
                <td>
                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-primary">
                        Редактировать
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
