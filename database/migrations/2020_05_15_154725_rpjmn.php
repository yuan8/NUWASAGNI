<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Rpjmn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasTable('tb_2020_2024_rpjmn')){ 
          Schema::create('tb_2020_2024_rpjmn',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->text("nama");
            $table->string("jenis",10);
            $table->bigInteger('id_pn')->unsigned()->nullable();
            $table->bigInteger('id_pp')->unsigned()->nullable();
            $table->bigInteger('id_kp')->unsigned()->nullable();
            $table->bigInteger('id_propn')->unsigned()->nullable();
            $table->string("info_path")->nullable()->unique();
            $table->timestamps();

            $table->foreign('id_pn')
            ->references('id')->on('tb_2020_2024_rpjmn')
            ->onDelete('cascade')->onUpdate('cascade');
             $table->foreign('id_pp')
            ->references('id')->on('tb_2020_2024_rpjmn')
            ->onDelete('cascade')->onUpdate('cascade');
             $table->foreign('id_kp')
            ->references('id')->on('tb_2020_2024_rpjmn')
            ->onDelete('cascade')->onUpdate('cascade');
             $table->foreign('id_propn')
            ->references('id')->on('tb_2020_2024_rpjmn')
            ->onDelete('cascade')->onUpdate('cascade');
        });
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('tb_2020_2024_rpjmn');

    }
}
