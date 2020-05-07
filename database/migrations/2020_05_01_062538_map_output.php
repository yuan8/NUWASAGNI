<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MapOutput extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
          Schema::create('output_map',function(Blueprint $table){
            $table->bigIncrements('íd');
            $table->text("title");
            $table->text("file_path")->nullable();
            $table->bigInteger("user_id")->unsigned();
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
        Schema::dropIfExists('output_map');

    }
}
