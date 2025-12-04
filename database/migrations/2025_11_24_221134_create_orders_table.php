<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->decimal('total_price', 12, 2)->default(0);
                $table->string('status')->default('pending'); // pending, paid, shipped, delivered, cancelled
                $table->timestamps();

                $table->index(['user_id']);
                $table->index(['status']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
