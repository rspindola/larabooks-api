<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!Storage::exists('public/images/categories')) {
            Storage::makeDirectory('public/images/categories', 0775, true);
        }
        if (!Storage::exists('public/images/books')) {
            Storage::makeDirectory('public/images/books', 0775, true);
        }
        if (!Storage::exists('public/images/companies')) {
            Storage::makeDirectory('public/images/companies', 0775, true);
        }

        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(BookSeeder::class);
    }
}
