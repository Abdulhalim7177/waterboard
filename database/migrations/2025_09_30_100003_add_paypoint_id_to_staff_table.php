<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaypointIdToStaffTable extends Migration
{
    public function up()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->unsignedBigInteger('paypoint_id')->nullable()->after('district_id');
            $table->foreign('paypoint_id')->references('id')->on('paypoints')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['paypoint_id']);
            $table->dropColumn(['paypoint_id']);
        });
    }
}