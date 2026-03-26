<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Tambahkan practice_id di sini
    protected $fillable = [
        'user_id', 'course_id', 'practice_id', 'reference_number', 
        'amount', 'status', 'payment_url', 'snap_token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Tambahkan relasi ke Practice
    public function practice()
    {
        return $this->belongsTo(Practice::class);
    }
}