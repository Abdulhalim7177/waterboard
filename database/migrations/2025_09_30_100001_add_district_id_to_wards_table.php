<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistrictIdToWardsTable extends Migration
{
    public function up()
    {
        Schema::table('wards', function (Blueprint $table) {
            $table->unsignedBigInteger('district_id')->nullable()->after('lga_id');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('wards', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
            $table->dropColumn(['district_id']);
        });
    }
}