<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
                $table->tinyInteger('rating')->unsigned(); // 1..5 handled by app validation
                $table->text('comment')->nullable();
                $table->timestamps();

                $table->index(['rating']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};