<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        // Optional: clear previous data
        Table::truncate();

        // Generate 10 fake tables using the factory
        Table::factory()->count(10)->create();
    }
}
