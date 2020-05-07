<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OutputPublish extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
          Schema::create('output_publish',function(Blueprint $table){
            $table->bigIncrements('íd');
            $table->text("title");
            $table->tinyInteger("type")->default(0)->comment('0 multy,1 file');
            $table->text("file_path")->nullable();
            $table->longText("content")->nullable();
            $table->bigInteger("user_id")->unsigned();
            $table->dateTime("publish_date")->nullable();
            $table->integer("tahun");
            $table->timestamps();
            $table->foreign('user_id')
              ->references('id')->on('users')
              ->onDelete('cascade')->onUpdate('cascade');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('output_publish');

    }
}
