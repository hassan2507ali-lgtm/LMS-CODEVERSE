<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Tambahkan ini

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'thumbnail', 'price', 'is_free',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke Model Module.
     * Satu Course memiliki banyak Module.
     */
    public function modules(): HasMany // <-- Method relasi
    {
        // Laravel akan otomatis mencari foreign key 'course_id' di tabel 'modules'
        return $this->hasMany(Module::class);
    }
}