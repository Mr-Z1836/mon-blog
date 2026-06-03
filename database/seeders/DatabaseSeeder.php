<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostBookmark;
use App\Models\PostReaction;
use App\Models\PostSeries;
use App\Models\PostView;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Blog',
            'username' => 'admin',
            'email' => 'admin@blog.test',
            'is_admin' => true,
        ]);

        $reader = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);

        User::factory(10)->create();

        $categories = Category::factory(6)->create();
        $tags = Tag::factory(12)->create();

        $laravelSeries = PostSeries::create([
            'title' => 'Série Laravel pas à pas',
            'slug' => 'laravel-pas-a-pas',
            'description' => 'Tutoriels progressifs pour construire un blog complet.',
        ]);

        $posts = Post::factory(20)->create([
            'user_id' => $admin->id,
        ]);

        $posts->take(3)->each(function (Post $post, int $index) use ($laravelSeries, $categories, $tags, $admin, $reader): void {
            $post->update([
                'category_id' => $categories->random()->id,
                'post_series_id' => $laravelSeries->id,
                'series_part' => $index + 1,
                'meta_title' => $post->title,
                'meta_description' => $post->excerpt,
            ]);
            $post->tags()->sync($tags->random(rand(2, 4))->pluck('id'));

            $this->seedInteractions($post, $admin, $reader);
        });

        $posts->skip(3)->each(function (Post $post) use ($categories, $tags, $admin, $reader): void {
            $post->update([
                'category_id' => $categories->random()->id,
            ]);
            $post->tags()->sync($tags->random(rand(2, 4))->pluck('id'));
            $this->seedInteractions($post, $admin, $reader);
        });
    }

    private function seedInteractions(Post $post, User $admin, User $reader): void
    {
        $randomUsers = User::inRandomOrder()->limit(rand(3, 7))->get();

        foreach ($randomUsers as $user) {
            Rating::factory()->create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);

            Review::factory()->create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);

            PostReaction::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
                'type' => fake()->randomElement(['fire', 'idea', 'clap', 'heart']),
            ]);
        }

        $rootComment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $reader->id,
            'is_approved' => true,
        ]);

        Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $admin->id,
            'parent_id' => $rootComment->id,
            'mentioned_user_id' => $reader->id,
            'content' => '@'.$reader->username.' merci pour ton retour, très utile !',
            'is_approved' => true,
        ]);

        for ($i = 0; $i < rand(2, 5); $i++) {
            Comment::factory()->create([
                'post_id' => $post->id,
                'user_id' => User::inRandomOrder()->value('id'),
            ]);
        }

        PostView::factory(rand(20, 80))->create([
            'post_id' => $post->id,
            'country_code' => fake()->randomElement(['fr', 'be', 'ch', 'ca', null]),
            'duration_seconds' => rand(30, 600),
        ]);

        PostBookmark::create([
            'post_id' => $post->id,
            'user_id' => $reader->id,
        ]);
    }
}
