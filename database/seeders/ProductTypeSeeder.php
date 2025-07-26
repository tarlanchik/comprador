<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductType::insert([
            ['name' => 'Процессор'],
            ['name' => 'Ноутбук'],
            ['name' => 'Монитор'],
        ]);
    }
}
