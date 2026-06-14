<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    private const TITLES = [
        'Déployer Laravel sur Railway depuis le Bénin',
        'Valider une idée de startup à Cotonou',
        'Mobile Money : comprendre la fintech locale',
        'Bourses tech pour jeunes Africains',
        'Créer un portfolio développeur depuis l\'Afrique',
        'Gérer son budget en tant qu\'étudiant tech',
    ];

    public function definition(): array
    {
        $title = $this->faker->randomElement(self::TITLES);

        $content = "## Introduction\n\n".$this->faker->paragraph()."\n\n## Mise en pratique\n\n".$this->faker->paragraph()."\n\n## Conclusion\n\n".$this->faker->paragraph();

        return [
            'user_id' => User::factory(),
            'category_id' => Category::query()->inRandomOrder()->value('id') ?? Category::factory(),
            'titre' => $title,
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numerify('###'),
            'resume' => $this->faker->sentence(12),
            'image_path' => 'posts/images/default-cover.svg',
            'video_path' => null,
            'contenu' => $content,
            'est_publie' => true,
            'publie_le' => $this->faker->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
