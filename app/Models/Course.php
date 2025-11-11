<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    /**
     * Kolom yang diizinkan untuk diisi secara massal (mass assignable).
     * PASTIKAN SEMUA KOLOM FORM ADA DI SINI.
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',         // <-- Pastikan 'slug' ada di sini
        'description',
        'thumbnail',
        'price',
        'is_free',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke Model Module.
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('order', 'asc');
    }
}