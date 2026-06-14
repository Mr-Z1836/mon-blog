<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\Rating;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'harrydedji@gmail.com')->firstOrFail();
        $reader = User::query()->where('email', 'test@example.com')->firstOrFail();

        $categories = $this->seedCategories();
        $tags = $this->seedTags();
        $this->seedArticles($categories, $tags, $admin, $reader);
        $this->seedNewsletterSubscribers();
    }

    /**
     * @return array<string, Category>
     */
    private function seedCategories(): array
    {
        $definitions = [
            'dev-code' => [
                'name' => 'Dev & Code',
                'description' => 'Tutoriels, stacks et bonnes pratiques pour coder depuis le Bénin et l\'Afrique.',
            ],
            'entrepreneuriat' => [
                'name' => 'Entrepreneuriat',
                'description' => 'Mindset, idées, lancement et croissance de projets tech sur le continent.',
            ],
            'crypto-finance' => [
                'name' => 'Crypto & Finance',
                'description' => 'Mobile money, fintech, crypto et gestion d\'argent pour les jeunes Africains.',
            ],
            'vie-etudiant' => [
                'name' => 'Vie d\'étudiant tech',
                'description' => 'Parcours, organisation, stages et premiers pas dans la tech.',
            ],
            'opportunites' => [
                'name' => 'Opportunités',
                'description' => 'Bourses, concours, incubateurs et programmes pour la jeunesse africaine.',
            ],
            'cybersecurite' => [
                'name' => 'Cybersécurité',
                'description' => 'CTF, outils de sécurité, writeups, tips pour débuter en cybersécurité',
            ],
        ];

        $categories = [];

        foreach ($definitions as $slug => $data) {
            $categories[$slug] = Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'nom' => $data['name'],
                    'description' => $data['description'],
                ]
            );
        }

        return $categories;
    }

    /**
     * @return array<string, Tag>
     */
    private function seedTags(): array
    {
        $names = [
            'Bénin', 'Afrique', 'Laravel', 'Startup', 'Fintech', 'Mobile Money',
            'Études', 'Bourse', 'Railway', 'PHP', 'JavaScript', 'Remote',
            'Cotonou', 'Jeunesse', 'Open Source',
        ];

        $tags = [];

        foreach ($names as $name) {
            $slug = Str::slug($name);
            $tags[$slug] = Tag::create(['nom' => $name, 'slug' => $slug]);
        }

        return $tags;
    }

    /**
     * @param  array<string, Category>  $categories
     * @param  array<string, Tag>  $tags
     */
    private function seedArticles(array $categories, array $tags, User $admin, User $reader): void
    {
        $articles = BlogArticleLibrary::definitions();

        foreach ($articles as $index => $article) {
            $category = $categories[$article['category']];

            $cta = $article['cta'] ?? $this->defaultCta($article['category']);

            $post = Post::create([
                'user_id' => $admin->id,
                'category_id' => $category->id,
                'post_series_id' => null,
                'series_part' => null,
                'titre' => $article['title'],
                'slug' => $article['slug'],
                'resume' => $article['excerpt'],
                'meta_title' => $article['title'].' — Built in Benin',
                'meta_description' => $article['excerpt'],
                'cta_title' => $cta['title'],
                'cta_text' => $cta['text'],
                'cta_url' => $cta['url'],
                'image_path' => $article['image_path'] ?? 'posts/images/default-cover.svg',
                'contenu' => $article['content'],
                'youtube_url' => $article['youtube_url'] ?? null,
                'est_publie' => true,
                'est_epingle' => $article['is_pinned'] ?? false,
                'est_a_la_une' => $article['is_featured'] ?? false,
                'publie_le' => now()->subDays(20 - $index),
            ]);

            $tagIds = collect($article['tags'])
                ->map(fn (string $slug) => $tags[$slug]->id ?? null)
                ->filter()
                ->values()
                ->all();

            $post->tags()->sync($tagIds);
            $this->seedInteractions($post, $admin, $reader);
        }
    }

    /**
     * @return array{title: string, text: string, url: string}
     */
    private function defaultCta(string $categorySlug): array
    {
        return match ($categorySlug) {
            'dev-code' => [
                'title' => 'Continue sur Dev & Code',
                'text' => 'Voir les tutos',
                'url' => route('posts.index', ['category' => 'dev-code']),
            ],
            'entrepreneuriat' => [
                'title' => 'Tu lances un projet ?',
                'text' => 'Écris à Starboy',
                'url' => route('contact'),
            ],
            'crypto-finance' => [
                'title' => 'Approfondis la finance digitale',
                'text' => 'Lire Crypto & Finance',
                'url' => route('posts.index', ['category' => 'crypto-finance']),
            ],
            'vie-etudiant' => [
                'title' => 'Besoin de conseils perso ?',
                'text' => 'Me contacter',
                'url' => route('contact'),
            ],
            'opportunites' => [
                'title' => 'Ne rate pas la prochaine opportunité',
                'text' => 'Voir les opportunités',
                'url' => route('posts.index', ['category' => 'opportunites']),
            ],
            'cybersecurite' => [
                'title' => 'Plonge dans la cybersécurité',
                'text' => 'Voir Cybersécurité',
                'url' => route('posts.index', ['category' => 'cybersecurite']),
            ],
            default => [
                'title' => 'Découvre Built in Benin',
                'text' => 'Tous les articles',
                'url' => route('posts.index'),
            ],
        };
    }

    private function seedNewsletterSubscribers(): void
    {
        $emails = [
            ['email' => 'amina.newsletter@example.com', 'source' => 'sidebar'],
            ['email' => 'dev.benin@example.com', 'source' => 'article'],
            ['email' => 'startup.cotonou@example.com', 'source' => 'home'],
            ['email' => 'lecteur.afrique@example.com', 'source' => 'sidebar'],
            ['email' => 'tech.youth@example.com', 'source' => 'article'],
        ];

        foreach ($emails as $entry) {
            NewsletterSubscriber::create([
                'email' => $entry['email'],
                'source' => $entry['source'],
                'abonne_le' => now()->subDays(rand(1, 30)),
            ]);
        }
    }

    private function seedInteractions(Post $post, User $admin, User $reader): void
    {
        $randomUsers = User::inRandomOrder()->limit(rand(2, 5))->get();

        foreach ($randomUsers as $user) {
            Rating::factory()->create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
        }

        $rootComment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $reader->id,
            'contenu' => 'Super article, très utile pour le contexte africain !',
            'est_approuve' => true,
        ]);

        Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $admin->id,
            'parent_id' => $rootComment->id,
            'mentioned_user_id' => $reader->id,
            'contenu' => '@'.$reader->username.' merci ! N\'hésite pas à partager ton retour d\'expérience.',
            'est_approuve' => true,
        ]);
    }
}
