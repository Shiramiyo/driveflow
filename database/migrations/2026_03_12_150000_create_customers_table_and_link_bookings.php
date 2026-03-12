<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('driver_license_number', 60)->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });

        $now = now();
        $users = DB::table('users')
            ->select('id', 'name', 'phone', 'email', 'driver_license_number')
            ->where('role', 'customer')
            ->orWhereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('bookings')
                    ->whereColumn('bookings.user_id', 'users.id');
            })
            ->get();

        foreach ($users as $user) {
            DB::table('customers')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'driver_license_number' => $user->driver_license_number,
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );
        }

        DB::statement('
            UPDATE bookings
            INNER JOIN customers ON customers.user_id = bookings.user_id
            SET bookings.customer_id = customers.id
            WHERE bookings.customer_id IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_id');
        });

        Schema::dropIfExists('customers');
    }
};
