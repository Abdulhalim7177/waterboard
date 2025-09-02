<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTariffsTable extends Migration
{
    public function up()
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('catcode', 3)->unique();
            $table->unsignedBigInteger('category_id');
            $table->decimal('amount', 10, 2);
            $table->decimal('rate', 8, 4);
            $table->decimal('fixed_charge', 10, 2)->default(0); // Added fixed_charge field
            $table->string('billing_type')->default('Flat'); // Added billing_type field
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tariffs');
    }
}