<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->string('comment', 1000);
            $table->timestamps();

            $table->index(['listing_id', 'created_at']);
            $table->index(['listing_id', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_feedback');
    }
};
