<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comment_reports', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['comment_id', 'user_id']);
        });

        DB::statement('ALTER TABLE comment_reports MODIFY user_id BIGINT UNSIGNED NULL');

        Schema::table('comment_reports', function (Blueprint $table) {
            $table->string('reporter_ip', 45)->nullable()->after('user_id');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('comment_reports', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('reporter_ip');
        });

        DB::statement('ALTER TABLE comment_reports MODIFY user_id BIGINT UNSIGNED NOT NULL');

        Schema::table('comment_reports', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['comment_id', 'user_id']);
        });
    }
};
