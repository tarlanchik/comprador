<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Удалим старые данные
        Category::truncate();

        // Уровень 1
        $electronics = Category::create(['name' => 'Электроника']);
        $fashion = Category::create(['name' => 'Одежда']);
        $books = Category::create(['name' => 'Книги']);

        // Уровень 2
        $phones = Category::create(['name' => 'Смартфоны', 'parent_id' => $electronics->id]);
        $laptops = Category::create(['name' => 'Ноутбуки', 'parent_id' => $electronics->id]);

        $men = Category::create(['name' => 'Мужская', 'parent_id' => $fashion->id]);
        $women = Category::create(['name' => 'Женская', 'parent_id' => $fashion->id]);

        // Уровень 3
        Category::create(['name' => 'Android', 'parent_id' => $phones->id]);
        Category::create(['name' => 'iPhone', 'parent_id' => $phones->id]);

        Category::create(['name' => 'Игровые ноутбуки', 'parent_id' => $laptops->id]);
        Category::create(['name' => 'Ультрабуки', 'parent_id' => $laptops->id]);

        Category::create(['name' => 'Футболки', 'parent_id' => $men->id]);
        Category::create(['name' => 'Куртки', 'parent_id' => $women->id]);
    }
}
