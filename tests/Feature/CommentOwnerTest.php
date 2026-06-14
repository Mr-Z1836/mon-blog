<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentOwnerTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_their_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['est_publie' => true, 'publie_le' => now()->subDay()]);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'contenu' => 'Ancien texte',
            'est_approuve' => true,
        ]);

        $response = $this->actingAs($user)->patch(
            route('posts.comments.update', [$post, $comment]),
            ['comment_content' => 'Texte mis à jour par l\'auteur'],
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'contenu' => 'Texte mis à jour par l\'auteur',
        ]);
    }

    public function test_owner_can_delete_their_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['est_publie' => true, 'publie_le' => now()->subDay()]);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(
            route('posts.comments.destroy', [$post, $comment]),
        );

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_user_cannot_update_another_users_comment(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post = Post::factory()->create(['est_publie' => true, 'publie_le' => now()->subDay()]);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $owner->id,
            'contenu' => 'Commentaire protégé',
        ]);

        $response = $this->actingAs($other)->patch(
            route('posts.comments.update', [$post, $comment]),
            ['comment_content' => 'Tentative de modification'],
        );

        $response->assertForbidden();
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'contenu' => 'Commentaire protégé',
        ]);
    }
}
