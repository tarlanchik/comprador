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
        Schema::table('news', function (Blueprint $table) {
            $table->unsignedInteger('comments_count')->default(0)->after('youtube_link');
            $table->boolean('comments_enabled')->default(true)->after('comments_count');
            $table->timestamp('last_commented_at')->nullable()->after('comments_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['comments_count', 'comments_enabled', 'last_commented_at']);
        });
    }
};
