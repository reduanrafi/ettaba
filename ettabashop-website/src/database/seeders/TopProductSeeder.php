<?php

namespace Database\Seeders;

use App\Models\TopProduct;
use Illuminate\Database\Seeder;

class TopProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TopProduct::factory()->times(200)->create();
    }
}
