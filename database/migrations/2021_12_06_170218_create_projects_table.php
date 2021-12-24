<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedBigInteger('category_id')->unsigned()->index();
            $table->date('deadline')->comment('project deadline')->useCurrent = true;
            $table->unsignedBigInteger('created_by')->index();
            $table->unsignedBigInteger('updated_by')->index();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('project_categories');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
        Schema::dropIfExists('projects');
    }
}
