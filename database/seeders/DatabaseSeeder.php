<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Jairo',
            'email' => 'jairo@example.com',
            'password' => bcrypt('12345678'),
            'role' => 'client',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Luis',
            'email' => 'luis@example.com',
            'password' => bcrypt('12345678'),
            'role' => 'delivery',
            'config' => [
                'availability' => false,
            ],
        ]);

        $this->call(EstablishmentSeeder::class);
    }
}
