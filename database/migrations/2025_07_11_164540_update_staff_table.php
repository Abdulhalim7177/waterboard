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
        Schema::table('staff', function (Blueprint $table) {
            $table->unsignedBigInteger('lga_id')->nullable()->after('succ');
            $table->unsignedBigInteger('ward_id')->nullable()->after('lga_id');
            $table->unsignedBigInteger('area_id')->nullable()->after('ward_id');

            $table->foreign('lga_id')->references('id')->on('lgas')->onDelete('set null');
            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('set null');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['lga_id']);
            $table->dropForeign(['ward_id']);
            $table->dropForeign(['area_id']);
            $table->dropColumn(['lga_id', 'ward_id', 'area_id']);
        });
    }
};
