<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        $this->call([
            ProductTypeSeeder::class,
            ParameterSeeder::class,
            CategorySeeder::class,
        ]);
*/
        User::factory()->create([
            'name' => 'Tarlan',
            'email' => 'tarlanchik@gmail.com',
            'password' => Hash::make('0okm9ijn'),
        ]);

    }
}
