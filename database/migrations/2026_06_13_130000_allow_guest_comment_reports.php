<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comment_reports', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['comment_id', 'user_id']);
        });

        Schema::table('comment_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('reporter_ip', 45)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('comment_reports', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('reporter_ip');
        });

        Schema::table('comment_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['comment_id', 'user_id']);
        });
    }
};
