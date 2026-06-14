<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('post_views')->delete();
    }

    public function down(): void
    {
        // Les vues supprimées étaient fictives ou historiques : pas de restauration.
    }
};
