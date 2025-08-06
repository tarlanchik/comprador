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
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            // Мультиязычные поля
            $table->string('name_ru');
            $table->string('name_en');
            $table->string('name_az');

            $table->string('title_ru')->nullable();
            $table->string('title_en')->nullable();
            $table->string('title_az')->nullable();

            $table->text('description_ru')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_az')->nullable();

            $table->text('keywords_ru')->nullable();
            $table->text('keywords_en')->nullable();
            $table->text('keywords_az')->nullable();

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
