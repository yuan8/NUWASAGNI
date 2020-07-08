<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use DB;
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
         $tahun_fokus=2019;
        static::init($tahun_fokus,$con);

        $schema='';
        $schema_public='';
        if($con=='pgsql'){
            $schema='bppspam.';
            $schema_public='public.';
        }else{
            $schema='';
            $schema_public='';
        }


        foreach (['bppspam_data_penilaian'=>'pdam_penilaian','bppspam_data_nilai'=>'pdam_hasil_nilai','bppspam_data_keterangan'=>'pdam_keterangan'] as $ke => $tb) {

            $dt=DB::connection('mybppspam')->table($tb)->get();
            foreach ($dt as $l=> $d) {
                $d=(array)$d;
                $d['pemda']=str_replace('00','',($d['pemda'].''));
                $tbx=str_replace('xxx', $d['tahun'], $ke);
                // if($d['pemda']=='1202'){
                //   dd($d);
                // }


                $pdam=DB::connection($con)->table($schema_public.'master_bppspam_pdam')
                ->where('kodepemda',$d['pemda'])->pluck('id')->first();




                if(!$pdam){


                    $dtpdam=(array)DB::connection('pgsql')->table('pdam')->where('kode_daerah',$d['pemda'])->first();
                    if($dtpdam){
                      unset($dtpdam['kode_daerah']);
                      unset($dtpdam['id']);
                      $dtpdam['kodepemda']=$d['pemda'];
                    }else{
                      $dtpdam=array(
                        'kodepemda'=>$d['pemda'],
                        'nama_pdam'=>$d['nama_pdam']
                      );
                    }

                    $pdam=DB::connection($con)->table($schema_public.'master_bppspam_pdam')
                     ->insertGetId(
                        $dtpdam
                     );

                }

                unset($d['nama_pdam']);
                unset($d['pemda']);
                $d['id_pdam']=$pdam;
                if(isset($d['periode_plan'])){
                  $pp=explode('-',$d['periode_plan']);
                  $d['periode_plan_awal']=isset($pp[0])?$pp[0]:null;
                  $d['periode_plan_ahir']=isset($pp[1])?$pp[1]:null;

                  unset($d['periode_plan']);

                }




                $dox=$d;


                foreach ($dox as $key => $value) {
                  // code...
                  $value=trim($value);
                  if(is_string($value)){
                    $value=str_replace(['(',')'],'',$value);
                    if(in_array($value,['-','','~','-0'])){
                      $d[$key]=null;
                    }else if(strpos($value,'%')!==false){
                        $d[$key]=(double)str_replace(',','.',(str_replace('%','',(str_replace('.','',$value)))));
                        $d[$key]=$d[$key]/100;
                    }else{
                      if(in_array($key,['kategori','keterangan'])){
                        $d[$key]=$value;
                      }else{
                        $value=str_replace(',','.',$value);
                        $d[$key]=(double)$value;
                      }

                    }
                  }
                }







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
            $schema='';
            $schema_public='';


        }
            if(!Schema::connection($con)->hasTable($schema_public.'master_daerah')){
                Schema::connection($con)->create($schema_public.'master_daerah',function(Blueprint $table)use($schema,$schema_public){



                    $table->string('id',4)->primary()->index();
                    $table->string('nama');
                    $table->string('kode_daerah_parent')->nullable();

                });

                $dt=DB::table('master_daerah')->select('id','nama','kode_daerah_parent')->get();
                $dt=json_encode($dt);
                $dt=json_decode($dt,true);

                DB::connection($con)->table($schema_public.'master_daerah')->insertOrIgnore($dt);

            }


            if(!Schema::connection($con)->hasTable($schema_public.'master_bppspam_pdam')){
                Schema::connection($con)->create($schema_public.'master_bppspam_pdam',function(Blueprint $table)use($schema,$schema_public){
                    $table->bigIncrements('id');
                    $table->string('kodepemda',4)->unique();
                    $table->string('kode_pdam')->nullable();
                    $table->string('nama_pdam');
                    $table->string('kordinat')->nullable();
                    $table->text('alamat')->nullable();
                    $table->text('open_hours')->nullable();
                    $table->string('no_telpon',20)->nullable();
                    $table->string('website',200)->nullable();
                    $table->text('url_image')->nullable();
                    $table->text('url_direct')->nullable();
                    $table->string('id_laporan_terahir')->nullable();
                    $table->string('id_laporan_terahir_2')->nullable();
                    $table->integer('period_tahun')->nullable();
                    $table->tinyInteger('period_bulan')->nullable();
                    $table->date('periode_laporan')->nullable();
                    $table->tinyInteger('kategori_pdam_kode')->nullable();
                    $table->tinyInteger('kategori_pdam_kode_self')->nullable();
                    $table->string('kategori_pdam')->nullable();
                    $table->string('kategori_pdam_self')->nullable();
                    $table->dateTime('updated_input_at')->nullable();
                    $table->tinyInteger('periode_tahun_bppspam')->nullable();
                    $table->string('kategori_pdam_bppspam')->nullable();
                    $table->timestamps();

                    $table->index(['kodepemda']);
                    $table->index(['kategori_pdam_bppspam']);
                    $table->index(['periode_tahun_bppspam']);

                    $table->foreign('kodepemda')->references('id')
                    ->on($schema_public.'master_daerah')
                    ->onDelete('cascade')->onUpdate('cascade');





                 });


            }



            if(!Schema::connection($con)->hasTable($schema.'bppspam_data_keterangan')){
                Schema::connection($con)->create($schema.'bppspam_data_keterangan',function(Blueprint $table)use($schema,$schema_public){
                    $table->bigIncrements('id');
                    $table->bigInteger('id_pdam')->unsigned();
                    $table->tinyInteger('kode_buku')->nullable();
                    $table->integer('kode_hal')->nullable();
                    $table->integer('tahun')->nullable();
                    $table->double('tarif',25,3)->nullable();
                    $table->double('hpp_nrw_std',25,3)->nullable();
                    $table->double('hpp_nrw_riil',25,3)->nullable();
                    $table->double('hpp',25,3)->nullable();
                    $table->double('pemenuhan_fcr',25,3)->nullable();
                    $table->double('total_aset_tetap',25,3)->nullable();
                    $table->double('total_aset',25,3)->nullable();
                    $table->double('hutang_lancar',25,3)->nullable();
                    $table->double('hutang_jangka_panjang',25,3)->nullable();
                    $table->double('total_equity',25,3)->nullable();
                    $table->double('ebitda_ebitda',25,3)->nullable();
                    $table->double('laba_bersih',25,3)->nullable();
                    $table->double('total_pendapatan',25,3)->nullable();
                    $table->double('total_beban_operasi',25,3)->nullable();
                    $table->double('rasio_per_sr',25,3)->nullable();
                    $table->double('biaya_bahan_energi',25,3)->nullable();
                    $table->double('biaya_energi',25,3)->nullable();
                    $table->double('biaya_pemeliharaan',25,3)->nullable();
                    $table->double('rasio_pegawai',25,3)->nullable();
                    $table->double('rasio_administrasi',25,3)->nullable();
                    $table->double('kapasitas',25,3)->nullable();
                    $table->double('volume',25,3)->nullable();
                    $table->double('panjang_transmisi',25,3)->nullable();
                    $table->double('panjang_distribusi',25,3)->nullable();
                    $table->double('volume_reservoar',25,3)->nullable();
                    $table->double('jumlah_unit',25,3)->nullable();
                    $table->double('jumlah_pdd_administrasi',25,3)->nullable();
                    $table->double('jumlah_di_pelayanan',25,3)->nullable();
                    $table->double('penduduk_terlayani',25,3)->nullable();
                    $table->double('jumlah_pegawai',25,3)->nullable();
                    $table->double('rata_pegawai_rp',25,3)->nullable();
                    $table->integer('periode_plan_awal')->nullable();
                    $table->integer('periode_plan_ahir')->nullable();

                    $table->unique(['id_pdam','tahun']);

                    $table->foreign('id_pdam')->references('id')
                    ->on($schema_public.'master_bppspam_pdam')
                    ->onDelete('cascade')->onUpdate('cascade');

                });

            }


                $tahun='data';
                if(!Schema::connection($con)->hasTable($schema.'bppspam_'.$tahun.'_nilai')){
                    Schema::connection($con)->create($schema.'bppspam_'.$tahun.'_nilai',function(Blueprint $table)use($tahun,$schema,$schema_public){
                        $table->bigIncrements('id');
                        $table->bigInteger('id_pdam')->unsigned();
                        $table->tinyInteger('kode_buku')->nullable();
                        $table->integer('kode_hal')->nullable();
                        $table->integer('tahun')->nullable();
                        $table->double('roe',25,3)->nullable();
                        $table->double('ratio_operasi',25,3)->nullable();
                        $table->double('ratio_kas',25,3)->nullable();
                        $table->double('efektivitas_penagihan',25,3)->nullable();
                        $table->double('solvabilitas',25,3)->nullable();
                        $table->double('kinerja_keuangan',25,3)->nullable();
                        $table->double('cakupan_pelayanan',25,3)->nullable();
                        $table->double('pertumbuhan_pelanggan',25,3)->nullable();
                        $table->double('tingkat_penyelesaian_pengaduan',25,3)->nullable();
                        $table->double('kualitas_air_pelanggan',25,3)->nullable();
                        $table->double('konsumsi_air_domestik',25,3)->nullable();
                        $table->double('kinerja_pelayanan',25,3)->nullable();
                        $table->double('efesiensi_produksi',25,3)->nullable();
                        $table->double('tingkat_kehilangan_air',25,3)->nullable();
                        $table->double('jam_operasi_layanan',25,3)->nullable();
                        $table->double('tekanan_sambungan_pelanggan',25,3)->nullable();
                        $table->double('penggantian_meter_air',25,3)->nullable();
                        $table->double('kinerja_operasi',25,3)->nullable();
                        $table->double('ratio_jumlah_pegawai',25,3)->nullable();
                        $table->double('ratio_diklat_pegawai',25,3)->nullable();
                        $table->double('biaya_diklat_thd_pegawai',25,3)->nullable();
                        $table->double('kinerja_sdm',25,3)->nullable();
                        $table->double('kinerja_total',25,3)->nullable();
                        $table->string('kategori',60)->nullable();

                        $table->unique(['id_pdam','tahun']);

                        $table->foreign('id_pdam')->references('id')
                        ->on($schema_public.'master_bppspam_pdam')
                        ->onDelete('cascade')->onUpdate('cascade');

                    });

                }

                if(!Schema::connection($con)->hasTable($schema.'bppspam_'.$tahun.'_penilaian')){
                    Schema::connection($con)->create($schema.'bppspam_'.$tahun.'_penilaian',function(Blueprint $table)use($tahun,$schema,$schema_public){

                        $table->bigIncrements('id');
                        $table->bigInteger('id_pdam')->unsigned();
                        $table->tinyInteger('kode_buku')->nullable();
                        $table->integer('kode_hal')->nullable();
                        $table->integer('tahun')->nullable();
                        $table->double('roe',25,3)->nullable();
                        $table->double('ratio_operasi',25,3)->nullable();
                        $table->double('ratio_kas',25,3)->nullable();
                        $table->double('efektivitas_penagihan',25,3)->nullable();
                        $table->double('solvabilitas',25,3)->nullable();
                        $table->double('kinerja_keuangan',25,3)->nullable();
                        $table->double('cakupan_pelayanan',25,3)->nullable();
                        $table->double('pertumbuhan_pelanggan',25,3)->nullable();
                        $table->double('tingkat_penyelesaian_pengaduan',25,3)->nullable();
                        $table->double('kualitas_air_pelanggan',25,3)->nullable();
                        $table->double('konsumsi_air_domestik',25,3)->nullable();
                        $table->double('kinerja_pelayanan',25,3)->nullable();
                        $table->double('efesiensi_produksi',25,3)->nullable();
                        $table->double('tingkat_kehilangan_air',25,3)->nullable();
                        $table->double('jam_operasi_layanan',25,3)->nullable();
                        $table->double('tekanan_sambungan_pelanggan',25,3)->nullable();
                        $table->double('penggantian_meter_air',25,3)->nullable();
                        $table->double('kinerja_operasi',25,3)->nullable();
                        $table->double('ratio_jumlah_pegawai',25,3)->nullable();
                        $table->double('ratio_diklat_pegawai',25,3)->nullable();
                        $table->double('biaya_diklat_thd_pegawai',25,3)->nullable();
                        $table->double('kinerja_sdm',25,3)->nullable();
                        $table->double('kinerja_total',25,3)->nullable();
                        $table->string('kategori',60)->nullable();
                        $table->mediumText('keterangan')->nullable();

                        $table->unique(['id_pdam','tahun']);



                        $table->foreign('id_pdam')->references('id')
                        ->on($schema_public.'master_bppspam_pdam')
                        ->onDelete('cascade')->onUpdate('cascade');

                    });



                }







    }
}
