<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            // <-- BARIS PENTING: Foreign Key ke tabel modules
            $table->foreignId('module_id')
                  ->constrained('modules')
                  ->onDelete('cascade'); // Jika module dihapus, lesson ikut terhapus
            $table->string('title'); // <-- Kolom Judul Pelajaran
            $table->enum('content_type', ['video', 'text', 'image'])->default('text'); // <-- Tipe Konten
            $table->text('content'); // <-- Isi Konten (URL video, teks, path gambar)
            $table->integer('order')->default(0); // <-- Kolom Urutan Pelajaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};