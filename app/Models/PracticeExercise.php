<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PracticeExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'practice_id',
        'title',
        'description',
        'instructions',
        'starter_code',
        'solution_code',
        'hints',
        'order',
        'difficulty',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    /**
     * Get the practice that owns the exercise
     */
    public function practice(): BelongsTo
    {
        return $this->belongsTo(Practice::class);
    }
}
