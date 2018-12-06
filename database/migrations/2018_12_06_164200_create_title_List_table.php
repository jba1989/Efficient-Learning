<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTitleListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('title_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('classId', 12);
            $table->integer('titleId', false, true);
            $table->string('title', 500)->nullable();
            $table->mediumText('videoLink')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('title_list');
    }
}
