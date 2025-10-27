<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', // Foreign key
        'title',
        'order',
    ];

    // (Nanti kita tambahkan relasi ke Course dan Lesson di sini)
}