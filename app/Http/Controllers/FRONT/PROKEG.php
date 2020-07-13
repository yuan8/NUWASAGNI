<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Carbon\Carbon;
use HP;
class PROKEG extends Controller
{
    //

    public static $ANGGARAN_PROKEG=false;
    public static $ANGGARAN_PROKEG_anggaran=false;


    public function index(){
        return view('front.prokeg.index');
    }

    static function status($status){
        switch ((int)$status) {
            case 1:

            $status= 'Persiapan';
            break;
            case 2:
            $status= 'RANWAL';
            break;
            case 3:
            $status= 'RANRKPD';
            break;
            case 4:
            $status= 'RANHIR';
            break;
            case 5:
            $status= 'FINAL';
            break;
        
            default:
                # code...
            $status= 'UNKNOWN';

            break;
        }

        return $status;
    }

    public function per_urusan(){
    	$tahun=HP::fokus_tahun();
        $id_dom='per_u';

        DB::connection('sinkron_prokeg')->enableQueryLog();
    	$data=DB::connection('sinkron_prokeg')
        ->table('public.master_urusan as u')
        ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'),'u.id','=','k.id_urusan')
        ->whereNotNull('k.id_sub_urusan')
        ->whereNotNull('k.id_urusan')
        ->whereNotNull('k.id_program')
        ->where('k.status',5)

    	->select(
            "u.id as id",
    		DB::raw("u.nama as nama"),
    		DB::raw("count(k.*) as jumlah_kegiatan"),
    		DB::raw("count(DISTINCT(k.id_program)) as jumlah_program"),
    		DB::raw("sum(k.anggaran)::numeric as jumlah_anggaran")
    	)
    	->groupBy('u.id')
        ->orderBy(DB::raw("jumlah_kegiatan "),'DESC')

    	->get();

        // dd(DB::connection('sinkron_prokeg')->getQueryLog());

        $data_return=[
            'category'=>[],
            'series'=>[
                0=>[
                                    'name'=>'Jumlah Kegiatan',
                                    'data'=>[]
                                    ],
                1=>[
                                    'name'=>'Jumlah Program',
                                    'data'=>[]
                                    ],
                2=>[
                                    'name'=>'Anggaran',
                                    'data'=>[]
                                    ],

            ],
            'data'=>[]
        ];

        foreach ($data as $key => $d) {
        $data_return['category'][]=$d->nama;
        $data_return['series'][0]['data'][]=(int)$d->jumlah_kegiatan;
        $data_return['series'][1]['data'][]=(int)$d->jumlah_program;
        $data_return['series'][2]['data'][]=(float)$d->jumlah_anggaran;
        $data_return['series'][2]['visible']=self::$ANGGARAN_PROKEG;

        $data_return['data'][]=$d;

        }

        return view('front.map-tem.map1')
        ->with('data',$data_return)
        ->with('id_dom',$id_dom)
        ->with('title','PROGRAM KEGIATAN PER URUSAN')
        ->with('next','program-kegiatan-per-sub-urusan');
    	// dd($data);

    }

    public function per_sub_urusan($id){

        $tahun=HP::fokus_tahun();
        $id_dom='per_suu';

        $urusan =DB::table('master_urusan')->find($id);
        DB::connection('sinkron_prokeg')->enableQueryLog();
        $data=DB::connection('sinkron_prokeg')
        ->table('public.master_sub_urusan as u')
        ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'),'u.id','=','k.id_sub_urusan')
        ->whereNotNull('k.id_sub_urusan')
        ->whereNotNull('k.id_urusan')
        ->whereNotNull('k.id_program')
        ->where('k.status',5)


        ->where('k.id_urusan',$id)
        ->select(
            "u.id as id",
            DB::raw("u.nama as nama"),
            DB::raw("count(k.*) as jumlah_kegiatan"),
            DB::raw("count(DISTINCT(k.id_program)) as jumlah_program"),
            DB::raw("sum(k.anggaran)::numeric as jumlah_anggaran")
        )
        ->groupBy('u.id')
        ->orderBy(DB::raw("jumlah_kegiatan "),'DESC')

        ->get();

        // dd(DB::connection('sinkron_prokeg')->getQueryLog());

        $data_return=[
            'category'=>[],
            'series'=>[
                0=>[
                                    'name'=>'Jumlah Kegiatan',
                                    'data'=>[]
                                    ],
                1=>[
                                    'name'=>'Jumlah Program',
                                    'data'=>[]
                                    ],
                2=>[
                                    'name'=>'Anggaran',
                                    'data'=>[]
                                    ],

            ],
            'data'=>[]
        ];

        foreach ($data as $key => $d) {
        $data_return['category'][]=$d->nama;
        $data_return['series'][0]['data'][]=(int)$d->jumlah_kegiatan;
        $data_return['series'][1]['data'][]=(int)$d->jumlah_program;
        $data_return['series'][2]['data'][]=(float)$d->jumlah_anggaran;
        $data_return['series'][2]['visible']=self::$ANGGARAN_PROKEG;

        $data_return['data'][]=$d;


        }
   return view('front.map-tem.map1')
        ->with('data',$data_return)
        ->with('id_dom',$id_dom)
        ->with('title','PROGRAM KEGIATAN SUBURUSAN '.$urusan->nama)
        ->with('next','program-kegiatan-per-program');

    }


public function per_program($id){

        $tahun=HP::fokus_tahun();
        $id_dom='per_suu';

        $urusan =DB::table('master_sub_urusan')->find($id);
        DB::connection('sinkron_prokeg')->enableQueryLog();
        $data=DB::connection('sinkron_prokeg')
        ->table('prokeg.tb_'.$tahun.'_'.'kegiatan as k')
        ->whereNotNull('k.id_sub_urusan')
        ->whereNotNull('k.id_urusan')
        ->whereNotNull('k.id_program')
        ->where('k.id_sub_urusan',$id)
        ->where('k.status',5)

        ->select(
            "k.id_program as id",
            DB::raw("(select uraian from prokeg.tb_".$tahun."_program as p where p.id=k.id_program) as nama"),
            DB::raw("(select count(*) from prokeg.tb_".$tahun."_ind_program as ip where ip.id_program=k.id_program) as jumlah_ind"),
            DB::raw("count(k.*) as jumlah_kegiatan"),
            DB::raw("sum(k.anggaran)::numeric as jumlah_anggaran")
        )
        ->groupBy('k.id_program')
        ->orderBy(DB::raw("jumlah_kegiatan "),'DESC')
        ->get();



        // dd(DB::connection('sinkron_prokeg')->getQueryLog());

        $data_return=[
            'category'=>[],
            'series'=>[
                0=>[
                                    'name'=>'Jumlah Kegiatan',
                                    'data'=>[],
                    ],
                1=>[
                                    'name'=>'Anggaran',
                                    'data'=>[]
                    ],

            ],
            'data'=>[]
        ];

        foreach ($data as $key => $d) {
        $data_return['category'][]=$d->nama;
        $data_return['series'][0]['data'][]=(int)$d->jumlah_kegiatan;
        // $data_return['series'][1]['data'][]=(int)$d->jumlah_program;
        $data_return['series'][1]['data'][]=(float)$d->jumlah_anggaran;
        $data_return['series'][1]['visible']=self::$ANGGARAN_PROKEG;

        $data_return['data'][]=$d;

        }

        return view('front.map-tem.map_pk')
        ->with('data',$data_return)
        ->with('id_dom',$id_dom)
        ->with('title','PROGRAM  SUBURUSAN '.$urusan->nama)
        ->with('next','');

        


}

public function daerah(){

    return view('front.daerah');
}

public function per_provinsi(){
    $tahun=HP::fokus_tahun();
        $id_dom='per_provinsi';

        $data=DB::connection('sinkron_prokeg')
        ->table('public.master_daerah as u')
        ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'),DB::raw("left(k.kode_daerah,2)"),'=',DB::raw("CONCAT(u.id)"))
        ->whereNotNull('k.id_program')
        ->where('k.status',5)
        ->where('k.id_sub_urusan',12)
        ->whereIn('k.status',[4,5])
        ->whereNull('u.kode_daerah_parent')
        ->select(
            "u.id as id",
              DB::raw("(select status from "."prokeg.tb_".$tahun."_status_file_daerah as f where f.kode_daerah = u.id limit 1) as status"),
            DB::raw("u.nama as nama"),
            DB::raw("count(k.*) as jumlah_kegiatan"),
            DB::raw("count(DISTINCT(k.id_program)) as jumlah_program"),
            DB::raw("sum(k.anggaran)::numeric as jumlah_anggaran")
        )
        ->groupBy('u.id')
        ->orderBy(DB::raw("jumlah_kegiatan "),'DESC')

        ->get();


        // dd(DB::connection('sinkron_prokeg')->getQueryLog());

        $data_return=[
            'category'=>[],
            'series'=>[
                0=>[
                                    'name'=>'Jumlah Kegiatan',
                                    'data'=>[]
                                    ],
                1=>[
                                    'name'=>'Jumlah Program',
                                    'data'=>[]
                                    ],
                2=>[
                                    'name'=>'Anggaran',
                                    'data'=>[]
                                    ],

            ],
            'data'=>[]
        ];

        foreach ($data as $key => $d) {
        $d->nama=$d->nama.' ['.static::status($d->status).']';
        $data_return['category'][]=$d->nama;
        $data_return['series'][0]['data'][]=(int)$d->jumlah_kegiatan;
        $data_return['series'][1]['data'][]=(int)$d->jumlah_program;
        $data_return['series'][2]['data'][]=(float)$d->jumlah_anggaran;
        $data_return['series'][2]['visible']=self::$ANGGARAN_PROKEG;

        $data_return['data'][]=$d;

        }

        return view('front.map-tem.map1')
        ->with('data',$data_return)
        ->with('id_dom',$id_dom)
        ->with('title','PROGRAM KEGIATAN PER PROVINSI')
        ->with('next','prokeg/program-kegiatan-per-kota');
        // dd($data);

}

public function per_kota($id){
    $tahun=HP::fokus_tahun();
        $id_dom='per_kota';

        $daerah=DB::table('master_daerah')->find($id);
        $data=DB::connection('sinkron_prokeg')
        ->table('public.master_daerah as u')
        ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'),'k.kode_daerah','ilike','u.id')
        ->whereNotNull('k.id_urusan')
        ->whereNotNull('k.id_program')
         ->where('k.status',5)
        ->where('k.id_sub_urusan',12)
        ->whereRaw("left(u.id,2) ='".$id."'")

        ->select(
            "u.id as id",
            DB::raw("(select status from "."prokeg.tb_".$tahun."_status_file_daerah as f where f.kode_daerah = u.id limit 1) as status"),
            DB::raw("u.nama as nama"),
            DB::raw("count(k.*) as jumlah_kegiatan"),
            DB::raw("count(DISTINCT(k.id_program)) as jumlah_program"),
            DB::raw("sum(k.anggaran)::numeric as jumlah_anggaran")
        )
        ->groupBy('u.id')
        ->orderBy(DB::raw("jumlah_kegiatan "),'DESC')

        ->get();


        // dd(DB::connection('sinkron_prokeg')->getQueryLog());

        $data_return=[
            'category'=>[],
            'series'=>[
                0=>[
                                    'name'=>'Jumlah Kegiatan',
                                    'data'=>[]
                                    ],
                1=>[
                                    'name'=>'Jumlah Program',
                                    'data'=>[]
                                    ],
                2=>[
                                    'name'=>'Anggaran',
                                    'data'=>[]
                                    ],

            ],
            'data'=>[]
        ];

        foreach ($data as $key => $d) {

        $d->nama=$d->nama.' ['.static::status($d->status).']';

        $data_return['category'][]=$d->nama;
        $data_return['series'][0]['data'][]=(int)$d->jumlah_kegiatan;
        $data_return['series'][1]['data'][]=(int)$d->jumlah_program;
        $data_return['series'][2]['data'][]=(float)$d->jumlah_anggaran;
        $data_return['series'][2]['visible']=self::$ANGGARAN_PROKEG;

        $data_return['data'][]=$d;

        }

        return view('front.map-tem.map1')
        ->with('data',$data_return)
        ->with('id_dom',$id_dom)
        ->with('title','PROGRAM KEGIATAN '.$daerah->nama.' PER KOTA/KAB')
        ->with('next','prokeg/program-kegiatan-per-urusan');
        // dd($data);

}


        public function data($id,Request $request){
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


        ->get()->toArray();

        // dd($data);
        $catatan=DB::connection('sinkron_analis')->table('tb_'.$tahun.'_rkpd_catatan_daerah as d')
        ->where('kode_daerah',$id)
        ->select('d.*','u.name')
        ->leftJoin('public.users as u','u.id','=','d.id_user')
        ->first();

        return view('front.prokeg.table_daerah')
        ->with('catatan',$catatan)
        ->with('name_right_side_bar','Buat Catatan')
        ->with('data',$data)->with('daerah',$daerah);

        }


        public function detail_program($id){
            $tahun=HP::fokus_tahun();
            $program=DB::connection('sinkron_prokeg')
            ->table(DB::raw('prokeg.tb_'.$tahun.'_'.'program as p'))->find($id);

            $data=DB::connection('sinkron_prokeg')
            ->table(DB::raw('prokeg.tb_'.$tahun.'_'.'program as p'))
            ->join(DB::raw('prokeg.tb_'.$tahun.'_'.'ind_program as ip'),'ip.id_program','=','p.id')
            ->select(
                'ip.id',
                "ip.indikator"
            )
            ->where('ip.indikator','!=',null)
            ->where('p.id',$id)
            ->get();

            return view('front.detail_program')->with('data',$data)
            ->with('program',$program);
        }


        public function dearah_per_urusan($id){
        $tahun=HP::fokus_tahun();
        $id_dom='per_d_u';
        $daerah=DB::table('master_daerah')->find($id);
        DB::connection('sinkron_prokeg')->enableQueryLog();
        $data=DB::connection('sinkron_prokeg')
        ->table('public.master_urusan as u')
        ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'),'u.id','=','k.id_urusan')
         ->where('k.status',5)
        ->where('k.id_sub_urusan',12)
        ->whereNotNull('k.id_urusan')
        ->whereNotNull('k.id_program')
        ->where('k.kode_daerah',$id)

        ->select(
            "u.id as id",
            DB::raw("u.nama as nama"),
            DB::raw("count(k.*) as jumlah_kegiatan"),
            DB::raw("count(DISTINCT(k.id_program)) as jumlah_program"),
            DB::raw("sum(k.anggaran)::numeric as jumlah_anggaran")
        )
        ->groupBy('u.id')
        ->orderBy(DB::raw("jumlah_kegiatan "),'DESC')

        ->get();

        // dd(DB::connection('sinkron_prokeg')->getQueryLog());

        $data_return=[
            'category'=>[],
            'series'=>[
                0=>[
                                    'name'=>'Jumlah Kegiatan',
                                    'data'=>[]
                                    ],
                1=>[
                                    'name'=>'Jumlah Program',
                                    'data'=>[]
                                    ],
                2=>[
                                    'name'=>'Anggaran',
                                    'data'=>[]
                                    ],

            ],
            'data'=>[]
        ];

        foreach ($data as $key => $d) {
        $data_return['category'][]=$d->nama;
        $data_return['series'][0]['data'][]=(int)$d->jumlah_kegiatan;
        $data_return['series'][1]['data'][]=(int)$d->jumlah_program;
        $data_return['series'][2]['data'][]=(float)$d->jumlah_anggaran;
        $data_return['series'][2]['visible']=self::$ANGGARAN_PROKEG;

        $data_return['data'][]=$d;

        }

        return view('front.map-tem.map1')
        ->with('data',$data_return)
        ->with('id_dom',$id_dom)
        ->with('title','PROGRAM KEGIATAN '.$daerah->nama.' PER URUSAN')
        ->with('next','prokeg/program-kegiatan-per-sub_urusan/'.$id);
        // dd($data);

    }


    public function dearah_per_sub_urusan($kode_daerah,$id_urusan){

        $tahun=HP::fokus_tahun();
        $id_dom='per_suu';
        $daerah =DB::table('master_daerah')->find($kode_daerah);

        $urusan =DB::table('master_urusan')->find($id_urusan);
        DB::connection('sinkron_prokeg')->enableQueryLog();
        $data=DB::connection('sinkron_prokeg')
        ->table('public.master_sub_urusan as u')
        ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'),'u.id','=','k.id_sub_urusan')
           ->where('k.status',5)
        ->where('k.id_sub_urusan',12)
        ->whereNotNull('k.id_urusan')
        ->whereNotNull('k.id_program')
        ->where('k.kode_daerah',$kode_daerah)
        ->where('k.id_urusan',$id_urusan)
        ->select(
            "u.id as id",
            DB::raw("u.nama as nama"),
            DB::raw("count(k.*) as jumlah_kegiatan"),
            DB::raw("count(DISTINCT(k.id_program)) as jumlah_program"),
            DB::raw("sum(k.anggaran)::numeric as jumlah_anggaran")
        )
        ->groupBy('u.id')
        ->orderBy(DB::raw("jumlah_kegiatan "),'DESC')

        ->get();

        // dd(DB::connection('sinkron_prokeg')->getQueryLog());

        $data_return=[
            'category'=>[],
            'series'=>[
                0=>[
                                    'name'=>'Jumlah Kegiatan',
                                    'data'=>[]
                                    ],
                1=>[
                                    'name'=>'Jumlah Program',
                                    'data'=>[]
                                    ],
                2=>[
                                    'name'=>'Anggaran',
                                    'data'=>[]
                                    ],

            ],
            'data'=>[]
        ];

        foreach ($data as $key => $d) {
        $data_return['category'][]=$d->nama;
        $data_return['series'][0]['data'][]=(int)$d->jumlah_kegiatan;
        $data_return['series'][1]['data'][]=(int)$d->jumlah_program;
        $data_return['series'][2]['visible']=self::$ANGGARAN_PROKEG;
        $data_return['series'][2]['data'][]=(float)$d->jumlah_anggaran;
        $data_return['data'][]=$d;


        }
   return view('front.map-tem.map1')
        ->with('data',$data_return)
        ->with('id_dom',$id_dom)
        ->with('title','PROGRAM KEGIATAN '.$daerah->nama.' SUBURUSAN DARI'.$urusan->nama)
        ->with('next','prokeg/program-kegiatan-per-daerah-sub-urusan-per-program/'.$daerah->id);

    }

    public function dearah_per_program($kode_daerah,$id_sub_urusan){
        $tahun=HP::fokus_tahun();
        $id_dom='per_suu';
        $daerah=DB::table('master_daerah')->find($kode_daerah);
        $urusan =DB::table('master_sub_urusan')->find($id_sub_urusan);
        DB::connection('sinkron_prokeg')->enableQueryLog();
        $data=DB::connection('sinkron_prokeg')
        ->table('prokeg.tb_'.$tahun.'_'.'kegiatan as k')
         ->where('k.status',5)
        ->where('k.id_sub_urusan',12)
        ->whereNotNull('k.id_urusan')
        ->whereNotNull('k.id_program')
        ->where('k.kode_daerah',$kode_daerah)
        ->where('k.id_sub_urusan',$id_sub_urusan)
        ->select(
            "k.id_program as id",
            DB::raw("(select uraian from prokeg.tb_".$tahun."_program as p where p.id=k.id_program) as nama"),
            DB::raw("(select count(*) from prokeg.tb_".$tahun."_ind_program as ip where ip.id_program=k.id_program) as jumlah_ind"),
            DB::raw("count(k.*) as jumlah_kegiatan"),
            DB::raw("sum(k.anggaran)::numeric as jumlah_anggaran")
        )
        ->groupBy('k.id_program')
        ->orderBy(DB::raw("jumlah_kegiatan "),'DESC')
        ->get();



        // dd(DB::connection('sinkron_prokeg')->getQueryLog());

        $data_return=[
            'category'=>[],
            'series'=>[
                0=>[
                                    'name'=>'Jumlah Kegiatan',
                                    'data'=>[],
                    ],
                1=>[
                                    'name'=>'Anggaran',
                                    'data'=>[]
                    ],

            ],
            'data'=>[]
        ];

        foreach ($data as $key => $d) {
        $data_return['category'][]=$d->nama;
        $data_return['series'][0]['data'][]=(int)$d->jumlah_kegiatan;
        // $data_return['series'][1]['data'][]=(int)$d->jumlah_program;
        $data_return['series'][1]['data'][]=(float)$d->jumlah_anggaran;
        $data_return['series'][1]['visible']=self::$ANGGARAN_PROKEG;

        $data_return['data'][]=$d;
        }

         return view('front.map-tem.map_pk')
        ->with('data',$data_return)
        ->with('id_dom',$id_dom)
        ->with('title',$daerah->nama.' PROGRAM SUBURUSAN '.$urusan->nama)
        ->with('next','pp');
    }


    public function dash_daerah($tahun=2020){
        
        $data=DB::table(DB::raw("master_daerah as d"))
        ->Leftjoin("prokeg.tb_".$tahun."_status_file_daerah as s","s.kode_daerah", "=","d.id")
        ->Leftjoin("prokeg.tb_".$tahun."_kegiatan as tk",'tk.kode_daerah','=','d.id')
        ->Leftjoin("prokeg.tb_".$tahun."_ind_kegiatan as tik","tik.id_kegiatan","=","tk.id")
        ->Leftjoin("public.master_urusan as u","u.id","=","tk.id_urusan")
        ->orderBy('d.id','ASC')
        ->groupBy("d.id")
        ->groupBy("u.id")
        ->select(
            DB::raw("(select concat(logo,'@',nama) from public.master_daerah md  where md.id=d.id) as nama_daerah,
            max(u.nama) as nama_urusan"),
            "d.id as kode_daerah",
            "u.id as id_urusan",
             DB::raw("max(s.status) as status") ,
             DB::raw("max(s.last_date ) as status_last_date") ,
             DB::raw("sum(case when tk.id_urusan is not null then tk.anggaran else 0 end) as total_anggaran"),
             DB::raw("sum(case when tk.id_urusan is not null then 1 else 0 end) as jumlah_kegiatan"),
             DB::raw("sum(case when ((tk.id_urusan is not null)and(tk.id_sub_urusan is not null)) then 1 else 0 end  ) as jumlah_kegiatan_tertaging"),
             DB::raw("sum(case when (tik.id) is not null then 1 else 0 end  ) as jumlah_ind
                "))
        ->whereIn('u.id',explode(',', env('HANDLE_URUSAN')))
        ->orWhere('u.id',null)
        ->get();

       $urusan_db= DB::table('master_urusan')
        ->select('id','nama')
        ->whereIn('id',explode(',',env('HANDLE_URUSAN')))->get();
        $urusan=[];
        foreach ($urusan_db as $key => $d) {
            $urusan[$d->id]=array(
                'id'=>$d->id,
                'nama'=>$d->nama,
                'existing_data_sipd'=>0,
                'tertaging_supd'=>0,

            );
        }
        $data_return=[];
        foreach($data as $d){
            $cor=[];
            if(!isset($data_return[$d->kode_daerah])){
                $cor['kode_daerah']=$d->kode_daerah;
                $cor['logo']=explode('@', $d->nama_daerah)[0];
                $cor['nama_daerah']=explode('@', $d->nama_daerah)[1];
                $cor['status_data_sipd']=0;
                $cor['exist_data']=$d->jumlah_kegiatan?1:0;
                $cor['urusan']=$urusan;
                $cor['updated_last_date_sipd']=0;
                $cor['lengkap']=0;
            }else{
                $cor=$data_return[$d->kode_daerah];
            }
            if($d->status){
                $cor['status_data_sipd']=$d->status;
                $cor['updated_last_date_sipd']=$d->status_last_date;
                if(($d->id_urusan!=99)&&!empty($d->id_urusan)){
                    if($d->jumlah_kegiatan_tertaging>0){
                          $cor['urusan'][$d->id_urusan]['tertaging_supd']=1;
                    }
                    if($d->jumlah_kegiatan>0){
                          $cor['urusan'][$d->id_urusan]['existing_data_sipd']=1;
                    }
                }
            }
            $count_tag=0;
            foreach ($cor['urusan'] as $key => $du) {
                if($du['tertaging_supd']){
                    $count_tag+=1;
                };
            }

            if($count_tag==count(explode(',',env('HANDLE_URUSAN')))){
                $cor['lengkap']=1;
            }

            $data_return[$d->kode_daerah]=$cor;
        
        }
        $pro=DB::table('master_daerah')->where('kode_daerah_parent',null)->orderBy('nama','ASC')->get();

        $data_return=array_values($data_return);
        return view('front.dash.index')
        ->with('provinsi',$pro)
        ->with('data',$data_return)->with('tahun',$tahun)->with('urusan',$urusan_db);
    }

    public function dash_urusan(Request $request){
        $tahun=HP::fokus_tahun();
        $data=DB::connection('sinkron_prokeg')->table('tb_'.$tahun.'_kegiatan as k')
        ->select(
            DB::raw("(select nama from public.master_daerah as d where d.id=k.kode_daerah) as nama_daerah"),DB::raw("string_agg(distinct(CONCAT('@',id_urusan))::text,',') as list_id_urusan")

        )->where('k.id_urusan','!=',null);

        if($request->negation){

        }else{

        }

        $data=$data->groupBy('k.kode_daerah')
        ->get();
        dd($data);



    }

    public function storeCatatan($id,Request $request){

        $uid=Auth::User()->id;
        $tahun=HP::fokus_tahun();

      $catatan=DB::connection('analis')->table('tb_'.$tahun.'_rkpd_catatan_daerah')
        ->where('kode_daerah',$id)
        ->first();

        if($catatan){
            DB::connection('analis')->table('tb_'.$tahun.'_rkpd_catatan_daerah')
             ->where('kode_daerah',$id)->update([
            'catatan'=>$request->catatan,
            'id_user'=>$uid,
            'updated_at'=>Carbon::now()
            ]);
        }else{
             DB::connection('analis')->table('tb_'.$tahun.'_rkpd_catatan_daerah')
             ->insert([
            'catatan'=>$request->catatan,
            'id_user'=>$uid,
            'kode_daerah'=>$id,
            'updated_at'=>Carbon::now(),
            'created_at'=>Carbon::now()

            ]);
        }


        return back();



    }


    public function daerah_upload_air_minum_map(){
        $tahun=HP::fokus_tahun();
        $data=DB::connection('sinkron_prokeg')->table('public.master_daerah as d')
        ->leftJoin(DB::raw("(select count(*) melaporkan, sum(case when (id_urusan=3 and id_sub_urusan=12) then 1 else 0 end ) as jumlah_kegiatan ,kode_daerah from  prokeg.tb_".$tahun."_kegiatan where status=5  group by kode_daerah) as k"),'k.kode_daerah','=','d.id')
        ->select(
            "d.id as id_daerah",
            DB::raw("left(d.id,2) as kode_provinsi"),
            DB::raw("(case when (length(d.id) < 3) then 'P' else 'K' end)  as status_daerah"),
            "d.nama as nama_daerah",
            'k.jumlah_kegiatan',
            DB::raw("(case when k.melaporkan <> 0 then (case when k.jumlah_kegiatan <> 0 then 2 else 1 end) else 0 end ) as status"),
            DB::RAW("(case when (length(d.id) > 3) then (select nama from public.master_daerah as dp where dp.id = d.kode_daerah_parent) else '' end) as nama_provinsi")
        )->get();

        // pr.data

         $map_data=[
            'title'=>'RKPD KEGIATAN AIR MINUM DAERAH '.$tahun,
            'series'=>[
                [
                    'name_layer'=>'AIR MINUM KOTA / KABUPATEN',
                    'mapData_name'=>'ind_kota',
                    'name_data'=>'KOTA',

                    'legend'=>[
                        'cat'=>['TIDAK TERDAPAT DATA','MELAPORKAN RKPD','TERDAPAT KEGIATAN AIR MINUM'],
                        'color'=>['#fff','#32a852','#42f2f5'],
                    ],

                    'data'=>[]

                ],
                [
                    'name_layer'=>'AIR MINUM PROVINSI',
                    'mapData_name'=>'ind',
                    'name_data'=>'PROVINSI',
                    
                    'legend'=>[
                       'cat'=>['TIDAK TERDAPAT DATA','MELAPORKAN RKPD','TERDAPAT KEGIATAN AIR MINUM'],
                        'color'=>['#fff','#32a852','#42f2f5'],
                    ],
                    'data'=>[],
                ],
                 [
                    'name_layer'=>'PERSENTASE PELAPORAN PER-PROVINSI',
                    'mapData_name'=>'ind',
                    'name_data'=>'PROVINSI',
                    
                    'legend'=>[
                       'cat'=>['TIDAK TEDAPAT DATA - 0%','SANGAT RENDAH - <30%','RENDAH - <40%','SEDANG - <60%','TINGGI - <80%','SANGAT TINGGI - >80%'],
                        'color'=>['#fff','#ff0000','#cf6317','#2C4F9B','#ffff00','#00ff00'],
                    ],
                    'data'=>[],
                    


                ]
            ]
        ];


        $PELAPORAN=[];

        foreach ($data as $key => $d) {
            $d=(array)$d;
            # code...
            $color='#fff';
            $status='';
            switch ($d['status']) {
                case 0:
                $color='#fff';
                $status='TIDAK TERDAPAT DATA RKPD';
                    # code...

                    break;
                case 1:
                $color='#32a852';
                $status='TERDPAT DATA RKPD';

                    # code...

                    break;
                case 2:
                $color='#42f2f5';
                $status='TERDAPAT KEGIATAN AIR MINUM';

                    # code...


                    break;
                
                default:
                    # code...
                    break;
            }


             $tooltip= $d['nama_daerah'].($d['status_daerah']=='K'?' / '.$d['nama_provinsi']:'').'<br>KONDISI KEGIATAN <br> : <b>'.($d['status']!=2?$status:$d['jumlah_kegiatan'].' Kegiatan </b>');


             if(!isset($PELAPORAN[$d['kode_provinsi']])){
                $PELAPORAN[$d['kode_provinsi']]=array(
                    'id_daerah'=>'',
                    'nama_daerah'=>'',
                    'value_melapor'=>0,
                    'jumlah_daerah'=>0,
                    'kategori'=>'',
                    'link'=>'',
                    'color'=>'',
                    'tooltip'=>''
                );
             }




             if($d['status_daerah']=='K'){

                if($d['status']==2){
                    $PELAPORAN[$d['kode_provinsi']]['value_melapor']+=1;
                }

                $PELAPORAN[$d['kode_provinsi']]['jumlah_daerah']+=1;

                 $map_data['series'][0]['data'][]=[
                    $d['id_daerah'],
                    $d['nama_daerah'],
                    $d['jumlah_kegiatan']OR 0,
                    $status,
                    route('pr.data',['id'=>$d['id_daerah']]),
                    $color,
                   $tooltip
                ];
             }else{

                if($d['status']==2){
                    $PELAPORAN[$d['kode_provinsi']]['value_melapor']+=1;
                }

                $PELAPORAN[$d['kode_provinsi']]['jumlah_daerah']+=1;

                $PELAPORAN[$d['kode_provinsi']]['nama_daerah']=$d['nama_daerah'];
                $PELAPORAN[$d['kode_provinsi']]['id_daerah']=$d['id_daerah'];




                 $map_data['series'][1]['data'][]=[
                    $d['id_daerah'],
                    $d['nama_daerah'],
                    $d['jumlah_kegiatan']OR 0,
                    $status,
                    route('pr.data',['id'=>$d['id_daerah']]),
                    $color,
                   $tooltip
               ];

             }









        }

        foreach ($PELAPORAN as $key => $d) {

                if($d['value_melapor'] !=0){
                    $PERSENTASE=(float) number_format(($d['value_melapor'] / $d['jumlah_daerah'])*100,2);
                }else{
                    $PERSENTASE=0;
                }
               
               if($PERSENTASE==0){
                    $status='TIDAK MELAPOR';
                    $color='#fff';

               }else if($PERSENTASE<30){
                    $status='SANGGAT RENDAH';
                    $color='#ff0000';

                    
               }else if($PERSENTASE<40){
                    $status='RENDAH';
                    $color='#cf6317';
                    
               }
               else if($PERSENTASE<60){
                    $status='SEDANG';
                    $color='#2C4F9B';

                    
               }else if($PERSENTASE<80){
                    $status='TINGGI';
                    $color='#ffff00';

                    
               }
               else if($PERSENTASE>80){
                    $status='SANGAT TINGGI';
                    $color='#00ff00';

                    
               }

            $tooltip= $d['nama_daerah'].'<br>PERSENTASE PELAPORAN DAERAH <br> : <b>'.$PERSENTASE.'% ('.$d['value_melapor'].'/'.$d['jumlah_daerah'].')  </b>';

              $map_data['series'][2]['data'][]=[
                    $d['id_daerah'],
                    $d['nama_daerah'],
                    $PERSENTASE.'%',
                    $status,
                    route('pr.table',['provinsi'=>$d['id_daerah']]),
                    $color,
                    $tooltip
               ];
            # code...
        }


        // dd($map_data['series'][1]);

         return view('output.map.themplate')
        ->with([
            'own_content'=>true,
            'id_map'=>'map_con_888',
            'current_data_db'=>$map_data,
            'file_path'=>'',
            'height'=>400
        ])->render();




    }

    public function list_program_kegiatan_daerah(){

        $tahun=HP::fokus_tahun();
     

        $data=DB::connection('sinkron_prokeg')->table('public.master_daerah as d')
        ->select(
            "d.*",
            DB::raw("null as target_nuwas"),
            DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=d.id) as nama_daerah"),
            DB::raw("(select count(distinct(k.kode_kegiatan)) from prokeg.tb_".$tahun."_kegiatan as k where k.id_urusan=3 and k.id_sub_urusan=12 and status=5 and k.kode_daerah =d.id) as jumlah_kegiatan"),
              DB::raw("(select count(distinct(id_program)) from prokeg.tb_".$tahun."_kegiatan as k where k.id_urusan=3 and k.id_sub_urusan=12 and status=5 and k.kode_daerah=d.id ) as jumlah_program"),

                 DB::raw("(select sum(k.anggaran) from prokeg.tb_".$tahun."_kegiatan as k where k.id_urusan=3 and k.id_sub_urusan=12 and status=5 and k.kode_daerah=d.id ) as jumlah_anggaran"),
            DB::raw("(select sum(k.anggaran) from prokeg.tb_".$tahun."_kegiatan as k where status=5 and k.kode_daerah=d.id ) as jumlah_anggaran_total"),

             DB::raw("(select count(distinct(k.id)) from public.master_daerah as k where k.id = d.id) as jumlah_daerah"),
             
             DB::raw("(select count(distinct(k.kode_daerah)) from prokeg.tb_".$tahun."_kegiatan as k where k.id_urusan=3 and k.id_sub_urusan=12 and status=5 and k.kode_daerah =d.id) as jumlah_daerah_melapor")
        )
        ->orderBy('d.id','asc')
        ->get();

        
        $provinsi=[];

        foreach ($data as $key => $d) {
            $target_nuwas=DB::table('daerah_nuwas')->where('tahun',$tahun)->where('kode_daerah',$d->id)->first();
            if($target_nuwas){
                $d->target_nuwas=$target_nuwas;
            }

            if($d->kode_daerah_parent==null){
                $provinsi[]=$d;
            }
        }

        return view('front.prokeg.table')->with('data',$data)->with('provinsi',$provinsi);
    }



    public function widget_prokeg(){
       $tahun=HP::fokus_tahun();
        $data=DB::connection('sinkron_prokeg')->table('public.master_daerah as d')
        ->select(
            "d.id as kode",
             DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=d.id) as nama_daerah"),
            DB::raw("(select count(distinct(k.kode_kegiatan)) from prokeg.tb_".$tahun."_kegiatan as k where k.id_urusan=3 and k.id_sub_urusan=12 and status=5 and k.kode_daerah=d.id ) as jumlah_kegiatan"),
            DB::raw("(select sum(k.anggaran) from prokeg.tb_".$tahun."_kegiatan as k where k.id_urusan=3 and k.id_sub_urusan=12 and status=5 and k.kode_daerah=d.id ) as jumlah_anggaran"),
            DB::raw("(select sum(k.anggaran) from prokeg.tb_".$tahun."_kegiatan as k where status=5 and k.kode_daerah=d.id ) as jumlah_anggaran_total"),
              DB::raw("(select count(distinct(id_program)) from prokeg.tb_".$tahun."_kegiatan as k where k.id_urusan=3 and k.id_sub_urusan=12 and status=5 and k.kode_daerah=d.id ) as jumlah_program")
         
        )->orderBy('jumlah_kegiatan','DESC')
        ->get();

        return $data;
    }



    public function widget_prokeg_pelaporan(){

        $tahun=HP::fokus_tahun();
        $data=DB::connection('sinkron_prokeg')->table('public.master_daerah as d')
        ->select(
            "d.id as kode",
            "d.nama as nama_daerah",
            DB::raw("(select count(distinct(k.kode_kegiatan)) from prokeg.tb_".$tahun."_kegiatan as k where k.id_urusan=3 and k.id_sub_urusan=12 and status=5 and k.kode_daerah ilike concat(d.id,'%')) as jumlah_kegiatan"),

             DB::raw("(select count(distinct(k.id)) from public.master_daerah as k where k.id ilike concat(d.id,'%')) as jumlah_daerah"),

             DB::raw("(select count(distinct(k.kode_daerah)) from prokeg.tb_".$tahun."_kegiatan as k where k.id_urusan=3 and k.id_sub_urusan=12 and status=5 and k.kode_daerah ilike concat(d.id,'%')) as jumlah_daerah_melapor")
        )
        ->where('d.kode_daerah_parent',null)
        ->orderBy('jumlah_daerah_melapor','DESC')
        ->get();

        return $data;
        
    }
}
