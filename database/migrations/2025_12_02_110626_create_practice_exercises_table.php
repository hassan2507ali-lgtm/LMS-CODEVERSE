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
        Schema::create('practice_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_id')->constrained()->onDelete('cascade');
            $table->string('title');
            
            // INI KOLOM BAHASANYA KITA MASUKKAN LANGSUNG KE SINI
            $table->string('language')->default('python'); 
            
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->text('starter_code')->nullable();
            $table->text('solution_code')->nullable();
            $table->text('hints')->nullable();
            $table->integer('order')->default(0);
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_exercises');
    }
};