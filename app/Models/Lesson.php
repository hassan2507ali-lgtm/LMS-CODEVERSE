<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Tambahkan ini

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id', 'title', 'content_type', 'content', 'order',
    ];

    /**
     * Mendefinisikan relasi inverse one-to-many ke Model Module.
     * (Satu Lesson dimiliki oleh satu Module)
     * Nama method ini (module) akan digunakan untuk mengakses relasi: $lesson->module
     */
    public function module(): BelongsTo
    {
        // Laravel otomatis mencari foreign key 'module_id' di tabel ini ('lessons')
        return $this->belongsTo(Module::class);
    }
}