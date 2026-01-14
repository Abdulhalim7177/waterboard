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
        Schema::create('connection_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('connection_type_id');
            $table->unsignedBigInteger('connection_size_id')->nullable(); // Nullable for non-size-dependent fees like legalisation/reconnection
            $table->decimal('fee_amount', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('connection_type_id')->references('id')->on('connection_types')->onDelete('cascade');
            $table->foreign('connection_size_id')->references('id')->on('connection_sizes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('connection_fees');
    }
};