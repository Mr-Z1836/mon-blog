<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $preservedEmails = collect(config('blog.preserved_user_emails', ['harrydedji@gmail.com']))
            ->map(fn (string $email) => strtolower(trim($email)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        foreach (['%@example.com', '%@example.org', '%@example.net'] as $pattern) {
            DB::table('newsletter_subscribers')->where('email', 'like', $pattern)->delete();
        }

        DB::table('users')
            ->whereNotIn('email', $preservedEmails)
            ->delete();
    }

    public function down(): void
    {
        // Comptes et abonnés de démo supprimés : pas de restauration.
    }
};
