<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribes', function (Blueprint $table) {
            $table->id();
            $table->string('sacco_name')->nullable();
            $table->string('amount')->nullable();
            $table->string('period')->nullable();
            $table->string('number_of_scans')->nullable();
            $table->string('PhoneNumber')->nullable();
            $table->string('pay_code')->nullable();
            $table->string('so_far_scanned')->default(0);
            $table->string('CheckoutRequestID')->nullable();
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
        Schema::dropIfExists('subscribes');
    }
}
