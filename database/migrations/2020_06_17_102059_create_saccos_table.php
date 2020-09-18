<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaccosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saccos', function (Blueprint $table) {
            $table->id();
            $table->string("is_active")->default(1);
            $table->string("sacco_name");
            $table->string("registration_number");
            $table->string("route_name");
            $table->string("route_number");
            $table->string("chair_name");
            $table->string("chair_id_number");
            $table->string("chair_email_address");
            $table->string("chair_phone_number");
            $table->string("added_by_name");
            $table->string("added_by_email");
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
        Schema::dropIfExists('saccos');
    }
}
