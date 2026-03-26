<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('practices', function (Blueprint $table) {
            // is_free = true (gratis total), false (berbayar)
            $table->boolean('is_free')->default(true)->after('category');
            
            // Harga (opsional jika gratis)
            $table->unsignedBigInteger('price')->nullable()->after('is_free'); 
            
            // Berapa soal pertama yang bisa diakses gratis untuk tes (default 2)
            $table->integer('free_exercises_count')->default(0)->after('price'); 
        });
    }

    public function down(): void
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->dropColumn(['is_free', 'price', 'free_exercises_count']);
        });
    }
};