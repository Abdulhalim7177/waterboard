<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZoneDistrictToStaffTable extends Migration
{
    public function up()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->unsignedBigInteger('zone_id')->nullable()->after('ward_id');
            $table->unsignedBigInteger('district_id')->nullable()->after('zone_id');
            
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropForeign(['district_id']);
            $table->dropColumn(['zone_id', 'district_id']);
        });
    }
}