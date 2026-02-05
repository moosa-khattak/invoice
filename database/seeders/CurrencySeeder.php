<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Currency::create(['code' => 'USD', 'symbol' => '$', 'name' => 'US Dollar']);
        \App\Models\Currency::create(['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro']);
        \App\Models\Currency::create(['code' => 'UGX', 'symbol' => 'USh', 'name' => 'Ugandan Shilling']);
    }
}
