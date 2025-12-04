<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
                $table->unsignedInteger('quantity')->default(1);
                $table->decimal('unit_price', 10, 2)->nullable(); // snapshot of price at purchase
                $table->timestamps();

                $table->index(['order_id']);
                $table->index(['article_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};