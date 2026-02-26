<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course; // Impor Model
use App\Models\Module; // Impor Model
use App\Models\Lesson; // Impor Model
use Illuminate\Support\Str; // Untuk slug

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Kursus 1: Laravel ---
        $course1 = Course::create([
            'title' => 'Dasar-Dasar Laravel 11', 'slug' => Str::slug('Dasar-Dasar Laravel 11'),
            'description' => 'Pelajari fundamental framework Laravel untuk membangun aplikasi web modern.', 'thumbnail' => 'https://placehold.co/600x400/6366f1/ffffff?text=Laravel+11',
            'price' => 0, 'is_free' => true,
        ]);
        $module1_1 = Module::create(['course_id' => $course1->id, 'title' => 'Modul 1: Instalasi & Setup', 'order' => 1]);
        $module1_2 = Module::create(['course_id' => $course1->id, 'title' => 'Modul 2: Routing & Controller', 'order' => 2]);
        Lesson::create(['module_id' => $module1_1->id, 'title' => 'Instalasi Composer & Laravel', 'content_type' => 'video', 'content' => 'placeholder_video_url_1', 'order' => 1]);
        Lesson::create(['module_id' => $module1_1->id, 'title' => 'Konfigurasi Environment (.env)', 'content_type' => 'text', 'content' => 'Penjelasan .env...', 'order' => 2]);
        Lesson::create(['module_id' => $module1_2->id, 'title' => 'Basic Routing', 'content_type' => 'video', 'content' => 'placeholder_video_url_2', 'order' => 1]);

        // --- Kursus 2: Vue JS ---
        $course2 = Course::create([
            'title' => 'Vue JS 3 untuk Pemula', 'slug' => Str::slug('Vue JS 3 untuk Pemula'),
            'description' => 'Kuasai Vue JS, framework JavaScript progresif.', 'thumbnail' => 'https://placehold.co/600x400/41b883/ffffff?text=Vue+JS+3',
            'price' => 250000, 'is_free' => false,
        ]);
        $module2_1 = Module::create(['course_id' => $course2->id, 'title' => 'Modul 1: Pengenalan Vue JS', 'order' => 1]);
        Lesson::create(['module_id' => $module2_1->id, 'title' => 'Setup Proyek Vue', 'content_type' => 'video', 'content' => 'placeholder_video_url_3', 'order' => 1]);

         // --- Kursus 3: Tailwind ---
        $course3 = Course::create([
            'title' => 'Tailwind CSS dari A sampai Z', 'slug' => Str::slug('Tailwind CSS dari A sampai Z'),
            'description' => 'Membangun desain web yang responsif dan modern.', 'thumbnail' => 'https://placehold.co/600x400/38bdf8/ffffff?text=Tailwind+CSS',
            'price' => 150000, 'is_free' => false,
        ]);
        $module3_1 = Module::create(['course_id' => $course3->id, 'title' => 'Modul 1: Setup & Konfigurasi', 'order' => 1]);
        Lesson::create(['module_id' => $module3_1->id, 'title' => 'Instalasi Tailwind', 'content_type' => 'text', 'content' => 'Cara instalasi...', 'order' => 1]);
    }
}