<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('post_reactions');
        Schema::dropIfExists('post_read_histories');
        Schema::dropIfExists('post_bookmarks');
        Schema::dropIfExists('media');

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('is_admin', 'est_administrateur');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('is_approved', 'est_approuve');
            $table->renameColumn('content', 'contenu');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('is_published', 'est_publie');
            $table->renameColumn('is_pinned', 'est_epingle');
            $table->renameColumn('is_featured', 'est_a_la_une');
            $table->renameColumn('published_at', 'publie_le');
            $table->renameColumn('title', 'titre');
            $table->renameColumn('excerpt', 'resume');
            $table->renameColumn('content', 'contenu');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('name', 'nom');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->renameColumn('name', 'nom');
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->renameColumn('name', 'nom');
            $table->renameColumn('read_at', 'lu_le');
        });

        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->renameColumn('subscribed_at', 'abonne_le');
            $table->renameColumn('unsubscribed_at', 'desabonne_le');
        });

        Schema::table('comment_reports', function (Blueprint $table) {
            $table->renameColumn('reason', 'motif');
            $table->renameColumn('status', 'statut');
        });

        DB::table('comment_reports')->where('statut', 'pending')->update(['statut' => 'en_attente']);
        DB::table('comment_reports')->where('statut', 'reviewed')->update(['statut' => 'examine']);
        DB::table('comment_reports')->where('statut', 'dismissed')->update(['statut' => 'rejete']);
    }

    public function down(): void
    {
        DB::table('comment_reports')->where('statut', 'en_attente')->update(['statut' => 'pending']);
        DB::table('comment_reports')->where('statut', 'examine')->update(['statut' => 'reviewed']);
        DB::table('comment_reports')->where('statut', 'rejete')->update(['statut' => 'dismissed']);

        Schema::table('comment_reports', function (Blueprint $table) {
            $table->renameColumn('statut', 'status');
            $table->renameColumn('motif', 'reason');
        });

        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->renameColumn('desabonne_le', 'unsubscribed_at');
            $table->renameColumn('abonne_le', 'subscribed_at');
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->renameColumn('lu_le', 'read_at');
            $table->renameColumn('nom', 'name');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->renameColumn('nom', 'name');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('nom', 'name');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('contenu', 'content');
            $table->renameColumn('resume', 'excerpt');
            $table->renameColumn('titre', 'title');
            $table->renameColumn('publie_le', 'published_at');
            $table->renameColumn('est_a_la_une', 'is_featured');
            $table->renameColumn('est_epingle', 'is_pinned');
            $table->renameColumn('est_publie', 'is_published');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('contenu', 'content');
            $table->renameColumn('est_approuve', 'is_approved');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('est_administrateur', 'is_admin');
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('filename');
            $table->string('mime_type', 100);
            $table->unsignedInteger('size');
            $table->string('alt')->nullable();
            $table->timestamps();
        });

        Schema::create('post_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['post_id', 'user_id']);
        });

        Schema::create('post_read_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->timestamp('last_read_at');
            $table->timestamps();
            $table->unique(['post_id', 'user_id']);
        });

        Schema::create('post_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->timestamps();
            $table->unique(['post_id', 'user_id']);
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            $table->unique(['post_id', 'user_id']);
        });
    }
};
