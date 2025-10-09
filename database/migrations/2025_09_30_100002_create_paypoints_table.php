<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaypointsTable extends Migration
{
    public function up()
    {
        Schema::create('paypoints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type'); // zone or district
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('paypoints');
    }
}