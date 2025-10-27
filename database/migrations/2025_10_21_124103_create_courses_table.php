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
    Schema::create('courses', function (Blueprint $table) {
        $table->id(); // Pastikan ini ada dan tidak diubah
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('description');
        $table->string('thumbnail')->nullable();
        $table->decimal('price', 15, 2)->default(0);
        $table->boolean('is_free')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};