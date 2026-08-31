<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('team')->default('team');
            $table->text('blurb')->nullable();
            $table->text('extra')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_leadership')->default(false);
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('team_members')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
