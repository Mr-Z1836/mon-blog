<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('post_series_id')->nullable()->after('category_id')->constrained('post_series')->nullOnDelete();
            $table->unsignedSmallInteger('series_part')->nullable()->after('post_series_id');
            $table->string('meta_title')->nullable()->after('excerpt');
            $table->string('meta_description', 500)->nullable()->after('meta_title');
            $table->string('youtube_url')->nullable()->after('video_path');
            $table->longText('content_html')->nullable()->after('content');
            $table->boolean('is_pinned')->default(false)->after('is_published');
            $table->boolean('is_featured')->default(false)->after('is_pinned');
            $table->timestamp('autosaved_at')->nullable()->after('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('post_series_id');
            $table->dropColumn([
                'series_part',
                'meta_title',
                'meta_description',
                'youtube_url',
                'content_html',
                'is_pinned',
                'is_featured',
                'autosaved_at',
            ]);
        });
    }
};
