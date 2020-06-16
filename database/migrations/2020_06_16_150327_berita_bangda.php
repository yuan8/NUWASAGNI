<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BeritaBangda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        if(!Schema::hasTable('berita_bangda')){ 
            Schema::create('berita_bangda',function(Blueprint $table){
                $table->bigIncrements('id');
                $table->text('title');
                $table->text('content')->nullable();
                $table->text('link')->nullable();
                $table->text('thumbnail_path')->nullable();
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
        Schema::dropIfExists('berita_bangda');

    }
}
