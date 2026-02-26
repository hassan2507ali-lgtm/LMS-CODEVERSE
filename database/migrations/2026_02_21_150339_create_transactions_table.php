<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            
            // Menyimpan nomor referensi unik (misal: TRX-123456)
            $table->string('reference_number')->unique(); 
            
            // Total harga yang harus dibayar
            $table->decimal('amount', 15, 2); 
            
            // Status: pending, success, failed, expired
            $table->string('status')->default('pending'); 
            
            // URL dari Midtrans/Xendit untuk halaman pembayaran
            $table->string('payment_url')->nullable(); 
            
            // Token dari payment gateway (khusus Midtrans)
            $table->string('snap_token')->nullable(); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};