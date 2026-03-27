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
        Schema::table('practice_exercises', function (Blueprint $table) {
            // Tambahkan kolom section_name (Nama Modul)
            $table->string('section_name')->nullable()->after('practice_id');
        });
    }

    public function down(): void
    {
        Schema::table('practice_exercises', function (Blueprint $table) {
            $table->dropColumn('section_name');
        });
    }
};
