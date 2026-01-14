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
        Schema::create('connection_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Private Water Connection, Commercial Water Connection, Legalisation, Reconnection Fee
            $table->string('slug')->unique(); // private_water_connection, commercial_water_connection, legalisation, reconnection_fee
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('connection_types');
    }
};