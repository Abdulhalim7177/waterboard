<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // For districts table - update to include pending_delete (this should work since districts enum is similar to others)
        DB::statement("ALTER TABLE districts MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'pending_delete') DEFAULT 'pending'");

        // For paypoints table - first expand enum to include all possible values
        DB::statement("ALTER TABLE paypoints MODIFY COLUMN status ENUM('active', 'inactive', 'pending', 'approved', 'rejected', 'pending_delete') DEFAULT 'active'");

        // Update the data: active -> approved, inactive -> rejected
        DB::table('paypoints')->where('status', 'active')->update(['status' => 'approved']);
        DB::table('paypoints')->where('status', 'inactive')->update(['status' => 'rejected']);

        // Now change enum to final values only
        DB::statement("ALTER TABLE paypoints MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'pending_delete') DEFAULT 'pending'");
    }

    public function down()
    {
        // First expand enum to include old values
        DB::statement("ALTER TABLE paypoints MODIFY COLUMN status ENUM('active', 'inactive', 'pending', 'approved', 'rejected', 'pending_delete') DEFAULT 'active'");

        // Update data back: approved -> active, rejected/pending/pending_delete -> inactive
        DB::table('paypoints')->where('status', 'approved')->update(['status' => 'active']);
        DB::table('paypoints')->whereIn('status', ['rejected', 'pending', 'pending_delete'])->update(['status' => 'inactive']);

        // Revert to original enum values
        DB::statement("ALTER TABLE paypoints MODIFY COLUMN status ENUM('active', 'inactive') DEFAULT 'active'");

        // Revert districts to original
        DB::statement("ALTER TABLE districts MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};