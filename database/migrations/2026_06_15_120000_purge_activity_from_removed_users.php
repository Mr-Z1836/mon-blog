<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        $preservedIds = DB::table('users')
            ->whereIn('email', $preservedEmails)
            ->pluck('id');

        if ($preservedIds->isEmpty()) {
            return;
        }

        $this->purgeActivityNotFromPreservedUsers($preservedIds);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, int>  $preservedIds
     */
    private function purgeActivityNotFromPreservedUsers($preservedIds): void
    {
        DB::table('comments')->whereNotIn('user_id', $preservedIds)->delete();

        if (Schema::hasTable('ratings')) {
            DB::table('ratings')->whereNotIn('user_id', $preservedIds)->delete();
        }

        DB::table('post_views')
            ->whereNotNull('user_id')
            ->whereNotIn('user_id', $preservedIds)
            ->delete();

        if (Schema::hasTable('comment_reports')) {
            DB::table('comment_reports')
                ->whereNotNull('user_id')
                ->whereNotIn('user_id', $preservedIds)
                ->delete();
        }

        // Commentaires dont l'auteur a déjà été supprimé (orphelins)
        DB::table('comments')
            ->whereNotExists(function ($query): void {
                $query->select(DB::raw(1))
                    ->from('users')
                    ->whereColumn('users.id', 'comments.user_id');
            })
            ->delete();
    }

    public function down(): void
    {
        // Données de comptes supprimés : pas de restauration.
    }
};
