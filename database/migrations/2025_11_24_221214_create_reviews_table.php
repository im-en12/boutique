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
                $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Track which order
                $table->integer('rating')->default(5); // 1-5 stars
                $table->text('comment')->nullable();
                $table->timestamps();
        
        // Prevent duplicate reviews for same product in same order
        $table->unique(['user_id', 'article_id', 'order_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};