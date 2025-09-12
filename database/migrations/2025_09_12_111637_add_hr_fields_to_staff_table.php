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
            $table->string('staff_id')->nullable()->after('id');
            $table->string('first_name')->nullable()->after('name');
            $table->string('surname')->nullable()->after('first_name');
            $table->string('middle_name')->nullable()->after('surname');
            $table->string('gender')->nullable()->after('middle_name');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('state_of_origin')->nullable()->after('date_of_birth');
            $table->string('nationality')->nullable()->after('state_of_origin');
            $table->string('nin')->nullable()->after('nationality');
            $table->string('mobile_no')->nullable()->after('phone_number');
            $table->text('address')->nullable()->after('email');
            $table->date('date_of_first_appointment')->nullable()->after('address');
            $table->string('rank')->nullable()->after('date_of_first_appointment');
            $table->string('staff_no')->nullable()->after('rank');
            $table->string('department')->nullable()->after('staff_no');
            $table->date('expected_next_promotion')->nullable()->after('department');
            $table->date('expected_retirement_date')->nullable()->after('expected_next_promotion');
            $table->string('highest_qualifications')->nullable()->after('status');
            $table->string('grade_level_limit')->nullable()->after('highest_qualifications');
            $table->string('appointment_type')->nullable()->after('grade_level_limit');
            $table->string('photo_path')->nullable()->after('appointment_type');
            $table->integer('years_of_service')->nullable()->after('photo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn([
                'staff_id',
                'first_name',
                'surname',
                'middle_name',
                'gender',
                'date_of_birth',
                'state_of_origin',
                'nationality',
                'nin',
                'mobile_no',
                'address',
                'date_of_first_appointment',
                'rank',
                'staff_no',
                'department',
                'expected_next_promotion',
                'expected_retirement_date',
                'highest_qualifications',
                'grade_level_limit',
                'appointment_type',
                'photo_path',
                'years_of_service'
            ]);
        });
    }
};
