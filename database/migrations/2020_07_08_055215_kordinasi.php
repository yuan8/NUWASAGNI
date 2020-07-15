<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Kordinasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

          if(!Schema::hasTable('kordinasi')){ 
            Schema::create('kordinasi',function(Blueprint $table){
                $table->bigIncrements('id');
                $table->bigInteger('id_kategory')->unsined();
                $table->string('kode_daerah')->unsined();
                $table->date('pelaksanaan')->nullable();
                $table->string('path',300)->nullable();
                $table->mediumText('note')->nullable();
                $table->bigInteger('id_user')->unsined();
                $table->timestamps();

                $table->unique(['id_kategory','kode_daerah']);


                 $table->foreign('id_kategory')
                  ->references('id')->on('kordinasi_kategory')
                  ->onDelete('cascade')->onUpdate('cascade');

                 $table->foreign('kode_daerah')
                  ->references('id')->on('master_daerah')
                  ->onDelete('cascade')->onUpdate('cascade');

                $table->foreign('id_user')
                  ->references('id')->on('users')
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
        Schema::dropIfExists('kordinasi');

    }
}
