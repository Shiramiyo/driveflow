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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->foreignId('host_id')->constrained('users')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('brand');
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->string('car_type');
            $table->string('transmission');
            $table->unsignedTinyInteger('seats');
            $table->string('fuel_type');
            $table->string('location_name');
            $table->decimal('price_per_day', 8, 2);
            $table->decimal('rating', 3, 1)->default(5.0);
            $table->unsignedInteger('trips_count')->default(0);
            $table->string('short_description');
            $table->text('description');
            $table->text('image_url');
            $table->json('gallery');
            $table->json('features');
            $table->json('pickup_options');
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('instant_book')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
