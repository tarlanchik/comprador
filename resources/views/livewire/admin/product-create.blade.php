<div>
    <form wire:submit.prevent="save">
        <div>
            <label>Название</label>
            <input type="text" wire:model="name">
        </div>

        <div>
            <label>Цена</label>
            <input type="text" wire:model="price">
        </div>

        <div>
            <label>Количество</label>
            <input type="number" wire:model="stock">
        </div>

        <div>
            <label>Тип товара</label>
            <select wire:model="product_type_id">
                <option value="">-- выберите --</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        @if(!empty($parameters))
            <div>
                <h4>Параметры</h4>
                @foreach($parameters as $index => $param)
                    <div>
                        <label>{{ $param['name'] }}</label>
                        <input type="text" wire:model="parameters.{{ $index }}.value">
                    </div>
                @endforeach
            </div>
        @endif

        <button type="submit">Сохранить</button>
    </form>
</div>
