<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('bill_id')->nullable();
            $table->string('payer_ref_no')->nullable ();
            $table->string('bill_ids')->nullable();
            $table->string('transaction_ref')->nullable();
            $table->string('payment_code')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('method');
            $table->string('channel')->nullable();
            $table->decimal('balance', 10, 2)->default(0.00);
            $table->enum('status', ['pending', 'successful', 'failed'])->default('pending');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('payment_date')->nullable();
            $table->string('payment_status')->default('pending');
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
