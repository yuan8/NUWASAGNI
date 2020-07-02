<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BPPSPAMProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {


    }

    public static function storingdata($con){

        static::init(2019,'myfbppspam');

        $tahun_fokus=$tahun;
        $schema='';
        $schema_public='';
        if($con=='pgsql'){
            $schema='bppspam.';
            $schema_public='public.';
        }else{
            $schema='bppspam.';
            $schema_public='bppspam.';
        }


        foreach (['bppspam_xxx_penilaian'=>'bppspam_penilaian','bppspam_xxx_nilai'=>'bppspam_hasil_nilai'] as $key => $tb) {

            $dt=DB::connection($con)->table($tb)->get();

            foreach ($dt as  $d) {
                $d=(array)$d;
                $tbx=str_replace('xxx', $d['tahun'], $key);

                

                $pdam=DB::connection($con)->table($schema_public.'master_bppspam_pdam')
                ->where('kodepemda',$d['kodepemda'])->pluck('id')->first();

                if(!$pdam){

                     $pdam=DB::connection($con)->table($schema_public.'master_bppspam_pdam')
                     ->insertGetId([
                        'kodepemda'=>$d['kodepemda'],
                        'nama_pdam'=>$d['nama_pdam']
                     ]);

                }

                unset($d['nama_pdam']);
                unset($d['kodepemda']);
                $d['id_pdam']=$pdam;

                DB::connection($con)->table($schema.$tbx)->insertOrIgnore($d);
                
            }
        }





        //

    }



    static public function init($tahun_fokus=2020,$con='pgsql'){

        $schema='';
        $schema_public='';


        if($con=='pgsql'){
            $schema='bppspam.';
            $schema_public='public.';


        }else{
            $schema='bppspam.';
            $schema_public='bppspam.';


        }

            if(!Schema::hasTable($schema_public.'master_daerah')){
                Schema::connection($con)->create($schema_public.'master_daerah',function(Blueprint $table)use($tahun,$schema){

                    $table->string('id',4)->primary()->unique();
                    $table->string('nama')->unique();
                    $table->string('kode_daerah_parent')->nullable();

                });

                $dt=DB::table('master_daerah')->select('id','nama','kode_daerah_parent')->get();
                $dt=json_encode($dt);
                $dt=json_decode($dt,true);

                DB::connection($con)->table($schema_public.'master_daerah')->insertOrIgnore($dt);
                    
            }

            if(!Schema::hasTable($schema.'master_bppspam_pdam')){
                Schema::connction($con)->create($schema.'master_bppspam_pdam',function(Blueprint $table)use($tahun,$schema){
                    $table->bigIncrements('id');
                    $table->string('kodepemda',4)->default(0)->unique();
                    $table->string('nama_pdam',300)->default('');
                    $table->string('kordinat',300)->nullable();
                    $table->text('alamat')->nullable();
                    $table->text('open_hours')->nullable();
                    $table->string('no_telp',20)->nullable();
                    $table->string('website',200)->nullable();
                    $table->text('img_url')->nullable();
                    $table->text('url_direct')->nullable();
                    $table->string('id_laporan_terahir')->nullable();
                    $table->string('id_laporan_terahir_2')->nullable();
                    $table->tinyInterger('periode_tahun')->nullable();
                    $table->date('periode_laporan')->nullable();
                    $table->tinyInterger('ketegori_pdam_kode')->nullable();
                    $table->tinyInterger('ketegori_pdam_kode_self')->nullable();
                    $table->string('kategori_pdam')->nullable();
                    $table->string('kategori_pdam_self')->nullable();
                    $table->dateTime('updated_input_at')->nullable();
                    $table->tinyInterger('periode_tahun_bppspam')->nullable();
                    $table->string('kategori_pdam_bppspam')->nullable();
                    $table->timestamps();

                    $table->references('kodepemda')->on($schema_public.'master_daerah')
                    ->onDelete('cascade')->onUpdate('cascade');
                 });


            }



            for($tahun=$tahun_fokus;$tahun>=($tahun_fokus-3);$tahun--){
                if(!Schema::hasTable($schema.'bppsam_'.$tahun.'_nilai')){
                Schema::connction($con)->create($schema.'bppsam_'.$tahun.'_nilai',function(Blueprint $table)use($tahun,$schema){
                    $table->bigIncrements('id');
                    $table->bigInteger('id_pdam')->unsigned();
                    $table->tinyInterger('kode_buku')->default(0);
                    $table->integer('kode_hal')->default(0);
                    $table->tinyInterger('tahun')->default(0);
                    $table->double('roe',25,3)->default(0);
                    $table->double('ratio_operasi',25,3)->default(0);
                    $table->double('ratio_kas',25,3)->default(0);
                    $table->double('efektivitas_penagihan',25,3)->default(0);
                    $table->double('solvabilitas',25,3)->default(0);
                    $table->double('kinerja_keuangan',25,3)->default(0);
                    $table->double('cakupan_pelayanan',25,3)->default(0);
                    $table->double('pertumbuhan_pelanggan',25,3)->default(0);
                    $table->double('tingkat_penyelesaian_pengaduan',25,3)->default(0);
                    $table->double('kualitas_air_pelanggan',25,3)->default(0);
                    $table->double('konsumsi_air_domestik',25,3)->default(0);
                    $table->double('kinerja_pelayanan',25,3)->default(0);
                    $table->double('efesiensi_produksi',25,3)->default(0);
                    $table->double('tingkat_kehilangan_air',25,3)->default(0);
                    $table->double('jam_operasi_layanan',25,3)->default(0);
                    $table->double('tekanan_sambungan_pelanggan',25,3)->default(0);
                    $table->double('penggantian_meter_air',25,3)->default(0);
                    $table->double('kinerja_operasi',25,3)->default(0);
                    $table->double('ratio_jumlah_pegawai',25,3)->default(0);
                    $table->double('ratio_diklat_pegawai',25,3)->default(0);
                    $table->double('biaya_diklat_thd_pegawai',25,3)->default(0);
                    $table->double('kinerja_sdm',25,3)->default(0);
                    $table->double('kinerja_total',25,3)->default(0);
                    $table->string('kategori',60)->default(0);

                    $table->unique(['id_pdam','tahun']);

                    $table->foreign('id_pdam')->references('id')
                    ->on($schema_public.'master_bppspam_pdam')
                    ->onDelete('cascade')->onUpdate('cascade');

                });

                 if(!Schema::hasTable($schema.'bppsam_'.$tahun.'_penilaian')){
                Schema::connction($con)->create($schema.'bppsam_'.$tahun.'_penilaian',function(Blueprint $table)use($tahun,$schema){
                    $table->bigIncrements('id');
                    $table->bigInteger('id_pdam')->unsigned();
                    $table->tinyInterger('kode_buku')->default(0);
                    $table->integer('kode_hal')->default(0);
                    $table->tinyInterger('tahun')->default(0);
                    $table->double('roe')->default(0),
                    $table->double('ratio_operasi')->default(0),
                    $table->double('ratio_kas')->default(0),
                    $table->double('efektivitas_penagihan')->default(0),
                    $table->double('solvabilitas')->default(0),
                    $table->double('kinerja_keuangan')->default(0),
                    $table->double('cakupan_pelayanan')->default(0),
                    $table->double('pertumbuhan_pelanggan')->default(0),
                    $table->double('tingkat_penyelesaian_pengaduan')->default(0),
                    $table->double('kualitas_air_pelanggan')->default(0),
                    $table->double('konsumsi_air_domestik')->default(0),
                    $table->double('kinerja_pelayanan')->default(0),
                    $table->double('efesiensi_produksi')->default(0),
                    $table->double('tingkat_kehilangan_air')->default(0),
                    $table->double('jam_operasi_layanan')->default(0),
                    $table->double('tekanan_sambungan_pelanggan')->default(0),
                    $table->double('penggantian_meter_air')->default(0),
                    $table->double('kinerja_operasi')->default(0),
                    $table->double('ratio_jumlah_pegawai')->default(0),
                    $table->double('ratio_diklat_pegawai')->default(0),
                    $table->double('biaya_diklat_thd_pegawai')->default(0),
                    $table->double('kinerja_sdm')->default(0),
                    $table->double('kinerja_total')->default(0),
                    $table->double('kategori')->default(0),
                    $table->mediumText('keterangan')->nullable();

                    $table->unique(['id_pdam','tahun']);



                    $table->foreign('id_pdam')->references('id')
                    ->on($schema_public.'master_bppspam_pdam')
                    ->onDelete('cascade')->onUpdate('cascade');

                });

             

             }
            }

         

    }
}
