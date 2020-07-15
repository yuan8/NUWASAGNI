<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KordinasiKategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

          if(!Schema::hasTable('kordinasi_kategory')){ 
            Schema::create('kordinasi_kategory',function(Blueprint $table){
                $table->bigIncrements('id');
                $table->string('title',500);
                $table->integer('tahun');
                $table->date('pelaksanaan')->nullable();
                $table->mediumText('descripsi')->nullable();
                $table->bigInteger('id_user')->unsined();
                $table->timestamps();

                $table->unique(['title','tahun']);

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
        Schema::dropIfExists('kordinasi_kategory');

    }
}
