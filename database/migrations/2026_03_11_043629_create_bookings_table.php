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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('status')->default('confirmed');
            $table->string('booking_rate');
            $table->string('payment_method');
            $table->string('card_last_four', 4)->nullable();
            $table->string('payment_country');
            $table->string('pickup_option');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->unsignedTinyInteger('trip_days');
            $table->decimal('price_per_day', 8, 2);
            $table->decimal('trip_subtotal', 8, 2);
            $table->decimal('protection_fee', 8, 2)->default(0);
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->decimal('total_amount', 8, 2);
            $table->string('driver_phone');
            $table->string('driver_license_number');
            $table->text('notes')->nullable();
            $table->boolean('wants_marketing')->default(false);
            $table->boolean('terms_accepted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
