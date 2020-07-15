<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pdam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
    Schema::create('pdam',function(Blueprint $table){

        $table->bigIncrements("id");
        $table->string("kode_pdam")->nullable();
        $table->string("nama_pdam")->nullable();
        $table->string("kode_daerah",4)->unique();
        $table->string("kordinat")->nullable();
        $table->text("alamat")->nullable();
        $table->text("open_hours")->nullable();
        $table->string("no_telpon")->nullable();
        $table->text("website")->nullable();
        $table->text("url_image")->nullable();
        $table->text("url_direct")->nullable();
        $table->text("id_laporan_terahir")->nullable()->unique();
        $table->text("id_laporan_terahir_2")->nullable()->unique();
        $table->integer("period_bulan")->nullable();
        $table->integer("period_tahun")->nullable();
        $table->dateTime("periode_laporan")->nullable();
        $table->float("kategori_pdam_kode")->nullable();
        $table->float("kategori_pdam_kode_self")->nullable();
        $table->string("kategori_pdam")->nullable();
        $table->string("kategori_pdam_self")->nullable();
        // $table->tinyInteger('nilai_kualitas_pdam_trafik')->nullable();
        // $table->tinyInteger('nilai_kualitas_pdam_trafik_self')->nullable();
        // $table->tinyInteger('nilai_aspek_keuangan_tafik')->nullable();
        // $table->tinyInteger('nilai_aspek_pelayanan_trafik')->nullable();
        // $table->tinyInteger('nilai_aspek_oprasional_trafik')->nullable();
        // $table->tinyInteger('nilai_aspek_sdm_tafik')->nullable();
        // $table->tinyInteger('nilai_kinerja_total_tafik')->nullable();
        $table->dateTime("updated_input_at")->nullable();
        $table->timestamps();
    });

    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pdam');
        //
    }
}
