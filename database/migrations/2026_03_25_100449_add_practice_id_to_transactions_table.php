<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Tambahkan practice_id, boleh kosong (nullable) karena user mungkin aja cuma beli Course
            $table->foreignId('practice_id')->nullable()->constrained('practices')->nullOnDelete();
            
            // Ubah course_id jadi nullable juga (kalau sebelumnya wajib),
            // karena kalau user beli Practice, course_id-nya pasti kosong.
            $table->unsignedBigInteger('course_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['practice_id']);
            $table->dropColumn('practice_id');
            // course_id biarkan saja nullable atau kembalikan ke aturan awalmu
        });
    }
};