<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {

            $table->date('contract_start_date')->nullable()->after('date_of_first_appointment');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
            $table->unsignedBigInteger('department_id')->nullable()->after('department');
            $table->unsignedBigInteger('rank_id')->nullable()->after('rank');
            $table->unsignedBigInteger('cadre_id')->nullable()->after('rank_id');
            $table->unsignedBigInteger('grade_level_id')->nullable()->after('cadre_id');
            $table->unsignedBigInteger('step_id')->nullable()->after('grade_level_id');
            $table->unsignedBigInteger('appointment_type_id')->nullable()->after('appointment_type');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['rank_id']);
            $table->dropForeign(['cadre_id']);
            $table->dropForeign(['grade_level_id']);
            $table->dropForeign(['step_id']);
            $table->dropForeign(['appointment_type_id']);


            $table->dropColumn('contract_start_date');
            $table->dropColumn('contract_end_date');
            $table->dropColumn('department_id');
            $table->dropColumn('rank_id');
            $table->dropColumn('cadre_id');
            $table->dropColumn('grade_level_id');
            $table->dropColumn('step_id');
            $table->dropColumn('appointment_type_id');
        });
    }
};