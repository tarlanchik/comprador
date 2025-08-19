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
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_type_id')->constrained()->onDelete('cascade');
            $table->string('name_ru', 191)->nullable();
            $table->string('name_en', 191)->nullable();
            $table->string('name_az', 191)->nullable();
            $table->timestamps();

            $table->unique(['product_type_id', 'name_ru']);
            $table->unique(['product_type_id', 'name_en']);
            $table->unique(['product_type_id', 'name_az']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameters');
    }
};
