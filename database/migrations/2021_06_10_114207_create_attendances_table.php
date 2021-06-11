<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->timestamp('time')->comment('Punch In Punch Out Time')->useCurrent = true;
            $table->enum('status', ['punch_in', 'punch_out']);
            $table->decimal('distance', 18, 2)->nullable()->comment('Distance between user and branch when any user punche in or punche out in metres'); 
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('ip_address', 40)->nullable()->comment('User IP Address'); 
            $table->unsignedBigInteger('branch_id')->index();
            $table->unsignedBigInteger('created_by')->index();
            $table->unsignedBigInteger('updated_by')->index();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });

        Schema::dropIfExists('attendances');
    }
}
