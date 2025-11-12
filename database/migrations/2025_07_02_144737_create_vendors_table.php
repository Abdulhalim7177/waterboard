<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('street_name')->nullable();
            $table->string('vendor_code')->unique()->nullable();
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('ward_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('lga_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendors');
    }
};