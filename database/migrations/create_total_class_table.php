<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTotalClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_class', function (Blueprint $table) {
            $table->increments('titleId');
            $table->smallInteger('classId');
            $table->string('className');
            $table->string('title', 50);
            $table->mediumText('videoLink');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('total_class');
    }
}
