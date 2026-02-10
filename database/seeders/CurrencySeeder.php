<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Currency::create(['code' => 'USD', 'symbol' => '$', 'name' => 'US Dollar']);
Currency::create(['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro']);
Currency::create(['code' => 'GBP', 'symbol' => '£', 'name' => 'British Pound']);
Currency::create(['code' => 'JPY', 'symbol' => '¥', 'name' => 'Japanese Yen']);
Currency::create(['code' => 'AUD', 'symbol' => 'A$', 'name' => 'Australian Dollar']);
Currency::create(['code' => 'CAD', 'symbol' => 'C$', 'name' => 'Canadian Dollar']);
Currency::create(['code' => 'CHF', 'symbol' => 'CHF', 'name' => 'Swiss Franc']);
Currency::create(['code' => 'CNY', 'symbol' => '¥', 'name' => 'Chinese Yuan']);
Currency::create(['code' => 'PKR', 'symbol' => '₨', 'name' => 'Pakistani Rupee']);
Currency::create(['code' => 'INR', 'symbol' => '₹', 'name' => 'Indian Rupee']);
Currency::create(['code' => 'NZD', 'symbol' => 'NZ$', 'name' => 'New Zealand Dollar']);
Currency::create(['code' => 'SGD', 'symbol' => 'S$', 'name' => 'Singapore Dollar']);

    }
}
