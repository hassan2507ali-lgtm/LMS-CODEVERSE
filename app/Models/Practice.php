<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Practice extends Model
{
    use HasFactory;

    // Pastikan semua kolom ini ada di $fillable
   // Pastikan semua kolom ini ada di $fillable
   protected $fillable = [
    'title',
    'slug',
    'description',
    'thumbnail',
    'github_link',
    'category',
    'tags',
    'content',
    'is_free',               // <-- TAMBAH INI
    'price',                 // <-- TAMBAH INI
    'free_exercises_count',  // <-- TAMBAH INI
];

// Pastikan tags di-cast ke array
protected $casts = [
    'tags' => 'array',
    'is_free' => 'boolean',  // <-- TAMBAH INI
    'price' => 'integer',    // <-- TAMBAH INI
];

    /**
     * Get the exercises for the practice
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(PracticeExercise::class)->orderBy('order', 'asc');
    }

    /**
     * Get the image URL for this practice project.
     * Priority: GitHub image > thumbnail > placeholder
     */
    public function getImageUrlAttribute()
    {
        // Try to get GitHub image first
        if ($this->github_link) {
            $githubImage = $this->getGithubImageUrl();
            if ($githubImage) {
                return $githubImage;
            }
        }

        // Fallback to thumbnail
        if ($this->thumbnail) {
            return $this->thumbnail;
        }

        // Default placeholder
        return 'https://placehold.co/600x400/8b5cf6/ffffff?text=' . urlencode($this->title);
    }

    /**
     * Extract and generate GitHub image URL from github_link
     * Supports multiple formats:
     * - https://github.com/username/repo
     * - https://github.com/username/repo/blob/main/image.png
     * - https://github.com/username/repo/blob/main/image.png?raw=true
     */
    private function getGithubImageUrl()
    {
        if (!$this->github_link) {
            return null;
        }

        $url = $this->github_link;

        // Remove ?raw=true parameter if present (we'll convert the URL properly)
        $url = preg_replace('/\?raw=true$/i', '', $url);

        // Check if it's a direct image link (ends with image extension)
        if (preg_match('/\.(png|jpg|jpeg|gif|webp)$/i', $url)) {
            // Convert GitHub blob URL to raw URL
            // From: https://github.com/user/repo/blob/main/path/image.png
            // To: https://raw.githubusercontent.com/user/repo/main/path/image.png
            $rawUrl = str_replace(
                ['github.com', '/blob/'],
                ['raw.githubusercontent.com', '/'],
                $url
            );
            return $rawUrl;
        }

        // Extract username and repo from GitHub URL
        if (preg_match('/github\.com\/([^\/]+)\/([^\/]+)/', $url, $matches)) {
            $username = $matches[1];
            $repo = str_replace('.git', '', $matches[2]);

            // Try common screenshot/preview file names
            $commonImages = [
                'preview.png',
                'screenshot.png',
                'demo.png',
                'thumbnail.png',
                'cover.png',
                'banner.png',
                'preview.jpg',
                'screenshot.jpg',
            ];

            // Return the first common image (you can enhance this to check if file exists)
            foreach ($commonImages as $image) {
                return "https://raw.githubusercontent.com/{$username}/{$repo}/main/{$image}";
            }

            // Fallback: Use GitHub's social preview (opengraph image)
            return "https://opengraph.githubassets.com/1/{$username}/{$repo}";
        }

        return null;
    }

    /**
     * Get GitHub repository info (username/repo)
     */
    public function getGithubRepoAttribute()
    {
        if (!$this->github_link) {
            return null;
        }

        if (preg_match('/github\.com\/([^\/]+)\/([^\/]+)/', $this->github_link, $matches)) {
            $repo = str_replace('.git', '', $matches[2]);
            return $matches[1] . '/' . $repo;
        }

        return null;
    }
}
