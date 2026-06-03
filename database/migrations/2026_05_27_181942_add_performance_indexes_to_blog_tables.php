<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->index(['is_published', 'published_at'], 'posts_published_at_index');
            $table->index(['category_id', 'published_at'], 'posts_category_published_at_index');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index(['post_id', 'is_approved'], 'comments_post_approved_index');
            $table->index(['is_approved', 'created_at'], 'comments_approved_created_at_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['post_id', 'is_approved'], 'reviews_post_approved_index');
            $table->index(['is_approved', 'created_at'], 'reviews_approved_created_at_index');
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->index('post_id', 'ratings_post_id_index');
        });

        Schema::table('post_views', function (Blueprint $table) {
            $table->index('post_id', 'post_views_post_id_index');
            $table->index(['post_id', 'created_at'], 'post_views_post_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_views', function (Blueprint $table) {
            $table->dropIndex('post_views_post_id_index');
            $table->dropIndex('post_views_post_created_at_index');
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->dropIndex('ratings_post_id_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_post_approved_index');
            $table->dropIndex('reviews_approved_created_at_index');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_post_approved_index');
            $table->dropIndex('comments_approved_created_at_index');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_published_at_index');
            $table->dropIndex('posts_category_published_at_index');
        });
    }
};
