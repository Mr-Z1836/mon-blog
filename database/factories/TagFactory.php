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
        $name = $this->faker->unique()->randomElement(self::TAG_NAMES);

        return [
            'nom' => $name,
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numerify('##'),
        ];
    }
}
