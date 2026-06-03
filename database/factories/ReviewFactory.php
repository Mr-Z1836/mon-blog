<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reviews = [
            'Article utile et bien structuré. Les exemples sont concrets et faciles à reproduire.',
            'Très bon niveau de détail, surtout sur la partie architecture et organisation du code.',
            'Explications claires du début à la fin. On comprend rapidement les décisions techniques.',
            'Contenu pertinent pour un blog technique. La lecture est fluide et agréable.',
            'J ai appliqué ces conseils sur mon projet et j ai gagné du temps immédiatement.',
            'Bonne synthèse entre théorie et pratique. C est exactement ce qu il me fallait.',
        ];

        return [
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'content' => fake()->randomElement($reviews),
            'is_approved' => fake()->boolean(70),
        ];
    }
}
