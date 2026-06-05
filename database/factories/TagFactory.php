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
        'Bénin', 'Afrique', 'Laravel', 'Startup', 'Fintech', 'Mobile Money',
        'Études', 'Bourse', 'Cotonou', 'Jeunesse', 'Remote', 'Open Source',
    ];

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(self::TAG_NAMES);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('##'),
        ];
    }
}
