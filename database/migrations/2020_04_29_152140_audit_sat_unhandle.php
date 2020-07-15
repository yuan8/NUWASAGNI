<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuditSatUnhandle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_sat_unhandle',function(Blueprint $table){
            $table->bigIncrements("id");
            $table->string("document_id")->unique();
            $table->string("nama_pdam")->nullable();
            $table->string("kota")->nullable();
            $table->string("provinsi")->nullable();
            $table->string("kode_daerah")->nullable();
            $table->timestamps();
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('audit_sat_unhandle');

    }
}
