<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Gives every team member a public profile page of their own.
 *
 * `blurb` and `extra` stay exactly as they are — the org chart and its hover
 * card still read them. Everything added here only feeds the profile page, so
 * a member with none of it filled in still renders a valid, tidy page.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            // ---- profile content
            $table->string('headline')->nullable()->after('role');
            $table->text('bio')->nullable()->after('blurb');
            $table->string('quote', 500)->nullable()->after('bio');
            $table->string('location')->nullable()->after('quote');

            $table->json('focus')->nullable()->after('location');       // what they own
            $table->json('expertise')->nullable()->after('focus');      // tools / skills
            $table->json('highlights')->nullable()->after('expertise'); // stat tiles
            $table->json('education')->nullable()->after('highlights'); // degrees

            // ---- contact
            $table->string('email')->nullable()->after('education');
            $table->string('linkedin')->nullable()->after('email');
            $table->string('behance')->nullable()->after('linkedin');

            // ---- imagery
            $table->string('photo_full')->nullable()->after('photo');
            $table->string('image_alt')->nullable()->after('photo_full');

            // ---- publishing
            $table->boolean('has_profile')->default(true)->after('is_published');

            // ---- SEO, matching the shape every other content type uses
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 500)->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical')->nullable();
            $table->boolean('noindex')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn([
                'headline', 'bio', 'quote', 'location',
                'focus', 'expertise', 'highlights', 'education',
                'email', 'linkedin', 'behance',
                'photo_full', 'image_alt', 'has_profile',
                'meta_title', 'meta_description', 'meta_keywords',
                'og_image', 'canonical', 'noindex',
            ]);
        });
    }
};
