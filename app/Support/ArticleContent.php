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

        foreach ($parts as $index => $part) {
            if ($index % 2 === 1) {
                $lines = explode("\n", trim($part), 2);
                $language = trim($lines[0] ?? 'plaintext');
                $code = e(trim($lines[1] ?? ''));
                $html .= '<pre class="rounded-xl bg-slate-900 p-4 overflow-x-auto"><code class="language-'.e($language).'">'.$code.'</code></pre>';

                continue;
            }

            $html .= self::renderLines(preg_split('/\r\n|\r|\n/', $part) ?: []);
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

    /**
     * @param  list<string>  $lines
     */
    private static function renderLines(array $lines): string
    {
        $html = '';
        $count = count($lines);
        $index = 0;

        while ($index < $count) {
            $trimmed = trim($lines[$index]);

            if ($trimmed === '') {
                $index++;

                continue;
            }

            if (preg_match('/^(#{2,3})\s+(.+)$/', $trimmed, $matches) === 1) {
                $tag = strlen($matches[1]) === 2 ? 'h2' : 'h3';
                $text = e($matches[2]);
                $id = self::headingId($matches[2]);
                $html .= "<{$tag} id=\"{$id}\" class=\"article-heading mt-6 mb-2 font-bold text-slate-900 dark:text-slate-100\">{$text}</{$tag}>";
                $index++;

                continue;
            }

            if (preg_match('/^[-*]\s+(.+)$/', $trimmed, $matches) === 1) {
                $items = [];

                while ($index < $count) {
                    $line = trim($lines[$index]);

                    if (preg_match('/^[-*]\s+(.+)$/', $line, $itemMatches) !== 1) {
                        break;
                    }

                    $items[] = $itemMatches[1];
                    $index++;
                }

                $html .= self::renderList('ul', $items);

                continue;
            }

            if (preg_match('/^\d+\.\s+(.+)$/', $trimmed, $matches) === 1) {
                $items = [];

                while ($index < $count) {
                    $line = trim($lines[$index]);

                    if (preg_match('/^\d+\.\s+(.+)$/', $line, $itemMatches) !== 1) {
                        break;
                    }

                    $items[] = $itemMatches[1];
                    $index++;
                }

                $html .= self::renderList('ol', $items);

                continue;
            }

            $html .= '<p class="mb-3 text-slate-700 dark:text-slate-300">'.self::inlineHtml($trimmed).'</p>';
            $index++;
        }

        return $html;
    }

    /**
     * @param  list<string>  $items
     */
    private static function renderList(string $tag, array $items): string
    {
        $listClass = $tag === 'ol'
            ? 'mb-4 list-decimal space-y-2 pl-6 text-slate-700 dark:text-slate-300'
            : 'mb-4 list-disc space-y-2 pl-6 text-slate-700 dark:text-slate-300';

        $html = "<{$tag} class=\"{$listClass}\">";

        foreach ($items as $item) {
            $html .= '<li>'.self::inlineHtml($item).'</li>';
        }

        return $html."</{$tag}>";
    }

    private static function headingId(string $text): string
    {
        return 'section-'.substr(md5($text), 0, 8);
    }

    private static function inlineHtml(string $text): string
    {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text) ?? $text;
        $text = preg_replace_callback(
            '/\[([^\]]+)\]\(([^)]+)\)/',
            static function (array $matches): string {
                $url = $matches[2];
                if (! str_starts_with($url, '/') && ! str_starts_with($url, 'https://')) {
                    return $matches[0];
                }

                return '<a href="'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').'" class="text-brand-red underline">'.$matches[1].'</a>';
            },
            $text
        ) ?? $text;

        return $text;
    }
}
