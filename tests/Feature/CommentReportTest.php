<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_report_comment_with_custom_reason(): void
    {
        $reporter = User::factory()->create();
        $post = Post::factory()->create(['est_publie' => true, 'publie_le' => now()->subDay()]);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'est_approuve' => true,
        ]);

        $response = $this->actingAs($reporter)->post(
            route('posts.comments.report', [$post, $comment]),
            ['reason' => 'Ce commentaire contient des insultes gratuites.'],
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('comment_reports', [
            'comment_id' => $comment->id,
            'user_id' => $reporter->id,
            'motif' => 'Ce commentaire contient des insultes gratuites.',
            'statut' => 'en_attente',
        ]);
    }

    public function test_guest_can_report_comment_with_custom_reason(): void
    {
        $post = Post::factory()->create(['est_publie' => true, 'publie_le' => now()->subDay()]);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'est_approuve' => true,
        ]);

        $response = $this->post(
            route('posts.comments.report', [$post, $comment]),
            ['reason' => 'Spam évident avec des liens suspects dedans.'],
            ['REMOTE_ADDR' => '203.0.113.10'],
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('comment_reports', [
            'comment_id' => $comment->id,
            'user_id' => null,
            'reporter_ip' => '203.0.113.10',
            'motif' => 'Spam évident avec des liens suspects dedans.',
        ]);
    }

    public function test_user_cannot_report_same_comment_twice(): void
    {
        $reporter = User::factory()->create();
        $post = Post::factory()->create(['est_publie' => true, 'publie_le' => now()->subDay()]);
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        CommentReport::create([
            'comment_id' => $comment->id,
            'user_id' => $reporter->id,
            'motif' => 'Premier signalement déjà envoyé.',
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($reporter)->post(
            route('posts.comments.report', [$post, $comment]),
            ['reason' => 'Deuxième tentative de signalement inutile.'],
        );

        $response->assertRedirect();
        $this->assertSame(1, CommentReport::where('comment_id', $comment->id)->count());
    }
}
