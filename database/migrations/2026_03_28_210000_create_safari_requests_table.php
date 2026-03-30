<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('safari_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('country')->nullable();
            $table->string('contact_method')->nullable();

            $table->date('arrival_date');
            $table->date('departure_date');
            $table->unsignedSmallInteger('trip_duration')->nullable();
            $table->boolean('flexible_dates')->default(false);

            $table->unsignedTinyInteger('adults');
            $table->unsignedTinyInteger('children')->default(0);
            $table->string('children_ages')->nullable();

            $table->string('group_type')->nullable();

            $table->json('destinations')->nullable();
            $table->json('experience_types')->nullable();

            $table->string('accommodation_type')->nullable();
            $table->string('room_type')->nullable();

            $table->string('transport_type')->nullable();
            $table->boolean('airport_pickup')->default(false);

            $table->string('budget_range')->nullable();

            $table->json('activities')->nullable();

            $table->text('special_requests')->nullable();

            $table->boolean('consent_privacy')->default(false);

            $table->string('status', 191)->default('new');
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safari_requests');
    }
};
