<?php

namespace Database\Seeders;

use App\Models\Parameter;
use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParameterSeeder extends Seeder
{
    public function run(): void
    {
        $processor = ProductType::where('name', 'Процессор')->first();
        $laptop    = ProductType::where('name', 'Ноутбук')->first();
        $monitor   = ProductType::where('name', 'Монитор')->first();

        Parameter::insert([
            // Процессор
            ['product_type_id' => $processor->id, 'name' => 'Сокет'],
            ['product_type_id' => $processor->id, 'name' => 'Тактовая частота'],
            ['product_type_id' => $processor->id, 'name' => 'Количество потоков'],

            // Ноутбук
            ['product_type_id' => $laptop->id, 'name' => 'Оперативная память'],
            ['product_type_id' => $laptop->id, 'name' => 'Жесткий диск'],
            ['product_type_id' => $laptop->id, 'name' => 'Видеокарта'],

            // Монитор
            ['product_type_id' => $monitor->id, 'name' => 'Диагональ'],
            ['product_type_id' => $monitor->id, 'name' => 'Частота обновления'],
        ]);
    }
}
