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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->date('publishing_date');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('subcategory_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 5, 2)->nullable();
            $table->decimal('discounted_price', 10, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->string('product_id')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
