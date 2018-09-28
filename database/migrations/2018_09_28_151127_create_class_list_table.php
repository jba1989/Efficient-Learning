<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_list', function (Blueprint $table) {
            $table->increments('classId');
            $table->string('className');
            $table->string('teacher');
            $table->longText('likeCount');
            $table->longText('dislikeCount');
            $table->string('classType', 12);
            $table->string('school',12);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_list');
    }
}
