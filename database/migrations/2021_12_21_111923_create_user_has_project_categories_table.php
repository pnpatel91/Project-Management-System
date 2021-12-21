<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHasProjectCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_has_project_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_category_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamps();
            
            $table->foreign('project_category_id')->references('id')->on('project_categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_has_project_categories', function (Blueprint $table) {
            $table->dropForeign(['project_category_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('user_has_project_categories');
    }
}
