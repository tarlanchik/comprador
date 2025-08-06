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
            $table->string('title_az');
            $table->text('content_az');
            $table->string('title_ru');
            $table->text('content_ru');
            $table->string('title_en');
            $table->text('content_en');
            $table->string('keywords_az')->nullable();
            $table->string('keywords_ru')->nullable();
            $table->string('keywords_en')->nullable();
            $table->text('description_az')->nullable();
            $table->text('description_ru')->nullable();
            $table->text('description_en')->nullable();
            $table->string('youtube_link')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
