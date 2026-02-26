<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi
    protected $guarded = [];

    // Relasi balik ke Practice (Topik Latihan)
    public function practice()
    {
        return $this->belongsTo(Practice::class);
    }
}