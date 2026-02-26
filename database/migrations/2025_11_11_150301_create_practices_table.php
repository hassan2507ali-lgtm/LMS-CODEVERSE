<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('practices', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->string('thumbnail')->nullable(); // Tetap ada untuk gambar
        $table->string('github_link')->nullable(); // Tambahkan ini untuk link
        $table->string('category');
        $table->json('tags')->nullable();
        $table->text('content')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('practices');
    }
};