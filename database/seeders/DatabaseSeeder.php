<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\OperatorSeeder;
use Database\Seeders\ClientSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            OperatorSeeder::class,
            ClientSeeder::class
        ]);
    }
}
