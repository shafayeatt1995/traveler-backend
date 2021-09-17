<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('place_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('thumbnail');
            $table->text('images');
            $table->string('address');
            $table->integer('duration_day');
            $table->integer('duration_night');
            $table->string('group_size');
            $table->integer('ticket');
            $table->decimal('price', 7, 2);
            $table->decimal('discount', 7, 2)->nullable();
            $table->decimal('min_booking_amount', 7, 2);
            $table->text('overview');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('return_date')->nullable();
            $table->text('included');
            $table->text('excluded');
            $table->text('tour_plan');
            $table->string('vehicle');
            $table->boolean('status')->nullable()->comment('null = booking abailable, false = tour running, true = tour complete');
            $table->integer('view')->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
