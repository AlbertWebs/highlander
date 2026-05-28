<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('safari_experience_tour', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safari_experience_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['safari_experience_id', 'tour_id'], 'safari_experience_tour_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safari_experience_tour');
    }
};
