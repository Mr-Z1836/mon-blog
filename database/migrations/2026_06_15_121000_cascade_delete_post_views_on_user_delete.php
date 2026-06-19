<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('post_views')) {
            return;
        }

        Schema::table('post_views', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
        });

        Schema::table('post_views', function (Blueprint $table): void {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('post_views')) {
            return;
        }

        Schema::table('post_views', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
        });

        Schema::table('post_views', function (Blueprint $table): void {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }
};
