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
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("category_id");
            $table->string("name");
            $table->string("slug")->unique();
            $table->text("images");
            $table->string("address");
            $table->string("duration");
            $table->string("group_size");
            $table->integer("price");
            $table->integer("discount")->nullable();
            $table->text("overview");
            $table->timestamp('start_date')->nullable();
            $table->timestamp('return_date')->nullable();
            $table->text("included");
            $table->text("excluded");
            $table->text("tour_plan");
            $table->string("vehicle");
            $table->boolean("status")->nullable()->comment('false = booking abailable, null = tour running, true = tour complete');
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade");
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
