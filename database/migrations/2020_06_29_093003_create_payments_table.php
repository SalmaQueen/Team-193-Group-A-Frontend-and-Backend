<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string("CheckoutRequestID")->nullable();
            $table->string("MpesaReceiptNumber")->nullable();
            $table->string("Amount")->nullable();
            $table->string("PhoneNumber")->nullable();
            $table->string("TransactionDate")->nullable();
            $table->string("sacco_name")->nullable();
            $table->string("vehicle_registration_number")->nullable();
            $table->string("pay_code")->default(0);
            $table->string("is_approved")->default(0);
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
        Schema::dropIfExists('payments');
    }
}
