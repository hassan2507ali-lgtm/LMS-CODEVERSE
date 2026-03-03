<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id', 'title', 'content_type', 'content', 'order',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    // --- FITUR BARU: Auto-Convert Link YouTube ke Embed ---
    public function getEmbedUrlAttribute()
    {
        // Pastikan ini adalah tipe video dan kontennya tidak kosong
        if ($this->content_type !== 'video' || empty($this->content)) {
            return null;
        }

        $url = $this->content; 

        // Rumus (Regex) untuk mengambil ID Video dari URL biasa atau URL share HP
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $match);
        
        $videoId = $match[1] ?? null;

        if ($videoId) {
            return "https://www.youtube.com/embed/{$videoId}";
        }

        // Kalau ternyata bukan link YouTube (misal Vimeo), kembalikan aslinya
        return $url; 
    }
}