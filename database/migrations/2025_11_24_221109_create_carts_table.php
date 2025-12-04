<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // carts table represents cart items per user
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
                $table->unsignedInteger('quantity')->default(1);
                $table->timestamps();

                $table->index(['user_id']);
                $table->index(['article_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};