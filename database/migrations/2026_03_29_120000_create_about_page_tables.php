<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title');
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('intro_heading');
            $table->text('intro_paragraph_1');
            $table->text('intro_paragraph_2')->nullable();
            $table->string('intro_image')->nullable();
            $table->string('intro_cta_label')->nullable();
            $table->string('fleet_heading');
            $table->text('fleet_body');
            $table->string('team_heading');
            $table->text('team_body');
            $table->string('team_image')->nullable();
            $table->string('safety_heading');
            $table->text('safety_body');
            $table->string('safety_image')->nullable();
            $table->string('core_values_section_title')->default('Our Core Values');
            $table->string('sustainability_section_title')->default('Our Sustainability Initiatives');
            $table->string('testimonials_section_title')->default('What Travelers Say');
            $table->string('cta_heading');
            $table->text('cta_body');
            $table->string('cta_button_label')->nullable();
            $table->timestamps();
        });

        Schema::create('about_vision_mission_cards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('icon')->default('🎯');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('about_core_values', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->default('✓');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('about_fleet_images', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('caption')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('about_fleet_subsections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('about_team_roles', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('about_safety_points', function (Blueprint $table) {
            $table->id();
            $table->string('point_text');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('about_sustainability_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->default('🌿');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->boolean('show_on_about')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('show_on_about');
        });

        Schema::dropIfExists('about_sustainability_items');
        Schema::dropIfExists('about_safety_points');
        Schema::dropIfExists('about_team_roles');
        Schema::dropIfExists('about_fleet_subsections');
        Schema::dropIfExists('about_fleet_images');
        Schema::dropIfExists('about_core_values');
        Schema::dropIfExists('about_vision_mission_cards');
        Schema::dropIfExists('about_page_settings');
    }
};
