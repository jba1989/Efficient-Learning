<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassListLikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_list_like', function (Blueprint $table) {
            $table->increments('id');
            $table->string('classId', 12);
            $table->json('likeUserList')->nullable();
            $table->json('dislikeUserList')->nullable();
            $table->unsignedMediumInteger('likeCount')->default(0)->nullable();
            $table->unsignedMediumInteger('dislikeCount')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_list_like');
    }
}
