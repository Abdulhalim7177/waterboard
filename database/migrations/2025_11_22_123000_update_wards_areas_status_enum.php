<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // For MySQL/MariaDB, modify the enum values
        DB::statement("ALTER TABLE wards MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'pending_delete') DEFAULT 'pending'");
        DB::statement("ALTER TABLE areas MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'pending_delete') DEFAULT 'pending'");
    }

    public function down()
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE wards MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
        DB::statement("ALTER TABLE areas MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};