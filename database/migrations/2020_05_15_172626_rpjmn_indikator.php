<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class RpjmnIndikator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         if(!Schema::hasTable('tb_2020_2024_rpjmn_indikator')){ 
          Schema::create('tb_2020_2024_rpjmn_indikator',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->text("nama");
            $table->string("jenis",10);
            $table->bigInteger('id_pn')->unsigned()->nullable();
            $table->bigInteger('id_pp')->unsigned()->nullable();
            $table->bigInteger('id_kp')->unsigned()->nullable();
            $table->bigInteger('id_propn')->unsigned()->nullable();
            $table->bigInteger('id_pronas')->unsigned()->nullable();

            $table->string("target_1_1")->nullable();
            $table->string("target_1_2")->nullable();

            $table->string("target_2_1")->nullable();
            $table->string("target_2_2")->nullable();

            $table->string("target_3_1")->nullable();
            $table->string("target_3_2")->nullable();

            $table->string("target_4_1")->nullable();
            $table->string("target_4_2")->nullable();

            $table->string("target_5_1")->nullable();
            $table->string("target_5_2")->nullable();

            $table->string("satuan")->nullable();

            $table->string("cal_type")->nullable();

            $table->boolean("this_numeric_type")->default(false);

            $table->string("info_path")->nullable()->unique();
            $table->double("anggaran",12,3)->default(0);
            $table->text("lokasi")->nullable();
            $table->text("instansi")->nullable();




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
        Schema::dropIfExists('tb_2020_2024_rpjmn_indikator');

    }
}
