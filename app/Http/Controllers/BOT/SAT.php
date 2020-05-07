<?php

namespace App\Http\Controllers\BOT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Storage;
class SAT extends Controller
{
    //

    static function penilaian_nuwas($nilai){
         if(($nilai=='-')OR($nilai=='')){
             return null;
        }

        switch ((int)$nilai) {
            case 1:
                # code...
            $nilai='SAKIT';
                break;
            case 2:
                # code...
            $nilai='KURANG SEHAT';
                break;
            case 3:
                # code...
            $nilai='POTENSI SEHAT';
            break;
            case 4:
                # code...
            $nilai='SEHAT';
            break;

            case 5:
                # code...
            $nilai='SEHAT BERKELANJUTAN';
            break;
            
            default:

                # code...
            break;
        }

        return $nilai;
    }

    public static function jumlah_penduduk_terlayani($data){
        // G58*G66
        return $data['sat_jumlah_jiwa_per_keluarga_data_bps_nilai']*$data['sat_jumlah_pelanggan_ttl_nilai'];
        // G57

    }

    public function checking($id){
        $data=DB::table('audit_sat')->where('id',$id)->first();

        $data=(array)$data;
        $dom='';
        foreach ($data as $key => $value) {
            # code...
           

            if((strpos($key, 'sat_')!==false) AND (strpos(substr($key,-7,10), '_nilai')!==false) ){
                $key_an=substr($key, 0,-6);

                $dom.="<tr>
                        <td>".$key."</td>
                        <td class='text-center'>"."@buka @Vdata->".$key_an."_tahun"." @tutup</td>
                        <td class='text-right'>
                            @buka number_format(@Vdata->".$key_an."_nilai".",2,'.',',')@tutup
                        </td>
                        <td> @buka (@Vdata->".$key_an."_sataun".")@tutup</td>
                        <td>@buka (@Vdata->".$key_an."_key".")@tutup</td>
                </tr>";

            }

        }
        return array('ni'=>$dom);


         // G18
        $bppspam_top=3.5;
        // G19
        $bppspam_mid=2.8;
        // G20
        $bppspam_bot=2.2;

        
        // G23
        $bppspam_f_top=1;
        // G24
        $bppspam_f_bot=0.7;


        // G27
        $bppspam_o_top=1;
        //G28
        $bppspam_o_bot=0.7;



        // G62
        $bppspam_p_top=0.75;
        // G63
        $bppspam_p_mid=0.5;
        // G64
        $bppspam_p_bot=0.25;


        return [
            'dokumen'=>[
                'id'=>$data['id'],
                'nama_pdam'=>$data['nama_pdam'],
                'kategi_pdam_sistem_sat'=>$data['kategori_pdam'],
                'kategi_pdam_sistem_kami'=>$data['kategori_pdam_self'],
                'periode_laporan'=>Carbon::parse($data['periode_laporan'])->format('F Y'),
                'updated_input_at'=>Carbon::parse($data['updated_input_at'])->format('d F Y'),
            ],
            'kost'=>[
                'G18_KAT_PDAM_KIN_TOP'=>$bppspam_top,
                'G19_KAT_PDAM_KIN_MID'=>$bppspam_mid,
                'G19_KAT_PDAM_KIN_BOT'=>$bppspam_bot,
                'G23_KAT_PDAM_KEU_TOP'=>$bppspam_f_top,
                'G24_KAT_PDAM_KEU_BOT'=>$bppspam_f_bot,
                'G27_KAT_PDAM_OP_TOP'=>$bppspam_o_top,
                'G28_KAT_PDAM_OP_BOT'=>$bppspam_o_bot,
                'G62_PEL_PDAM_PEL_TOP'=>$bppspam_p_top,
                'G63_PEL_PDAM_PEL_MID'=>$bppspam_p_mid,
                'G64_PEL_PDAM_PEL_BOT'=>$bppspam_p_bot,

            ],
            'data'=>[
                'G30_TTL_KIN'=>$data['sat_nilai_kinerja_ttl_dr_bppspam_nilai'],
                'G31_KEU'=>$data['sat_nilai_aspek_keuangan_dr_bppspam_nilai'],
                'G32_OP'=>$data['sat_nilai_aspek_operasional_dr_bppspam_nilai'],
                'INPUTG17_PEL'=>$data['sat_nilai_aspek_pel_dr_bppspam_nilai'],
                'INPUTG919_SDM'=>$data['sat_nilai_aspek_sdm_dr_bppspam_nilai'],

                'G56_JUM_PD_WIL_PEL_TEKNIS'=>$data['sat_jumlah_pd_di_wilayah_pel_teknis_nilai'],
                'G57_jumlah_penduduk_terlayani_658_X_G66'=>static::jumlah_penduduk_terlayani($data),
                'G58_JUM_JIWA_PER_KLUARGA'=>$data['sat_jumlah_jiwa_per_keluarga_data_bps_nilai'],
                'G66_JUM_PEL_TERLAYANI'=>$data['sat_jumlah_pelanggan_ttl_nilai'],
                'G59_CAKUPAN_PEL_DAERAH_PEL_G57_/_G56'=>static::cakupan_pelayanan_di_daerah_pelayanan($data),
            ],
            'rumus'=>[
                [
                    'IF(OR(G30="",G31="",G32="")',
                    ''
                ],
                [
                    'IF(AND(G30>=G18,G31>=G23,G32>=G27,G59>G62)',
                    'SEHAT BERKELANJUTAN'

                ],
                [
                    'IF(AND(G30>=G18,OR(G59<G62,G59>G62))',
                    'SEHAT'

                ]

            ]
            
        ];

    }

    public static function cakupan_pelayanan_di_daerah_pelayanan($data){

        // G57/G56
        return static::jumlah_penduduk_terlayani($data)/$data['sat_jumlah_pd_di_wilayah_pel_teknis_nilai'];
        // G59
    }

    public static function  penilaian_nuwas_cal($data){
        //G59
        $cakupan_pelayanan_di_daerah_pelayanan=static::cakupan_pelayanan_di_daerah_pelayanan($data);

        // G18
        $bppspam_top=3.5;
        // G19
        $bppspam_mid=2.8;
        // G20
        $bppspam_bot=2.2;

        
        // G23
        $bppspam_f_top=1;
        // G24
        $bppspam_f_bot=0.7;


        // G27
        $bppspam_o_top=1;
        //G28
        $bppspam_o_bot=0.7;



        // G62
        $bppspam_p_top=0.75;
        // G63
        $bppspam_p_mid=0.5;
        // G64
        $bppspam_p_bot=0.25;

        // SEHAT BERKELANJTAN C37
        // SEHAT C38
        // POTENSI SEHAT C39
        // KURANG SEHAT C40
        // SAKIT C41


        // IF(OR(G30="",G31="",G32="")
        if((empty($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']))OR (empty($data['sat_nilai_aspek_keuangan_dr_bppspam_nilai'])) OR (empty($data['sat_nilai_aspek_operasional_dr_bppspam_nilai']) )){
            return null;

        // IF(AND(G30>=G18,G31>=G23,G32>=G27,G59>G62)
        }else if(($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']>=$bppspam_top) AND ($data['sat_nilai_aspek_keuangan_dr_bppspam_nilai']>=$bppspam_f_top) AND ($data['sat_nilai_aspek_operasional_dr_bppspam_nilai']>=$bppspam_o_top) AND ($cakupan_pelayanan_di_daerah_pelayanan>$bppspam_p_top) ){
            return 5;

            //C37 sehat berkelanjutan

        // IF(AND(G30>=G18,OR(G59<G62,G59>G62))
        }else if(($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']>=$bppspam_top) AND (($cakupan_pelayanan_di_daerah_pelayanan<$bppspam_p_top)OR($cakupan_pelayanan_di_daerah_pelayanan>$bppspam_p_top))){
            return 4;
            // C38  sehat

        // IF(AND(G30>=G19,G30<G18,G59>=G63)
        }else if(($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']>=$bppspam_mid) AND ($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']<$bppspam_top) AND ($cakupan_pelayanan_di_daerah_pelayanan>=$bppspam_p_mid)){

            return 4;
            // C38 sehat

        // IF(AND(G30>=G19,GH31>G24,G32>G28) SAT
        // IF(AND(G30>=G19,G31>G24,G32>G28)  SELF CALL
        }else if(($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']>=$bppspam_mid) AND ($data['sat_nilai_aspek_keuangan_dr_bppspam_nilai']>$bppspam_f_bot) AND ($data['sat_nilai_aspek_operasional_dr_bppspam_nilai']>$bppspam_o_bot)){
            return 4;
            //C38  sehat
        
        // IF(AND(G30>=G19,G30<G18,G59<G63)
        }else if(($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']>=$bppspam_mid) AND ($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']<$bppspam_top) AND ($cakupan_pelayanan_di_daerah_pelayanan<$bppspam_p_mid)){
            return 3;
            //C39 potensi sehat

        // IF(AND(G30>=G20,G30<G19,G31>=G24,G32>=G28,G59>G62)
        }else if(($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']>=$bppspam_bot) AND ($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']<$bppspam_mid) AND ($data['sat_nilai_aspek_keuangan_dr_bppspam_nilai']>=$bppspam_f_bot) AND ($data['sat_nilai_aspek_operasional_dr_bppspam_nilai']>=$bppspam_o_bot) AND ($cakupan_pelayanan_di_daerah_pelayanan>$bppspam_p_top)){
            return 3;
            //C39 potensi sehat

            // IF(AND(G30>G20,G30<G19,OR(G31<G24,G32<G28)
        }else if(($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']>$bppspam_bot) AND ($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']<$bppspam_mid) AND (($data['sat_nilai_aspek_keuangan_dr_bppspam_nilai']<$bppspam_f_bot)OR($data['sat_nilai_aspek_operasional_dr_bppspam_nilai']<$bppspam_o_bot))){
             return 2;
            //C40 kurang sehat
        
             // IF(AND(G30>G20,G30<G19,G59<G62)
        }else if(($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']>$bppspam_bot) AND ($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']<$bppspam_mid) AND ($cakupan_pelayanan_di_daerah_pelayanan<$bppspam_p_top) ){
             return 2;
            //C40 kurang sehat

             // IF(AND(G30<=G20,G59>G62)
        }else if(($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']<=$bppspam_bot) AND ($cakupan_pelayanan_di_daerah_pelayanan>$bppspam_p_top) ){
             return 2;
            //C40 kurang sehat

             // IF(AND(G30<=G20,G59<G62)
         }else if(($data['sat_nilai_kinerja_ttl_dr_bppspam_nilai']<=$bppspam_bot) AND ($cakupan_pelayanan_di_daerah_pelayanan<$bppspam_p_top)){
              return 1;
            //C41 sakit
         }else{
            return null;
         }


    }




    public function store_data($id_doc=null){

        //     $data=DB::table('pdam')->get();
        // foreach ($data as $key => $value) {
        //         $pd=DB::table('pdam_profile')->where('kode_daerah',$value->kode_daerah)->first();
        //         if($pd){
        //             DB::table('pdam')->where('id',$value->id)
        //             ->update(
        //                 [
        //                     'alamat'=>$pd->alamat,
        //                     'no_telpon'=>$pd->no_telp,
        //                     'kordinat'=>$pd->kordinat,
        //                     'open_hours'=>$pd->open_hours,
        //                     'website'=>$pd->website,
        //                     'url_image'=>$pd->url_img,
        //                     'url_direct'=>$pd->url_direct,



        //                 ]
        //             );


        //         }

        // }

        // dd('wes');



    	$path_data=app_path('NODEJS/SAT/storage/file/sat_data').'';
    
        if($id_doc){
            $path_list=[$id_doc];
        }else{
            $path_list=scandir($path_data);
            unset($path_list[0]);
            unset($path_list[1]);
        }


    	foreach ($path_list as $key => $value) {
            $unhandle=[];

    		$data=file_get_contents($path_data.'/'.$value);
    		$data=json_decode($data,true);
            $kode_pdam=trim(strtoupper(str_replace(',', '', (str_replace(' ', '', $data['sat_nama_pdam'])))),true);

            $kota=null;
            if(!empty($data['kode_daerah'])){
                $kota=$data['kode_daerah'];
            }else{
                $prov=DB::table('master_daerah')->where('kode_daerah_parent',null)
                ->whereRaw("REPLACE(nama,' ','') ilike '%".str_replace(' ','',$data['provinsi'])."%'")
                ->first();

                if($prov){
                    $kota=DB::table('master_daerah')->where('kode_daerah_parent',$prov->id)
                    ->whereRaw("REPLACE(nama,' ','') ilike '%".str_replace(' ','',$data['kota'])."%'")->first();
                    if($kota){
                        $kota=$kota->id;
                    }
                }

                if(!$kota){
                    $kota=DB::table('master_daerah')->where('kode_daerah_parent',null)
                    ->whereRaw("REPLACE(nama,' ','') ilike '%".str_replace(' ','',$data['kota'])."%'")
                    ->first();
                    if($kota){
                        $kota=$kota->id;

                    }else{
                        // check pakek kode pdam
                    }

                }



            }
            // end if

            if(!$kota){
                $h=DB::table('audit_sat_unhandle')->where('document_id',$data['id'])
                ->where('kode_daerah','!=',null)
                ->first();

                if($h){
                    $kota=$h->kode_daerah;
                }
            }

            if($kota){
                $periode_laporan=(Carbon::create($data['tahun_laporan'].'-'.(strlen(($data['bulan_berlaku_ahir'].''))<2?('0'.$data['bulan_berlaku_ahir']):$data['bulan_berlaku_ahir']).'-15'))->startOfMonth();
              
                $penilaian_nuwas_cal=static::penilaian_nuwas_cal($data);

                // dd($penilaian_nuwas_cal);

                $profile_pdam=array(
                    'kode_pdam'=>$kode_pdam,
                    'nama_pdam'=>$data['nama_pdam'],
                    'kode_daerah'=>$kota,
                    'kordinat'=>$data['kordinat'],
                    'alamat'=>null,
                    'open_hours'=>$data['open_hours'],
                    'no_telpon'=>$data['no_telpon'],
                    'website'=>$data['website'],
                    'url_image'=>$data['url_image'],
                    'url_direct'=>$data['url_direct'],
                    'id_laporan_terahir'=>$data['id'],
                    'id_laporan_terahir_2'=>null,
                    'periode_laporan'=>$periode_laporan,
                    'period_bulan'=>$data['bulan_berlaku_ahir'],
                    'period_tahun'=>$data['tahun_laporan'],
                    'kategori_pdam_kode'=>$data['kategori_pdam'],
                    'kategori_pdam_kode_self'=>$penilaian_nuwas_cal,
                    'kategori_pdam_self'=>static::penilaian_nuwas($penilaian_nuwas_cal),
                    'kategori_pdam'=>static::penilaian_nuwas($data['kategori_pdam']),
                    'updated_input_at'=>Carbon::createFromFormat('m/d/Y',$data['sat_tanggal_masukan'])->endOfDay(),
                    'updated_at'=>Carbon::now(),
                    // 'nilai_kualitas_pdam_trafik'=>0,
                    // 'nilai_kualitas_pdam_trafik_self'=>0,
                    // 'nilai_aspek_keuangan_tafik'=>0,
                    // 'nilai_aspek_pelayanan_trafik'=>0,
                    // 'nilai_aspek_oprasional_trafik'=>0,
                    // 'nilai_aspek_sdm_tafik'=>0,
                    // 'nilai_kinerja_total_tafik'=>0,
                );



                $data_laporan=[
                    'id'=>$data['id'],
                    'kode_pdam'=>$kode_pdam,
                    'nama_pdam'=>$data['nama_pdam'],
                    'kode_daerah'=>$kota,
                    'kategori_pdam_kode'=>$data['kategori_pdam'],
                    'kategori_pdam'=>static::penilaian_nuwas($data['kategori_pdam']),
                    'kategori_pdam_kode_self'=>$penilaian_nuwas_cal,
                    'kategori_pdam_self'=>static::penilaian_nuwas($penilaian_nuwas_cal),
                    'period_bulan_awal'=>$data['bulan_berlaku_awal'],
                    'period_bulan_ahir'=>$data['bulan_berlaku_ahir'],
                    'periode_laporan'=>$periode_laporan,
                    'period_tahun'=>$data['tahun_laporan'],
                    'updated_input_at'=>Carbon::createFromFormat('m/d/Y',$data['sat_tanggal_masukan'])->endOfDay(),
                    'keterangan'=>$data['keterangan'],
                    'verifikasi_provinsi'=>$data['verifikasi_provinsi'],
                    'verifikasi_regional'=>$data['verifikasi_regional'],
                    'verifikasi_satker'=>$data['verifikasi_satker'],  
                    'updated_at'=>Carbon::now()
                ];

                $table=[];

                foreach ($data as  $key_sat=>$sat) {
                    $key1=substr($key_sat,0,10);
                    if(strpos($key1, 'sat_')!==false){
                        $data_laporan[$key_sat]=$sat;
                    }

                }

                unset($data_laporan['sat_nama_pdam']);
                unset($data_laporan['sat_nama_kota_kabupaten']);
                unset($data_laporan['sat_tanggal_masukan']);
                unset($data_laporan['sat_per_lap_yg_digunakan']);
                unset($data_laporan['sat_keterangan_umum']);



                $aprove=0;
                $update_lap=0;

                $laporan_detail_existing=DB::table('audit_sat')
                ->where('id',$data['id'])
                ->where('kode_daerah',$data_laporan['kode_daerah'])
                ->where('periode_laporan',$data_laporan['periode_laporan'])
                ->orderBy('periode_laporan','DESC')
                ->first();

                if(!$laporan_detail_existing){
                   $aprove=1;
                }else{
                    $lap_exist_date=Carbon::parse($laporan_detail_existing->updated_input_at)->format('d/m/Y');
                    $lap_date=Carbon::parse($data_laporan['updated_input_at'])->format('d/m/Y');

                    if($lap_exist_date < $lap_date ){
                        $aprove=1;
                        $update_lap=1;
                    }else{

                    }

                }


                if($aprove){

                    $pdam_existing=DB::table('pdam')
                    ->where('kode_daerah',$data_laporan['kode_daerah'])
                    ->first();

                    if($pdam_existing){
                        if(Carbon::create($pdam_existing->periode_laporan) < $data_laporan['periode_laporan'] ){
                            unset($profile_pdam['kordinat']);
                            unset($profile_pdam['alamat']);
                            unset($profile_pdam['open_hours']);
                            unset( $profile_pdam['website']);
                            unset($profile_pdam['url_image']);
                            unset($profile_pdam['url_direct']);
                            unset($profile_pdam['no_telpon']);

                            $profile_pdam['id_laporan_terahir_2']=$pdam_existing->id_laporan_terahir;

                            // $profile_pdam['nilai_kualitas_pdam_trafik']=static::banding_nilai($pdam_existing->nilai_kualitas_pdam_trafik,$data_laporan['kategori_pdam_kode']);
                            
                            // $profile_pdam['nilai_kualitas_pdam_trafik_self']=static::banding_nilai($pdam_existing->nilai_kualitas_pdam_trafik_self,$data_laporan['kategori_pdam_kode_self']);
                            
                            // $profile_pdam['nilai_aspek_keuangan_tafik']=static::banding_nilai($pdam_existing->nilai_aspek_keuangan_tafik,$data_laporan['sat_nilai_aspek_keuangan_dr_bppspam_nilai']);
                            
                            // $profile_pdam['nilai_aspek_pelayanan_trafik']=static::banding_nilai($pdam_existing->nilai_aspek_pelayanan_trafik,$data_laporan['sat_nilai_aspek_pel_dr_bppspam_nilai']);
                            
                            // $profile_pdam['nilai_aspek_oprasional_trafik']=static::banding_nilai($pdam_existing->nilai_aspek_oprasional_trafik,$data_laporan['sat_nilai_aspek_operasional_dr_bppspam_nilai']);
                            
                            // $profile_pdam['nilai_aspek_sdm_tafik']=static::banding_nilai($pdam_existing->nilai_aspek_sdm_tafik,$data_laporan['sat_nilai_aspek_sdm_dr_bppspam_nilai']);
                            
                            // $profile_pdam['nilai_kinerja_total_tafik']=static::banding_nilai($pdam_existing->nilai_kinerja_total_tafik,$data_laporan['sat_nilai_kinerja_ttl_dr_bppspam_nilai']);



                            DB::table('pdam')->where('kode_daerah',$data_laporan['kode_daerah'])
                            ->update($profile_pdam);
                        }

                    }else{
                            $profile_pdam['created_at']=Carbon::now();
                            DB::table('pdam')->insert($profile_pdam);
                    }

                    if($update_lap){
                        $dt_lap=$data_laporan;
                        unset($dt_lap['id']);
                        DB::table('audit_sat')->where('id',$laporan_detail_existing->id)
                        ->where('kode_daerah',$laporan_detail_existing->kode_daerah)
                        ->update($dt_lap);

                        if($pdam_existing->id_laporan_terahir==$data_laporan['id']){
                            unset($profile_pdam['kordinat']);
                            unset($profile_pdam['alamat']);
                            unset($profile_pdam['open_hours']);
                            unset( $profile_pdam['website']);
                            unset($profile_pdam['url_image']);
                            unset($profile_pdam['url_direct']);
                            unset($profile_pdam['no_telpon']);

                             // $profile_pdam['id_laporan_terahir_2']=$pdam_existing->id_laporan_terahir;

                            // $pdam_existing_2=DB::table('pdam as  p')
                            // ->where('')

                            // $profile_pdam['nilai_kualitas_pdam_trafik']=static::banding_nilai($pdam_existing->nilai_kualitas_pdam_trafik,$data_laporan['kategori_pdam_kode']);
                            
                            // $profile_pdam['nilai_kualitas_pdam_trafik_self']=static::banding_nilai($pdam_existing->nilai_kualitas_pdam_trafik_self,$data_laporan['kategori_pdam_kode_self']);
                            
                            // $profile_pdam['nilai_aspek_keuangan_tafik']=static::banding_nilai($pdam_existing->nilai_aspek_keuangan_tafik,$data_laporan['sat_nilai_aspek_keuangan_dr_bppspam_nilai']);
                            
                            // $profile_pdam['nilai_aspek_pelayanan_trafik']=static::banding_nilai($pdam_existing->nilai_aspek_pelayanan_trafik,$data_laporan['sat_nilai_aspek_pel_dr_bppspam_nilai']);
                            
                            // $profile_pdam['nilai_aspek_oprasional_trafik']=static::banding_nilai($pdam_existing->nilai_aspek_oprasional_trafik,$data_laporan['sat_nilai_aspek_operasional_dr_bppspam_nilai']);
                            
                            // $profile_pdam['nilai_aspek_sdm_tafik']=static::banding_nilai($pdam_existing->nilai_aspek_sdm_tafik,$data_laporan['sat_nilai_aspek_sdm_dr_bppspam_nilai']);
                            
                            // $profile_pdam['nilai_kinerja_total_tafik']=static::banding_nilai($pdam_existing->nilai_kinerja_total_tafik,$data_laporan['sat_nilai_kinerja_ttl_dr_bppspam_nilai']);

                            
                            DB::table('pdam')->where('kode_daerah',$data_laporan['kode_daerah'])
                            ->update($profile_pdam);
                        }

                    }else{
                        $data_laporan['created_at']=Carbon::now();
                         DB::table('audit_sat')->insert($data_laporan);
                    }


                }




                // die;

               
            }else{

                $unhen=array(
                    'document_id'=>$data['id'],
                    'kota'=>$data['kota'],
                    'provinsi'=>$data['provinsi'],
                    'kode_daerah'=>$data['kode_daerah'],
                    'nama_pdam'=>$data['nama_pdam'],
                );

                $check_unhend=DB::table('audit_sat_unhandle')->where('document_id',$unhen['document_id'])
                ->first();

                if(!$check_unhend){
                    if(!DB::table('audit_sat')->where('id',$unhen['document_id'])->first()){
                        DB::table('audit_sat_unhandle')->insert($unhen);
                    }

                    $unhandle[]=$unhandle;
                    Storage::disk('node')->put('SAT/file/data_unhandle.json',json_encode($unhandle,JSON_PRETTY_PRINT));


                }



            }
            // end else



           


    	}




    }
    // end methos
}
