<?php

// database/migrations/2025_07_06_124500_create_customers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('alternate_phone_number')->nullable();
            $table->string('street_name')->nullable();
            $table->string('house_number')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('lga_id')->nullable();
            $table->unsignedBigInteger('ward_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('tariff_id')->nullable();
            $table->string('landmark')->nullable();
            $table->string('delivery_code')->nullable();
            $table->string('billing_id')->nullable()->unique();
            $table->string('billing_condition')->nullable();
            $table->string('water_supply_status')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('altitude', 10, 2)->nullable();
            $table->json('polygon_coordinates')->nullable(); 
            $table->json('pipe_path')->nullable(); 
            $table->string('password');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->decimal('meter_reading', 10, 2)->nullable();
            $table->decimal('account_balance', 10, 2)->default(0.00);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
            $table->foreign('lga_id')->references('id')->on('lgas')->onDelete('set null');
            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('tariff_id')->references('id')->on('tariffs')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
