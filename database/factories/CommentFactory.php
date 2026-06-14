<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sentences = [
            'Merci pour cet article, il est très clair.',
            'Je viens de tester cette approche et ça fonctionne très bien.',
            'Peux tu faire un article complémentaire sur les tests ?',
            'Le passage sur la validation m a beaucoup aidé.',
            'Super explication, surtout pour la partie admin.',
            'J apprécie le côté simple et pragmatique.',
            'C est exactement ce que je cherchais.',
            'Très bon contenu, continue comme ça.',
        ];

        return [
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'contenu' => $this->faker->randomElement($sentences),
            'est_approuve' => $this->faker->boolean(70),
        ];
    }
}
