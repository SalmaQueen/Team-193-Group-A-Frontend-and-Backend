<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string("is_active")->default(1);
            $table->string("sacco_name")->nullable();
            $table->string("vehicle_registration_number");
            $table->string("vehicle_nickname");
            $table->string("driver_name");
            $table->string("driver_id_number");
            $table->string("driver_dl_number");
            $table->string("conductor_name");
            $table->string("conductor_id_number");
            $table->string("conductor_permit_number");
            $table->string("drivers_phone_number");
            $table->string("conductors_phone_number");
            $table->string("capacity");
            $table->string("daily_target");
            $table->string("added_by_name")->nullable();
            $table->string("added_by_email")->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('vehicles');
    }
}
