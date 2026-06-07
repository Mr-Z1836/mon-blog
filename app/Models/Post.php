<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'user_id', 'category_id', 'post_series_id', 'series_part', 'title', 'slug', 'excerpt',
    'meta_title', 'meta_description', 'cta_title', 'cta_text', 'cta_url', 'image_path', 'video_path',
    'youtube_url', 'content', 'content_html', 'is_published', 'is_pinned', 'is_featured', 'published_at',
    'autosaved_at',
])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_pinned' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'autosaved_at' => 'datetime',
        ];
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(PostSeries::class, 'post_series_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(PostReaction::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(PostBookmark::class);
    }

    public function readHistories(): HasMany
    {
        return $this->hasMany(PostReadHistory::class);
    }

    public function readingTimeMinutes(): int
    {
        $wordCount = str_word_count(strip_tags((string) $this->content));

        return max(1, (int) ceil($wordCount / 200));
    }

    public function hasCta(): bool
    {
        return filled($this->cta_text) && filled($this->cta_url);
    }

    public function imageUrl(): ?string
    {
        return self::publicMediaUrl($this->image_path);
    }

    public function videoUrl(): ?string
    {
        return self::publicMediaUrl($this->video_path);
    }

    public static function publicMediaUrl(?string $path): ?string
    {
        if (blank($path) || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return asset('storage/'.$path);
    }
}
