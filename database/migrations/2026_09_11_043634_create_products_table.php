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
            $table->string('slug')->unique();
            $table->string('category')->default('Full-Face');
            $table->decimal('price', 12, 2);
            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->text('specification')->nullable();
            $table->text('materials')->nullable();
            $table->string('image');
            $table->json('gallery_images')->nullable();
            $table->json('sizes')->nullable();
            $table->boolean('is_weekly_featured')->default(true);
            $table->timestamps();
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
