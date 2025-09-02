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
        Schema::create('pending_customer_updates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('field');
            $table->text('old_value')->nullable();
            $table->text('new_value');
            $table->foreignId('updated_by')->nullable()->constrained('staff')->onDelete('set null');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pending_customer_updates');
    }
};
