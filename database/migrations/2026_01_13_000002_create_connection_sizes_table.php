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
        Schema::create('connection_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 12.5mm (1/2 inch), 20mm (3/4 inch), etc.
            $table->string('size_mm'); // 12.5, 20, 25, 37, 50
            $table->string('size_inches'); // 1/2, 3/4, 1, 1 1/4, 1 1/2, 2
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('connection_sizes');
    }
};