<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('billing_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->timestamp('payment_date')->nullable();
            $table->string('method')->nullable();
            $table->string('status')->default('pending');
            $table->string('payment_status')->default('pending');
            $table->string('channel')->nullable();
            $table->string('transaction_ref')->nullable();
            $table->string('payment_code')->nullable();
            $table->string('payer_ref_no')->nullable();
            $table->string('nabroll_ref')->nullable(); // For NABRoll API reference
            $table->text('nabroll_response')->nullable(); // For storing NABRoll API response
            $table->timestamps();
            
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_payments');
    }
};
