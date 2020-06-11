<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Dokumen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         if(!Schema::hasTable('dokumen_kebijakan_daerah')){ 
            Schema::create('dokumen_kebijakan_daerah',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string("jenis",20);
            $table->string("kode_daerah",4)->unsigned();
            $table->text("nama");
            $table->tinyInteger("tahun");
            $table->tinyInteger("tahun_selesai");
            $table->text("path");
            $table->string("extension",10);
            $table->bigInteger("user_id")->unsigned();

            $table->unique('tahun','tahun_selesai','kode_daerah','jenis');

            $table->foreign('user_id')
              ->references('id')->on('users')
              ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('kode_daerah')
              ->references('id')->on('master_daerah')
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
        Schema::dropIfExists('dokumen_kebijakan_daerah');

    }
}
