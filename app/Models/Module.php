<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Tambahkan ini
use Illuminate\Database\Eloquent\Relations\HasMany;   // <-- Tambahkan ini

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'title', 'order',
    ];

    /**
     * Mendefinisikan relasi inverse one-to-many ke Model Course.
     * (Satu Module dimiliki oleh satu Course)
     * Nama method ini (course) akan digunakan untuk mengakses relasi: $module->course
     */
    public function course(): BelongsTo
    {
        // Laravel otomatis mencari foreign key 'course_id' di tabel ini ('modules')
        return $this->belongsTo(Course::class);
    }

    /**
     * Mendefinisikan relasi one-to-many ke Model Lesson.
     * (Satu Module memiliki banyak Lesson)
     * Nama method ini (lessons) akan digunakan untuk mengakses relasi: $module->lessons
     */
    public function lessons(): HasMany
    {
        // Laravel otomatis mencari foreign key 'module_id' di tabel 'lessons'
        // Kita tambahkan orderBy agar lesson selalu terurut
        return $this->hasMany(Lesson::class)->orderBy('order', 'asc');
    }
}