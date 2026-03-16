<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('press_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('publication')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->string('pdf')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('press_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('press_event_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('press_images');
        Schema::dropIfExists('press_events');
    }
};
