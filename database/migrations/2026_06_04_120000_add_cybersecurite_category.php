<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('categories')->where('slug', 'cybersecurite')->exists()) {
            return;
        }

        DB::table('categories')->insert([
            'slug' => 'cybersecurite',
            'name' => 'Cybersécurité',
            'description' => 'CTF, outils de sécurité, writeups, tips pour débuter en cybersécurité',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('categories')->where('slug', 'cybersecurite')->delete();
    }
};
