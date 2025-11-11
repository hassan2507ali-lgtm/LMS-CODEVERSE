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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom 'is_admin'
            // boolean = tipe data (1 untuk true, 0 untuk false)
            // default(false) = semua pengguna baru otomatis BUKAN admin
            // after('email') = letakkan kolom ini setelah kolom email (opsional, agar rapi)
            $table->boolean('is_admin')->default(false)->after('email');
        });
    }
    // (Method down() bisa Anda isi dengan $table->dropColumn('is_admin');)

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
