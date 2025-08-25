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
        Schema::create('goods', function (Blueprint $table) {
            $table->id();

            // Категория (с каскадным удалением)
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            // Мультиязычные поля (JSON)
            $table->json('name');
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->json('keywords')->nullable();

            // Цены и прочее
            $table->decimal('price', 10, 2);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->integer('count')->default(0);

            $table->string('youtube_link')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
};
