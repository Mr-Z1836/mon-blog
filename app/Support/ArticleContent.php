<?php

namespace App\Support;

class ArticleContent
{
    /**
     * @return array<int, array{id: string, text: string, level: int}>
     */
    public static function tableOfContents(string $content): array
    {
        $headings = [];

        foreach (preg_split('/\r\n|\r|\n/', $content) ?: [] as $line) {
            if (preg_match('/^(#{2,3})\s+(.+)$/', trim($line), $matches) !== 1) {
                continue;
            }

            $text = trim($matches[2]);

            $headings[] = [
                'id' => self::headingId($text),
                'text' => $text,
                'level' => strlen($matches[1]),
            ];
        }

        return $headings;
    }

    public static function renderHtml(string $content, ?string $contentHtml = null): string
    {
        if ($contentHtml !== null && trim($contentHtml) !== '') {
            return $contentHtml;
        }

        $parts = preg_split('/```/', $content) ?: [$content];
        $html = '';
        $inCode = false;

        foreach ($parts as $index => $part) {
            if ($index % 2 === 1) {
                $lines = explode("\n", trim($part), 2);
                $language = trim($lines[0] ?? 'plaintext');
                $code = e(trim($lines[1] ?? ''));
                $html .= '<pre class="rounded-xl bg-slate-900 p-4 overflow-x-auto"><code class="language-'.e($language).'">'.$code.'</code></pre>';
                $inCode = false;

                continue;
            }

            foreach (preg_split('/\r\n|\r|\n/', $part) ?: [] as $line) {
                $trimmed = trim($line);

                if ($trimmed === '') {
                    continue;
                }

                if (preg_match('/^(#{2,3})\s+(.+)$/', $trimmed, $matches) === 1) {
                    $tag = strlen($matches[1]) === 2 ? 'h2' : 'h3';
                    $text = e($matches[2]);
                    $id = self::headingId($matches[2]);
                    $html .= "<{$tag} id=\"{$id}\" class=\"article-heading mt-6 mb-2 font-bold text-slate-900 dark:text-slate-100\">{$text}</{$tag}>";

                    continue;
                }

                $html .= '<p class="mb-3 text-slate-700 dark:text-slate-300">'.e($trimmed).'</p>';
            }
        }

        return $html !== '' ? $html : '<p class="text-slate-700 dark:text-slate-300">'.nl2br(e($content)).'</p>';
    }

    public static function extractYoutubeId(?string $url): ?string
    {
        if ($url === null || $url === '') {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([A-Za-z0-9_-]{11})/', $url, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    private static function headingId(string $text): string
    {
        return 'section-'.substr(md5($text), 0, 8);
    }
}
