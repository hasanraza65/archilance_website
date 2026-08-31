<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->json('services')->nullable();
            $table->string('project_type')->nullable();
            $table->string('size')->nullable();
            $table->string('scope')->nullable();
            $table->string('timeline')->nullable();
            $table->string('engagement')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('hours_low')->default(0);
            $table->unsignedInteger('hours_high')->default(0);
            $table->unsignedInteger('price_low')->default(0);
            $table->unsignedInteger('price_high')->default(0);
            $table->string('recommended_plan')->nullable();
            $table->json('breakdown')->nullable();
            $table->string('ip', 45)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
