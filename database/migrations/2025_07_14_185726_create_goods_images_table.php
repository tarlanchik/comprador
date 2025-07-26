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
        Schema::create('goods_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_id')->constrained('goods')->onDelete('cascade');
            $table->string('path'); // путь к изображению
            $table->integer('order')->default(0); // порядок сортировки
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_images');
    }
};
