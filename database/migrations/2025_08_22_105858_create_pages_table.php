<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique(); // "about", "contact"

            // Название страницы
            $table->string('title_az')->nullable();
            $table->string('title_ru')->nullable();
            $table->string('title_en')->nullable();

            // Контент
            $table->longText('content_az')->nullable();
            $table->longText('content_ru')->nullable();
            $table->longText('content_en')->nullable();

            // SEO
            $table->string('seo_title_az')->nullable();
            $table->string('seo_title_ru')->nullable();
            $table->string('seo_title_en')->nullable();

            $table->text('seo_description_az')->nullable();
            $table->text('seo_description_ru')->nullable();
            $table->text('seo_description_en')->nullable();

            $table->string('seo_keywords_az')->nullable();
            $table->string('seo_keywords_ru')->nullable();
            $table->string('seo_keywords_en')->nullable();

            // Управление страницей
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
