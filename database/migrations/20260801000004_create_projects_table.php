<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category_label')->nullable();
            $table->string('categories')->nullable();
            $table->string('card_image')->nullable();
            $table->unsignedInteger('card_width')->nullable();
            $table->unsignedInteger('card_height')->nullable();
            $table->string('full_image')->nullable();
            $table->string('image_alt')->nullable();
            $table->json('tags')->nullable();
            $table->string('span')->nullable();
            $table->boolean('featured')->default(false);
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 500)->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical')->nullable();
            $table->boolean('noindex')->default(false);
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
