<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('location');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->unsignedInteger('max_attendees');
            $table->string('image_url')->nullable();
            $table->boolean('is_published')->default(false);

            $table->index(['start_date', 'is_published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
