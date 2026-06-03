<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_views', function (Blueprint $table) {
            $table->string('country_code', 2)->nullable()->after('ip_address');
            $table->unsignedInteger('duration_seconds')->default(0)->after('country_code');
            $table->string('user_agent')->nullable()->after('duration_seconds');
        });
    }

    public function down(): void
    {
        Schema::table('post_views', function (Blueprint $table) {
            $table->dropColumn(['country_code', 'duration_seconds', 'user_agent']);
        });
    }
};
