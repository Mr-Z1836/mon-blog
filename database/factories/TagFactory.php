<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    private const TAG_NAMES = [
        'Backend',
        'Frontend',
        'API',
        'Eloquent',
        'Validation',
        'Tests',
        'Performance',
        'SEO',
        'Clean Code',
        'Refactoring',
        'Middleware',
        'Blade',
        'Migration',
        'UX',
        'Debug',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(self::TAG_NAMES);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
