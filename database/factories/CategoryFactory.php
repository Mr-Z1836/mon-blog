<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    private const CATEGORY_NAMES = [
        'Développement Web',
        'Laravel',
        'PHP',
        'JavaScript',
        'Productivité',
        'Outils',
        'Base de données',
        'Design',
        'Sécurité',
        'DevOps',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(self::CATEGORY_NAMES);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->randomElement([
                'Tutoriels et retours d\'expérience pour progresser rapidement.',
                'Conseils pratiques pour améliorer la qualité du code.',
                'Articles accessibles pour mieux comprendre les bases et les bonnes pratiques.',
            ]),
        ];
    }
}
