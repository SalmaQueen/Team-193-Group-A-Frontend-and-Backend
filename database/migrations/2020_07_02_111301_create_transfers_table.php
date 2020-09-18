<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string("vehicle_registration_number")->nullable();
            $table->string("sacco_name")->nullable();
            $table->string("ConversationID")->nullable();
            $table->string("TransactionAmount")->nullable();
            $table->string("TransactionReceipt")->nullable();
            $table->string("ReceiverPartyPublicName")->nullable();
            $table->string("ReceiverPhoneNumber")->nullable();
            $table->string("TransactionCompletedDateTime")->nullable();
            $table->string("B2CUtilityAccountAvailableFunds")->nullable();
            $table->string("B2CWorkingAccountAvailableFunds")->nullable();
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
        Schema::dropIfExists('transfers');
    }
}
