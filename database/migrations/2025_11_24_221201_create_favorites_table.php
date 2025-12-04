<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
                $table->timestamps();

                // Prevent duplicate favorites: a user can favorite an article only once
                $table->unique(['user_id', 'article_id'], 'favorites_user_article_unique');

                $table->index(['user_id']);
                $table->index(['article_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};
