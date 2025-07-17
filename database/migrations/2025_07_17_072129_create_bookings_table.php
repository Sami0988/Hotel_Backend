<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Polymorphic relation fields
            $table->unsignedBigInteger('bookable_id');
            $table->string('bookable_type');

            // Foreign key to users table (who booked)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Booking time range
            $table->dateTime('start_time');
            $table->dateTime('end_time');

            // Other booking details, like status
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
