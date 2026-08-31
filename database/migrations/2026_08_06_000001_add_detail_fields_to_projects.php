<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Projects used to exist only as a tile that opened a lightbox. Each one now
 * has a page of its own, which needs somewhere to keep the write-up, the
 * at-a-glance facts and any extra imagery.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('summary')->nullable()->after('description');
            $table->longText('body')->nullable()->after('summary');
            $table->longText('facts')->nullable()->after('body');
            $table->longText('gallery')->nullable()->after('facts');
            $table->longText('deliverables')->nullable()->after('gallery');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['summary', 'body', 'facts', 'gallery', 'deliverables']);
        });
    }
};
