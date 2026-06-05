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
    private const CATEGORIES = [
        ['name' => 'Dev & Code', 'slug' => 'dev-code'],
        ['name' => 'Entrepreneuriat', 'slug' => 'entrepreneuriat'],
        ['name' => 'Crypto & Finance', 'slug' => 'crypto-finance'],
        ['name' => 'Vie d\'étudiant tech', 'slug' => 'vie-etudiant'],
        ['name' => 'Opportunités', 'slug' => 'opportunites'],
    ];

    public function definition(): array
    {
        $category = fake()->randomElement(self::CATEGORIES);

        return [
            'name' => $category['name'],
            'slug' => $category['slug'].'-'.fake()->unique()->numerify('##'),
            'description' => 'Contenus autour de la tech et de l\'entrepreneuriat africain.',
        ];
    }
}
