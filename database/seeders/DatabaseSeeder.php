<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Starboy',
            'username' => 'starboy',
            'email' => 'harrydedji@gmail.com',
            'est_administrateur' => true,
        ]);

        $reader = User::factory()->create([
            'name' => 'Amina K.',
            'username' => 'amina_k',
            'email' => 'test@example.com',
            'est_administrateur' => false,
        ]);

        User::factory(8)->create();

        $this->call(BlogContentSeeder::class);
    }
}
