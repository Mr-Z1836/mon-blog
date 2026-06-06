<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Collection;

class CommentMentions
{
    /**
     * @return Collection<int, User>
     */
    public static function resolveFromContent(string $content): Collection
    {
        if (! preg_match_all('/@([a-zA-Z0-9_]{2,50})/', $content, $matches)) {
            return collect();
        }

        $usernames = collect($matches[1])
            ->map(fn (string $username) => strtolower($username))
            ->unique()
            ->values();

        if ($usernames->isEmpty()) {
            return collect();
        }

        return User::query()
            ->whereNotNull('username')
            ->where(function ($query) use ($usernames): void {
                foreach ($usernames as $username) {
                    $query->orWhereRaw('LOWER(username) = ?', [$username]);
                }
            })
            ->get()
            ->unique('id')
            ->values();
    }

    public static function formatContent(string $content): string
    {
        $escaped = e($content);

        return (string) preg_replace(
            '/@([a-zA-Z0-9_]{2,50})/',
            '<span class="font-medium text-brand-red">@$1</span>',
            $escaped,
        );
    }
}
