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
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreignId('lga_id')->nullable()->constrained('lgas')->onDelete('set null')->after('status');
            $table->foreignId('ward_id')->nullable()->constrained('wards')->onDelete('set null')->after('lga_id');
            $table->foreignId('area_id')->nullable()->constrained('areas')->onDelete('set null')->after('ward_id');
        });
    }

    public function down()
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign(['lga_id']);
            $table->dropForeign(['ward_id']);
            $table->dropForeign(['area_id']);
            $table->dropColumn(['lga_id', 'ward_id', 'area_id']);
        });
    }
};
