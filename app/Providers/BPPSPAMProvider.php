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
        // static::init($tahun_fokus,$con);

        $schema='';
        $schema_public='';
        if($con=='simspam'){
            $schema='bppspam.';
            $schema_public='public.';
        }else{
            $schema='';
            $schema_public='';
        }


        foreach (['bppspam_data_keterangan'=>'bppspam_data_keterangan','bppspam_data_penilaian'=>'bppspam_data_penilaian','bppspam_data_nilai'=>'bppspam_data_nilai'] as $ke => $tb) {

            $dt=DB::connection('mybppspam')->table($tb.' as i')
            ->select(
                DB::raw("(select nama_pdam from master_bppspam_pdam as p where p.id =i.id_pdam limit 1) as nama_pdam "),
                DB::raw("(select kodepemda from master_bppspam_pdam as p where p.id =i.id_pdam limit 1) as kode_daerah ")
                ,
                DB::raw("i.*")

            )
            ->get();


            $pdam=null;
             $xxx[]=[];
            foreach ($dt as $l=> $d) {
                $d=(array)$d;
              $pdam=DB::connection($con)->table($schema_public.'pdam')
                        ->where('kode_daerah',$d['kode_daerah'])->pluck('id')->first();
              
                // if($d['pemda']=='1202'){
                //   dd($d);
                // }


               
                if(!$pdam){

                   $dtpdam=array(
                        'kode_daerah'=>$d['kode_daerah'],
                        'nama_pdam'=>$d['nama_pdam']
                      );

                    $pdam=DB::connection($con)->table($schema_public.'pdam')
                     ->insertGetId(
                        $dtpdam
                     );

                }
                
                $d['id_pdam']=$pdam;
                unset($d['nama_pdam']);




                if(array_key_exists('periode_plan',$d)){
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







                DB::connection($con)->table($schema.$tb)->insertOrIgnore($d);

            }
        }





        //

    }



    static public function init($tahun_fokus=2020,$con='pgsql'){

        $schema='';
        $schema_public='';


        if($con=='simspam'){
            $schema='bppspam.';
            $schema_public='public.';


        }else{
            $schema='';
            $schema_public='';




        }
    

            

   
            if(!Schema::connection($con)->hasTable($schema.'bppspam_data_keterangan')){
                Schema::connection($con)->create($schema.'bppspam_data_keterangan',function(Blueprint $table)use($schema,$schema_public){
                    $table->bigIncrements('id');
                    $table->bigInteger('id_pdam')->unsigned();
                    $table->string('kode_daerah',4);
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

                    $table->unique(['kode_daerah','tahun']);

                    $table->foreign('id_pdam')->references('id')
                    ->on($schema_public.'pdam')
                    ->onDelete('cascade')->onUpdate('cascade');

                });

            }


                $tahun='data';
                if(!Schema::connection($con)->hasTable($schema.'bppspam_'.$tahun.'_nilai')){
                    Schema::connection($con)->create($schema.'bppspam_'.$tahun.'_nilai',function(Blueprint $table)use($tahun,$schema,$schema_public){
                        $table->bigIncrements('id');
                        $table->bigInteger('id_pdam')->unsigned();
                        $table->string('kode_daerah',4);
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

                        $table->unique(['kode_daerah','tahun']);

                        $table->foreign('id_pdam')->references('id')
                        ->on($schema_public.'pdam')
                        ->onDelete('cascade')->onUpdate('cascade');

                    });

                }

                if(!Schema::connection($con)->hasTable($schema.'bppspam_'.$tahun.'_penilaian')){
                    Schema::connection($con)->create($schema.'bppspam_'.$tahun.'_penilaian',function(Blueprint $table)use($tahun,$schema,$schema_public){

                        $table->bigIncrements('id');
                        $table->bigInteger('id_pdam')->unsigned();
                        $table->string('kode_daerah',4);
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

                        $table->unique(['kode_daerah','tahun']);



                        $table->foreign('id_pdam')->references('id')
                        ->on($schema_public.'pdam')
                        ->onDelete('cascade')->onUpdate('cascade');

                    });



                }



                if(Schema::connection($con)->hasTable($schema.'view_bppspam_penilaian_kategori')){

                    DB::connection($con)->statement("
                        CREATE OR REPLACE VIEW bppspam.view_bppspam_penilaian_kategori
                        AS SELECT n.id_pdam,
                            n.tahun,
                                CASE
                                    WHEN n.kinerja_total IS NULL OR n.kinerja_keuangan IS NULL OR n.kinerja_operasi IS NULL THEN NULL::integer
                                    WHEN n.kinerja_total >= k.bppspam_top AND n.kinerja_keuangan >= k.bppspam_finansial_top AND n.kinerja_operasi >= k.bppspam_oprasional_top AND n.cakupan_pelayanan >= k.bppspam_pelayanan_top THEN 5
                                    WHEN n.kinerja_total >= k.bppspam_top AND n.cakupan_pelayanan < k.bppspam_pelayanan_top OR n.cakupan_pelayanan > k.bppspam_pelayanan_top THEN 4
                                    WHEN n.kinerja_total >= k.bppspam_middle AND n.kinerja_total < k.bppspam_top AND n.cakupan_pelayanan >= k.bppspam_pelayanan_middle THEN 4
                                    WHEN n.kinerja_total >= k.bppspam_middle AND n.kinerja_keuangan > k.bppspam_finansial_bottom AND n.kinerja_operasi > k.bppspam_oprasional_bottom THEN 4
                                    WHEN n.kinerja_total >= k.bppspam_middle AND n.kinerja_total < k.bppspam_top AND n.cakupan_pelayanan > k.bppspam_pelayanan_middle THEN 3
                                    WHEN n.kinerja_total >= k.bppspam_bottom AND n.kinerja_total < k.bppspam_middle AND n.kinerja_keuangan >= k.bppspam_finansial_bottom AND n.kinerja_operasi >= k.bppspam_oprasional_bottom AND n.cakupan_pelayanan > k.bppspam_pelayanan_top THEN 3
                                    WHEN n.kinerja_total > k.bppspam_bottom AND n.kinerja_total < k.bppspam_middle AND n.kinerja_keuangan < k.bppspam_finansial_bottom AND n.kinerja_operasi < k.bppspam_oprasional_bottom THEN 2
                                    WHEN n.kinerja_total > k.bppspam_bottom AND n.kinerja_total < k.bppspam_middle AND n.cakupan_pelayanan < k.bppspam_pelayanan_top THEN 2
                                    WHEN n.kinerja_total <= k.bppspam_bottom AND n.cakupan_pelayanan > k.bppspam_pelayanan_top THEN 2
                                    WHEN n.kinerja_total <= k.bppspam_bottom AND n.cakupan_pelayanan < k.bppspam_pelayanan_top THEN 1
                                    ELSE NULL::integer
                                END AS kategori_pdam,
                            n.kategori AS kategori_bppspam
                           FROM bppspam.bppspam_data_penilaian n
                             LEFT JOIN bppspam.konst k ON 1 = 1

                    ");


                }







    }
}
