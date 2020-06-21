<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FileKegiatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

          if(!Schema::hasTable('kegiatan_file')){ 
            Schema::create('kegiatan_file',function(Blueprint $table){
                $table->bigIncrements('id');
                $table->text('title');
                $table->text('path')->nullable();
                $table->text('extension')->nullable();
                $table->bigInteger('almbul_id')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('kegiatan_file');
        
    }
}
