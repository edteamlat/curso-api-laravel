<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Establishment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EstablishmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Establishment::factory()
            ->has(Product::factory()->count(5))
            ->count(50)
            ->create();
    }
}
