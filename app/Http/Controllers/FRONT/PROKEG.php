<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Carbon\Carbon;
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
    	$tahun=2020;
        $id_dom='per_u';

        DB::connection('sinkron_prokeg')->enableQueryLog();
    	$data=DB::connection('sinkron_prokeg')
        ->table('public.master_urusan as u')
        ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'),'u.id','=','k.id_urusan')
        ->whereNotNull('k.id_sub_urusan')
        ->whereNotNull('k.id_urusan')
        ->whereNotNull('k.id_program')
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

        $tahun=2020;
        $id_dom='per_suu';

        $urusan =DB::table('master_urusan')->find($id);
        DB::connection('sinkron_prokeg')->enableQueryLog();
        $data=DB::connection('sinkron_prokeg')
        ->table('public.master_sub_urusan as u')
        ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'),'u.id','=','k.id_sub_urusan')
        ->whereNotNull('k.id_sub_urusan')
        ->whereNotNull('k.id_urusan')
        ->whereNotNull('k.id_program')

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

        $tahun=2020;
        $id_dom='per_suu';

        $urusan =DB::table('master_sub_urusan')->find($id);
        DB::connection('sinkron_prokeg')->enableQueryLog();
        $data=DB::connection('sinkron_prokeg')
        ->table('prokeg.tb_'.$tahun.'_'.'kegiatan as k')
        ->whereNotNull('k.id_sub_urusan')
        ->whereNotNull('k.id_urusan')
        ->whereNotNull('k.id_program')
        ->where('k.id_sub_urusan',$id)
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
    $tahun=2020;
        $id_dom='per_provinsi';

        $data=DB::connection('sinkron_prokeg')
        ->table('public.master_daerah as u')
        ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'),DB::raw("left(k.kode_daerah,2)"),'=',DB::raw("CONCAT(u.id)"))
        ->whereNotNull('k.id_program')
        ->where('k.status',5)
        ->where('k.id_sub_urusan',11)

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
    $tahun=2020;
        $id_dom='per_kota';

        $daerah=DB::table('master_daerah')->find($id);
        $data=DB::connection('sinkron_prokeg')
        ->table('public.master_daerah as u')
        ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'),'k.kode_daerah','ilike','u.id')
        ->whereNotNull('k.id_urusan')
        ->whereNotNull('k.id_program')
         ->where('k.status',5)
        ->where('k.id_sub_urusan',11)
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
            $tahun=2020;
            $daerah=DB::table('master_daerah')->find($id);

            $urusan=DB::table('master_urusan')->whereIn('id',[3])->get();
            $data=DB::connection('sinkron_prokeg')
            ->table(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'))
            ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'program as p'),'p.id','=','k.id_program')
            ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'ind_program as ip'),'ip.id_program','=','k.id_program')
            ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'ind_kegiatan as ik'),'ik.id_kegiatan','=','k.id')
            ->select(
                "k.id_urusan as id_urusan",
                DB::raw("(select nama from public.master_urusan where id=k.id_urusan limit 1) as nama_urusan"),
                "k.id_sub_urusan as id_sub_urusan",
                DB::raw("(select nama from public.master_sub_urusan where id=k.id_sub_urusan limit 1) as nama_sub_urusan"),
                "p.id as id_program",
                "p.uraian as nama_program",
                "ip.id as id_ind_p",
                "ip.indikator as nama_ind_p",
                "ip.target_awal as target_ind_p",
                "ip.satuan as satuan_ind_p",

                "k.id as id_kegiatan",
                "k.uraian as nama_kegiatan",
                "ik.id as id_ind_k",
                "ik.indikator as nama_ind_k",
                "ik.target_awal as target_ind_k",
                "ik.satuan as satuan_ind_k",
                "k.anggaran as anggaran"
            )
            ->where('k.status',5)
             ->where('k.id_sub_urusan',11)
            ->whereNotNull('k.id_urusan')

            ->whereNotNull('k.id_program')
            ->where('k.kode_daerah',$id);

            if($request->urusan && (($request->urusan!=[null]))){
                $data->whereIn('k.id_urusan',$request->urusan);
            }

            $data=$data->orderBy('k.id_urusan','ASC')
            ->orderBy('k.id_sub_urusan','ASC')
            ->orderBy('p.id','ASC')
            ->orderBy('ip.id','ASC')
            ->orderBy('k.id','ASC')
            ->orderBy('ik.id','ASC')
            ->get();

            $tahun=2020;
            $catatan=DB::connection('sinkron_analis')->table('tb_'.$tahun.'_rkpd_catatan_daerah as d')
            ->where('kode_daerah',$id)
            ->select('d.*','u.name')
            ->leftJoin('public.users as u','u.id','=','d.id_user')
            ->first();





            return view('front.prokeg.table_daerah')
            ->with('urusan',$urusan)
            ->with('catatan',$catatan)
            ->with('name_right_side_bar','Buat Catatan')
            ->with('data',$data)->with('daerah',$daerah);

        }


        public function detail_program($id){
            $tahun=2020;
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
        $tahun=2020;
        $id_dom='per_d_u';
        $daerah=DB::table('master_daerah')->find($id);
        DB::connection('sinkron_prokeg')->enableQueryLog();
        $data=DB::connection('sinkron_prokeg')
        ->table('public.master_urusan as u')
        ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'),'u.id','=','k.id_urusan')
         ->where('k.status',5)
        ->where('k.id_sub_urusan',11)
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

        $tahun=2020;
        $id_dom='per_suu';
        $daerah =DB::table('master_daerah')->find($kode_daerah);

        $urusan =DB::table('master_urusan')->find($id_urusan);
        DB::connection('sinkron_prokeg')->enableQueryLog();
        $data=DB::connection('sinkron_prokeg')
        ->table('public.master_sub_urusan as u')
        ->Leftjoin(DB::raw('prokeg.tb_'.$tahun.'_'.'kegiatan as k'),'u.id','=','k.id_sub_urusan')
           ->where('k.status',5)
        ->where('k.id_sub_urusan',11)
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
        $tahun=2020;
        $id_dom='per_suu';
        $daerah=DB::table('master_daerah')->find($kode_daerah);
        $urusan =DB::table('master_sub_urusan')->find($id_sub_urusan);
        DB::connection('sinkron_prokeg')->enableQueryLog();
        $data=DB::connection('sinkron_prokeg')
        ->table('prokeg.tb_'.$tahun.'_'.'kegiatan as k')
         ->where('k.status',5)
        ->where('k.id_sub_urusan',11)
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
        $tahun=2020;
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
        $tahun=2020;

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



}
