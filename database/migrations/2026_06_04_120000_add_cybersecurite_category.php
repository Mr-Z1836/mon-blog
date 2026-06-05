<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Category::firstOrCreate(
            ['slug' => 'cybersecurite'],
            [
                'name' => 'Cybersécurité',
                'description' => 'CTF, outils de sécurité, writeups, tips pour débuter en cybersécurité',
            ]
        );
    }

    public function down(): void
    {
        Category::query()->where('slug', 'cybersecurite')->delete();
    }
};
