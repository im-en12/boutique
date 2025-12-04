<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('excerpt')->nullable();
                $table->decimal('price', 10, 2)->nullable(); // optional for articles, keep if needed by template
                $table->unsignedInteger('stock')->default(0);
                $table->string('image')->nullable();
                $table->unsignedBigInteger('views_count')->default(0);
                $table->boolean('is_featured')->default(false);
                $table->unsignedInteger('sales_count')->default(0);
                // foreign keys
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
                $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('published_at')->nullable();
                $table->timestamps();

                // indexes useful for filtering/sorting
                $table->index(['category_id']);
                $table->index(['brand_id']);
                $table->index(['price']);
                $table->index(['published_at']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
};