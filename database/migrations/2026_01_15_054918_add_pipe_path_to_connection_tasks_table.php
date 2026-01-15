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
        Schema::table('connection_tasks', function (Blueprint $table) {
            $table->json('pipe_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('connection_tasks', function (Blueprint $table) {
            $table->dropColumn('pipe_path');
        });
    }
};
