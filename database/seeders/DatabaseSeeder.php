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
        User::query()->updateOrCreate(
            ['email' => 'harrydedji@gmail.com'],
            [
                'name' => 'Harry DEDJI',
                'username' => 'starboy',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'est_administrateur' => true,
            ]
        );

        $this->call(BlogContentSeeder::class);
    }
}
