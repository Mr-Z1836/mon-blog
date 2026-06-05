<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('cta_title')->nullable()->after('meta_description');
            $table->string('cta_text', 100)->nullable()->after('cta_title');
            $table->string('cta_url', 500)->nullable()->after('cta_text');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['cta_title', 'cta_text', 'cta_url']);
        });
    }
};
