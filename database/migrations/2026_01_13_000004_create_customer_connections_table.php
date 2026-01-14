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
        Schema::create('customer_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('connection_type_id');
            $table->unsignedBigInteger('connection_size_id')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, active, inactive
            $table->text('notes')->nullable();
            $table->timestamp('installation_date')->nullable();
            $table->unsignedBigInteger('installed_by')->nullable(); // staff who installed
            $table->timestamps();
            
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('connection_type_id')->references('id')->on('connection_types')->onDelete('cascade');
            $table->foreign('connection_size_id')->references('id')->on('connection_sizes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('customer_connections');
    }
};