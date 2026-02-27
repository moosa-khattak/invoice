<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //user create
     

        // User::factory(10)->create();
        $this->call(CurrencySeeder::class);
        User::create([
                    'name' => 'Admin',
                    'email' => 'admin@gmail.com',
                    'password' => bcrypt('password'),
                ]);
       
    }
}
