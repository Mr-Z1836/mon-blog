<?php

namespace App\Support;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class Seo
{
    public static function absoluteUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url($path);
    }

    public static function defaultOgImage(): string
    {
        $configured = config('blog.og_image');

        if (filled($configured)) {
            return self::absoluteUrl($configured) ?? url('/');
        }

        return url(asset('images/og-default.svg'));
    }

    public static function postOgImage(Post $post): string
    {
        if (filled($post->image_path)) {
            $url = Post::publicMediaUrl($post->image_path);

            return $url ?? url('/');
        }

        return self::defaultOgImage();
    }

    /**
     * @return array<string, mixed>
     */
    public static function articleJsonLd(Post $post, string $description): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->meta_title ?: $post->title,
            'description' => $description,
            'image' => [self::postOgImage($post)],
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author->name,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'url' => route('home'),
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('posts.show', $post),
            ],
            'articleSection' => $post->category->name,
            'inLanguage' => 'fr',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function websiteJsonLd(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'alternateName' => config('blog.tagline'),
            'url' => route('home'),
            'description' => config('blog.meta_description'),
            'inLanguage' => 'fr',
        ];
    }
}
