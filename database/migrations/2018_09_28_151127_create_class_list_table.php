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
            $table->increments('id');
            $table->string('classId', 12);
            $table->string('className')->nullable();
            $table->string('teacher')->nullable();
            $table->longText('likeCount')->nullable();
            $table->longText('dislikeCount')->nullable();
            $table->string('classType', 12)->nullable();
            $table->string('school',12)->nullable();
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
