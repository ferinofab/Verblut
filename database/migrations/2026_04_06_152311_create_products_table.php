<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('moysklad_id')->unique();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('amount')->default(0);
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->foreignId('category_id')->nullable()->default(null)->constrained('categories')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
