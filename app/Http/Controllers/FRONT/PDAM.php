<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
class PDAM extends Controller
{
    //

    public function index(){
    	$tahun=HP::fokus_tahun();
    	$data=DB::table('pdam as dt')
        ->leftJoin('audit_sat as sat','sat.id','=','dt.id_laporan_terahir')
    	->leftJoin('master_daerah as d','d.id','=','dt.kode_daerah')
    	->leftJoin('daerah_nuwas as nws',function($q)use ($tahun){
    		return $q->on('nws.kode_daerah','=','d.id')
    		->on('nws.tahun','=',DB::raw($tahun));
    	})
    	->select('dt.*','d.nama as nama_daerah',
            DB::raw("(select nama from master_daerah as p where p.id=(case when d.kode_daerah_parent is not null then d.kode_daerah_parent else d.id end)) as nama_provinsi"),
    		DB::raw("(case when nws.id is not null then true else false end) as target_nuwas"),
            'sat.keterangan'
    	)
        ->orderBy('kode_daerah','ASC')->get();

    	return view('front.pdam.index')->with('data',$data);




    }

    public function sat($id){
        $tahun=HP::fokus_tahun();

        $db=DB::table('pdam')
        ->where('kode_daerah',$id)
        ->first();

        if($db){
            $id=$db->id_laporan_terahir;
        }

        $data=DB::table('audit_sat as d')
            ->leftJoin('master_daerah as dae','dae.id','=','d.kode_daerah')
              ->select('d.*','dae.nama as nama_daerah',
                DB::raw("(select nama from master_daerah as p where p.id=case when dae.kode_daerah_parent is not null then dae.kode_daerah_parent else dae.id end limit 1  ) as nama_provinsi ")
            )

        ->where('d.id',$id)
        ->first();

        if($data){
             $pdam=DB::table('pdam as d')
             ->leftJoin('daerah_nuwas as n',function($q) use ($tahun){
                return $q->on('n.kode_daerah','=','d.kode_daerah')
                ->on("n.tahun",'=',DB::raw($tahun));
                    
             })
            ->where('d.kode_daerah',$data->kode_daerah)
            ->select(
                'd.*',
                DB::raw("(case when n.id is not null then true else false end) as daerah_nuwas"),
                DB::raw("REPLACE(n.jenis_bantuan,'@','') as  jenis_bantuan")
            )
               
            ->first();

            $prof_pdam=[];
           
            if(($pdam)and(isset($pdam->id_laporan_terahir_2))){
                $old_pdam=DB::table('audit_sat')
                ->where('id',$pdam->id_laporan_terahir_2)
                ->first();

                 $last_pdam=DB::table('audit_sat')
                ->where('id',$pdam->id_laporan_terahir)
                ->first();

                $prof_pdam['kinerja_trf']=$old_pdam->id;
                $prof_pdam['periode_laporan']=$old_pdam->periode_laporan;
                $prof_pdam['updated_input_at']=$old_pdam->updated_input_at;

                $prof_pdam['kinerja_trf']=HP::banil($old_pdam->sat_nilai_kinerja_ttl_dr_bppspam_nilai,$last_pdam->sat_nilai_kinerja_ttl_dr_bppspam_nilai);
                $prof_pdam['keuangan_trf']=HP::banil($old_pdam->sat_nilai_aspek_keuangan_dr_bppspam_nilai,$last_pdam->sat_nilai_aspek_keuangan_dr_bppspam_nilai);
                $prof_pdam['pelayanan_trf']=HP::banil($old_pdam->sat_nilai_aspek_pel_dr_bppspam_nilai,$last_pdam->sat_nilai_aspek_pel_dr_bppspam_nilai);
                $prof_pdam['oprasional_trf']=HP::banil($old_pdam->sat_nilai_aspek_operasional_dr_bppspam_nilai,$last_pdam->sat_nilai_aspek_operasional_dr_bppspam_nilai);
                $prof_pdam['sdm_trf']=HP::banil($old_pdam->sat_nilai_aspek_sdm_dr_bppspam_nilai,$last_pdam->sat_nilai_aspek_sdm_dr_bppspam_nilai);
                $prof_pdam['pertumbuhan_pelangan']=((($last_pdam->sat_jumlah_pelanggan_ttl_nilai-$old_pdam->sat_jumlah_pelanggan_ttl_nilai)/$last_pdam->sat_jumlah_pelanggan_ttl_nilai)*100);
                $prof_pdam['pertumbuhan_sambungan_rumah']=((($last_pdam->sat_jumlah_sam_rumah_tangga_nilai-$old_pdam->sat_jumlah_sam_rumah_tangga_nilai)/$last_pdam->sat_jumlah_sam_rumah_tangga_nilai)*100);

            }

            $pdam_else=[];


             $else=DB::table('audit_sat as d')
           
            ->where('d.kode_daerah',$data->kode_daerah)
            ->where('d.id','!=',$data->id)
            ->orderBy('d.periode_laporan','DESC')
            ->orderBy('d.updated_input_at','DESC')
            ->get();


            return view('front.pdam.laporan_sat')->with(
                [
                    'data'=>$data,
                    'pdam_else'=>$pdam_else,
                    'else'=>$else,
                    'pdam'=>$pdam,
                    'trafik'=>$prof_pdam,


                ]
            );

        }else{
            return abort('404');
        }






    }


    public function map(){

        $pdam_kondisi=DB::table('master_daerah as d')
        ->leftJoin('pdam','pdam.kode_daerah','=','d.id')
        ->select(
            'pdam.*',
            'd.nama as nama_daerah',
            'd.id as id_daerah'
        )
        ->orderBy('d.id','asc')
        ->get();


        $map_data=[
            'title'=>'KONDISI PDAM DAERAH',
            'series'=>[
                [
                    'name_layer'=>'PDAM KOTA / KABUPATEN',
                    'mapData_name'=>'ind_kota',
                    'name_data'=>'KOTA',

                    'legend'=>[
                        'cat'=>['TIDAK TERDAPAT DATA','SEHAT BERKELANJUTAN','SEHAT','POTENSI SEHAT','KURANG SEHAT','SAKIT'],
                        'color'=>['#fff','#32a852','#42f2f5','#2c56d4','#d4c62c','#f56342'],
                    ],

                    'data'=>[]

                ],
                [
                    'name_layer'=>'PDAM PROVINSI',
                    'mapData_name'=>'ind',
                    'name_data'=>'PROVINSI',
                    
                    'legend'=>[
                        'cat'=>['TIDAK TERDAPAT DATA','SEHAT BERKELANJUTAN','SEHAT','POTENSI SEHAT','KURANG SEHAT','SAKIT'],
                        'color'=>['#fff','#32a852','#42f2f5','#2c56d4','#d4c62c','#f56342'],
                    ],
                    'data'=>[],
                    


                ]
            ]
        ];

        foreach ($pdam_kondisi as $key => $d) {
            $d=(array)$d;
            $color='#fffff';
            switch ((float)$d['kategori_pdam_kode']) {
                case 5:
                $color='#32a852';
                    # code...
                    break;
                 case 4:
                $color='#42f2f5';
                    # code...
                    break;
                 case 3:
                $color='#2c56d4';
                    # code...
                    break;
                 case 2:
                $color='#d4c62c';
                    # code...
                    break;
                 case 1:
                $color='#f56342';
                    # code...
                    break;
                
                default:
                    # code...
                $color='#fff';
                    break;
            }

            if($d['id']){
                $tooltip= $d['nama_daerah'].'<br>'.$d['nama_pdam'].'<br> KONDISI :'.$d['kategori_pdam'];
            }else{
                $tooltip=$d['nama_daerah'].'<br>'.'TIDAK TERDAPAT DATA';
            }

            // ['id', 'nama', 'value','cat','link','color', 'tooltip']

            if((strlen($d['id_daerah'])<3) ){
                $map_data['series'][1]['data'][]=[
                    $d['id_daerah'],
                    $d['nama_daerah'],
                    $d['nama_pdam'],
                    $d['kategori_pdam'],
                    route('p.laporan_sat',['id'=>$d['id_daerah']]),
                    $color,
                   $tooltip
                ];

            }else{

                  $map_data['series'][0]['data'][]=[
                    $d['id_daerah'],
                    $d['nama_daerah'],
                    $d['nama_pdam'],
                    $d['kategori_pdam'],
                    route('p.laporan_sat',['id'=>$d['id_daerah']]),
                    $color,
                    $tooltip
                ];
            }


        }


      
        return view('output.map.themplate')
        ->with([
            'own_content'=>true,
            'id_map'=>'map_con_888',
            'current_data_db'=>$map_data,
            'file_path'=>'',
            'height'=>400
        ])->render();


        return $map_data;


    }
}
