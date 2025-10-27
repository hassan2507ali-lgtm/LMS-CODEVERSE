<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil CourseSeeder kita
        $this->call([
            CourseSeeder::class,
        ]);
    }
}