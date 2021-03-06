<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use HP;
use DB;
use Carbon\Carbon;
use Auth;
class NUWAS_PROJECT extends Controller
{
    //

    public function index(){
        $tahun=HP::fokus_tahun();

        $output=DB::table('output_publish as p')
        ->leftJoin('users as u','u.id','=','p.user_id')
        ->select('p.*','u.name as nama_user')
        ->where('p.tahun',$tahun)
        ->orderBy('p.updated_at','DESC')->limit(11)->get();

        $data_target_nuwas=DB::table('daerah_nuwas as n')
        ->where('n.tahun',$tahun)->get()
        ->pluck('kode_daerah');

        $pdam_rekap=(array)DB::table('pdam')
        ->leftJoin('audit_sat as pen','pen.id','=','pdam.id_laporan_terahir' )
        ->select(DB::raw("(CASE WHEN max(pen.kategori_pdam) is not null THEN max(pen.kategori_pdam) else'TIDAK MEMILIKI KATEGORI' end ) as kategori_pdam"),'pen.kategori_pdam_kode',
            DB::raw("count(distinct(pdam.id)) as jumlah_pdam")
        )
        ->groupBy('pen.kategori_pdam_kode')
        ->orderBy('pen.kategori_pdam_kode','DESC')
        ->whereIn('pdam.kode_daerah',$data_target_nuwas)
        ->get()->toArray();

        $pd_a=[];
        foreach ($pdam_rekap as $key => $pd) {
            $pd_a[$pd->kategori_pdam_kode?$pd->kategori_pdam_kode:0]=$pd;
        }
        $pdam_rekap=$pd_a;



        $album=DB::table('album')
        ->where('created_at','>=',Carbon::parse($tahun.'-'.'01-01')->startOfDay())
        ->where('created_at','<=',Carbon::parse($tahun.'-'.'12-15')->endOfMonth())
        ->orderBy('id','DESC')
        ->limit(10)->get();





        $rkpd_final=DB::connection('sinkron_prokeg')->table('tb_'.$tahun.'_kegiatan')
        ->whereIn('kode_daerah',$data_target_nuwas)
        ->where('status',5)
        ->where('id_urusan',3)
        ->where('id_sub_urusan',12)
        ->select(DB::raw("count(distinct(kode_daerah)) as jumlah_daerah"))
        ->pluck('jumlah_daerah')->first();


        $data_target_nuwas=count($data_target_nuwas);



        $public_world_bank=scandir(storage_path('app/public/publikasi_world_bank_air_bersih'));
        unset($public_world_bank[0]);
        unset($public_world_bank[1]);
        $public_world_bank=array_values($public_world_bank);

        foreach ($public_world_bank as $key => $value) {
            # code...
            $public_world_bank[$key]=array(
                'nama'=>$value,
                'url'=>'storage/publikasi_world_bank_air_bersih/'.$value
            );
        }


        return view('front.nuwas_project.index')->with(
            [
                'target_nuwas'=>$data_target_nuwas,
                'public_world_bank'=>$public_world_bank,
                'output'=>$output,
                'pdam_rekap'=>$pdam_rekap,
                'album'=>$album,
                'rkpd_final'=>$rkpd_final
            ]
        );
    }


    public function prokeg_index(){
         $tahun=HP::fokus_tahun();

        $target_nuwas=DB::table('daerah_nuwas')->where('tahun',$tahun)->get()->pluck('kode_daerah');
        $data=DB::connection('sinkron_prokeg')->table('public.master_daerah as d')
        ->select(
            'd.*',
            DB::raw("null as target_nuwas"),
             DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=d.id) as nama_daerah"),
            DB::raw("(select count(*) from prokeg.tb_".$tahun."_kegiatan  as k where k.kode_daerah=d.id and k.id_urusan=3 and k.id_sub_urusan=12 and k.status=5) as jumlah_kegiatan")
        )
        ->whereIn('d.id',$target_nuwas)
        ->orderBy('id','ASC')->get();

        $provinsi=[];



        foreach ($data as $key => $d) {
            $target_nuwas=DB::table('daerah_nuwas')->where('tahun',$tahun)->where('kode_daerah',$d->id)->first();
            if($target_nuwas){
                $d->target_nuwas=$target_nuwas;
            }

        }

        $provinsi=DB::table('daerah_nuwas')->select(DB::RAW("left(kode_daerah,2) as id_pro"))->where('tahun',$tahun)->get()->pluck('id_pro')->toArray();
        $provinsi=array_unique((array)$provinsi);

        $provinsi=DB::table('master_daerah')->whereIn('id',$provinsi)->get();

        return view('front.nuwas_project.prokeg.index')->with('data',$data)->with('provinsi',$provinsi);
    }

    public function prokeg_detail($id){

        $tahun=HP::fokus_tahun();
        $rpjmn_ind_table=HP::get_rpjmn_table('indikator');
        $rpjmn_table=HP::get_rpjmn_table();
        $dekade=HP::get_tahun_rpjmn();

        $daerah=DB::table('master_daerah as d')->select(
             DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=d.id) as nama_daerah")
        )->where('id',$id)->first();

        $data=DB::connection('sinkron_prokeg')->table("prokeg.tb_".$tahun."_kegiatan  as k")
        ->leftJoin("prokeg.tb_".$tahun."_program as p",'p.id','=','k.id_program')
        ->leftJoin("prokeg.tb_".$tahun."_ind_program as indp",'indp.id_program','=','p.id')
        ->leftJoin("prokeg.tb_".$tahun."_ind_kegiatan as indk",'indk.id_kegiatan','=','k.id')
        ->leftJoin("kebijakan.tb_".$tahun."_ind_keg_pen_pusat as indkp",'indkp.id_ind','=','indk.id')
        ->leftJoin(DB::raw("(select *,(select nama from kebijakan.".$rpjmn_table." where id=indp.id_pn) as nama_pn,(select nama from kebijakan.".$rpjmn_table." where id=indp.id_pp) as nama_pp,(select nama from kebijakan.".$rpjmn_table." where id=indp.id_kp) as nama_kp,(select nama from kebijakan.".$rpjmn_table." where id=indp.id_propn) as nama_propn,(select nama from kebijakan.".$rpjmn_table." where id=indp.id_pronas) as nama_pronas,target_".$dekade."_1 as target_1,target_".$dekade."_2 as target_2 from kebijakan.".$rpjmn_ind_table." as indp ) as indps"),'indps.id','=','indkp.id_ind_pusat')
        ->select(
            'p.id as id_p',
            'p.kode_program as kode_p',
            'p.uraian as nama_p',
            'indp.id as id_ind_p',
            'indp.indikator as ind_p_nama',
            'indp.indikator as ind_p_nama',
            'indp.target_awal as ind_p_target_1',
            'indp.target_ahir as ind_p_target_2',
            'indp.satuan as ind_p_satuan',
            'k.id as id_k',
            'k.kode_kegiatan as kode_k',
            'k.uraian as nama_k',
            'k.anggaran as anggaran_k',
            'indk.id as id_ind_k',
            'indk.indikator as ind_k_nama',
            'indk.target_awal as ind_k_target_1',
            'indk.target_ahir as ind_k_target_2',
            'indk.satuan as ind_k_satuan',
            'indps.id as id_indp_ps',
            'indps.nama as ind_ps_nama',
            'indps.jenis as ind_ps_jenis',
            'indps.id_pn',
            'indps.nama_pn',
            'indps.lokasi as indp_ps_lokasi',
            'indps.instansi as indp_ps_instansi',
            'indps.id_pp',
            'indps.nama_pp',
            'indps.id_kp',
            'indps.nama_kp',
            'indps.id_propn',
            'indps.nama_propn',
            'indps.id_pronas',
            'indps.nama_pronas',
            'indps.target_1 as ind_ps_target_1',
            'indps.target_2 as ind_ps_target_2',
            'indps.satuan as ind_ps_satuan'
        )
        ->where('k.id_urusan',3)
        ->where('k.id_sub_urusan',12)
        ->where('k.status',5)
        ->where('k.kode_daerah',$id)
        ->orderBy('id_p','ASC')
        ->orderBy('id_ind_p','ASC')
        ->orderBy('id_k','ASC')
        ->orderBy('id_ind_k','ASC')
        ->orderBy('id_indp_ps','ASC')


        ->get();

        return view('front.nuwas_project.prokeg.detail')->with(['data'=>$data,'daerah'=>$daerah]);

    }

    static public function api_daerah_target(){
        $tahun=HP::fokus_tahun();
        $data=DB::table('daerah_nuwas as n')->select(
            'n.*',
             DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=n.kode_daerah) as nama_daerah")
        )->where('tahun',$tahun)->get();

        return view('front.nuwas_project.table_daerah_target')->with('data',$data)->render();
    }

    public function api_daerah_target_2_tahun($get_data_only=false){
        $tahun=(int)HP::fokus_tahun();

        DB::enableQueryLog();  
        $data=DB::table('daerah_nuwas as n')
        ->leftJoin(DB::raw("(select * from public.master_regional as u where length(u.kode_daerah)>3) as r"),'r.kode_daerah','=','n.kode_daerah')
        ->select(
            'n.*',
            'r.color',
            'r.regional',
            DB::raw("(select concat(nama_pdam,' -> ',kategori_pdam) from public.pdam  where pdam.kode_daerah = n.kode_daerah ) as pdam "),

             DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=n.kode_daerah) as nama_daerah")
        )->where('tahun','<=',$tahun+1)
        ->orWhere('tahun',null)
        ->get();
         $query = DB::getQueryLog();
      // dd(end($query));

        $data_return=array(
            'all'=>[
            ],
            'point_target'=>[
                'name'=>'Target NUWSP',
                'jumlah_daerah'=>0,
                'color'=>'#9c27b0',
                'data'=>[]
            ],
            't'.$tahun=>[
                'name'=>'Tahun '.$tahun,
                'stimulan'=>[
                    'jumlah_daerah'=>0,
                    'data'=>[]
                ],
                'pendamping'=>[
                    'jumlah_daerah'=>0,
                    'data'=>[]
                ],
            ],
            't'.($tahun+1)=>[
                'name'=>'Tahun '.($tahun+1),
                'stimulan'=>[
                    'jumlah_daerah'=>0,
                    'data'=>[]
                ],
                'pendamping'=>[
                    'jumlah_daerah'=>0,
                    'data'=>[]
                ],
            ],
        );


        $reg=DB::table('public.master_regional as r')
        ->leftJoin('public.daerah_nuwas as n','n.kode_daerah','=','r.kode_daerah')->select(
            'n.tahun',
            'n.jenis_bantuan',
            'n.nilai_bantuan',
            'r.kode_daerah',
            'r.color',
            DB::raw("(case when n.kode_daerah is not null then 'target' else null end) as target"),
            'r.regional',
            DB::raw("(select string_agg(distinct(f.jenis),'||') from public.dokumen_kebijakan_daerah as f where f.jenis <> 'LAIN_LAIN' and f.tahun <=".$tahun." and f.tahun_selesai >=".$tahun." and f.kode_daerah=r.kode_daerah) as doc_kebijakan_daerah"),
            DB::raw("(replace('".route('ty.daerah',['kode_daerah'=>'xxx'])."','xxx',r.kode_daerah)) as link_tipologi"),
            DB::raw("(select concat(nama_pdam,' -> ',kategori_pdam) from public.pdam  where pdam.kode_daerah = n.kode_daerah ) as pdam "),
             DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=r.kode_daerah) as nama_daerah")
        )
        ->orderBy('r.kode_daerah','asc')
        ->orderBy('n.tahun','asc')
        ->get();




        $data_reg_all=[];
        foreach ($reg as $key => $d) {

            if(!isset( $data_return['all'][strtolower(str_replace(' ','' ,str_replace(',', '', $d->regional)))]['jumlah_daerah'])){
                $data_return['all'][strtolower(str_replace(' ','' ,str_replace(',', '', $d->regional)))]['jumlah_daerah']=0;
            }

            $dr=(array)$d;
            if($dr['target']){
                 $append=(array)DB::connection('sinkron_prokeg')
                    ->table('prokeg.tb_'.$tahun.'_kegiatan as k')

                    ->select(
                        DB::raw("count(distinct(k.id_program)) as jumlah_program"),
                        DB::raw("count(*) as jumlah_kegiatan"),
                        DB::raw("max(k.status) as status_rkpd_sistem")
                    )
                    ->where('k.kode_lintas_urusan',12)
                    ->where('k.kode_daerah',$dr['kode_daerah'])
                    ->where('k.status',5)

                    ->first();

                    $dr['jumlah_program']=(int) $append['jumlah_program'];
                    $dr['jumlah_kegiatan']=(int) $append['jumlah_kegiatan'];
                    $dr['status_rkpd_sistem']=(int) $append['status_rkpd_sistem'];



                    $append=(array)DB::connection('sinkron_prokeg')
                    ->table('prokeg.tb_'.($tahun+1).'_kegiatan as k')

                    ->select(
                        DB::raw("count(distinct(k.id_program)) as jumlah_program_1"),
                        DB::raw("count(*) as jumlah_kegiatan_1"),
                        DB::raw("max(k.status) as status_rkpd_sistem_1")

                    )
                    ->where('k.kode_lintas_urusan',12)
                    ->where('k.kode_daerah',$dr['kode_daerah'])
                    ->where('k.status',5)

                    ->first();

                    $dr['jumlah_program_1']=(int)$append['jumlah_program_1'];
                    $dr['jumlah_kegiatan_1']=(int)$append['jumlah_kegiatan_1'];
                    $dr['status_rkpd_sistem_1']=(int)$append['status_rkpd_sistem_1'];


            }

            if($dr['tahun']){
                $dr['z']=0.1;
                $dr['id']=$key;
                $dr['opacity']=1;
                $dr['borderWidth']= 0.2;
                $dr['color']= '#fff';
                $dr['borderColor']='black';
                $dr['states']=[
                    'hover'=>[
                        'borderWidth'=>2
                    ]

                ];




                $data_return['point_target']['data'][]=$dr;

                if(!isset( $data_return['point_target']['name'])){
                    $data_return['point_target']['name']='Target NUWSP';
                    $data_return['point_target']['jumlah_daerah']=0;
                    $data_return['point_target']['color']='';

                }

                $data_return['point_target']['jumlah_daerah']+=1;
                $data_return['all'][strtolower(str_replace(' ','' ,str_replace(',', '', $d->regional)))]['jumlah_daerah']+=1;


            }

           

            $data_return['all'][strtolower(str_replace(' ','' ,str_replace(',', '', $d->regional)))]['data'][]=$d;
            $data_return['all'][strtolower(str_replace(' ','' ,str_replace(',', '', $d->regional)))]['color']=$d->color;
            $data_return['all'][strtolower(str_replace(' ','' ,str_replace(',', '', $d->regional)))]['name']=$d->regional;

            $dr['color']=$d->color;
            $data_reg_all[]=$dr;

            # code...
        }


        if($get_data_only){
            return $data_return['point_target'];
        }

        foreach ($data as $key => $d) {

            if($d->tahun!=1){
              $jenis_bantuan=explode(',',$d->jenis_bantuan);

              if(in_array('@STIMULAN', $jenis_bantuan)){
                    $df=(array)$d;
                    unset($df['color']);
                  $data_return['t'.$d->tahun]['stimulan']['data'][]=$df;
                  $data_return['t'.$d->tahun]['stimulan']['jumlah_daerah']+=1;

              }
              if(in_array('@PENDAMPING', $jenis_bantuan)){
                $df=(array)$d;
                    unset($df['color']);
                  $data_return['t'.$d->tahun]['pendamping']['data'][]=$df;
                  $data_return['t'.$d->tahun]['pendamping']['jumlah_daerah']+=1;

              }
            }

// #2d2c36
        }

        $data_return['data']=$data_reg_all;
        
        return $data_return;

    }

    // public function api_daerah_target_map(){
    //     $tahun=HP::fokus_tahun();

    //       $map_data=[
    //         'title'=>'DAERAH NUWSP'.$tahun.' - '.(((int)$tahun)+1),
    //         'series'=>[
    //             [
    //                 'name_layer'=>'NUWSP SEMUA'.,
    //                 'mapData_name'=>'ind_kota',
    //                 'name_data'=>'KOTA',

    //                 'legend'=>[
    //                     'cat'=>['BUKAN DAERAH NUWSP','BUKAN DAERAH NUWSP','TERDAPAT KEGIATAN AIR MINUM'],
    //                     'color'=>['#fff','#32a852','#42f2f5'],
    //                 ],

    //                 'data'=>[]

    //             ],
    //             [
    //                 'name_layer'=>'NUWSP '.$tahun,
    //                 'mapData_name'=>'ind',
    //                 'name_data'=>'PROVINSI',

    //                 'legend'=>[
    //                    'cat'=>['TIDAK TERDAPAT DATA','MELAPORKAN RKPD','TERDAPAT KEGIATAN AIR MINUM'],
    //                     'color'=>['#fff','#32a852','#42f2f5'],
    //                 ],
    //                 'data'=>[],
    //             ],
    //              [
    //                 'name_layer'=>'NUWSP '.$tahun,
    //                 'mapData_name'=>'ind',
    //                 'name_data'=>'PROVINSI',
    //                 'legend'=>[
    //                    'cat'=>['TIDAK TEDAPAT DATA - 0%','SANGAT RENDAH - <30%','RENDAH - <40%','SEDANG - <60%','TINGGI - <80%','SANGAT TINGGI - >80%'],
    //                     'color'=>['#fff','#ff0000','#cf6317','#2C4F9B','#ffff00','#00ff00'],
    //                 ],
    //                 'data'=>[],



    //             ]
    //         ]
    //     ];

    // }
}
