<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();

            // Локализуемые поля
            $table->json('title');
            $table->json('content');
            $table->json('keywords')->nullable();
            $table->json('description')->nullable();

            // Доп. поля
            $table->string('youtube_link')->nullable();
            $table->unsignedBigInteger('views')->default(0);

            // Связь с категориями
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();

            // Автор
            $table->foreignId('author_id')->nullable()->default(1)->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
