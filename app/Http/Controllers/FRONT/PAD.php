<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use HP;
use DB;
class PAD extends Controller
{
    //

    public function index(Request $request){
    	$tahun=HP::fokus_tahun();
    	if($request->tahun){
    		$tahun=$request->tahun;
    	}
        DB::enableQueryLog(); 
    	$daerah_nuwas=DB::table('daerah_nuwas as dn')->get()->pluck('kode_daerah');
        DB::connection('sinkron_prokeg')->enableQueryLog(); 

    	$data=DB::connection('sinkron_prokeg')->table('pad.pad')
		->leftJoin('pad.master_akun as ma','ma.id','=','pad.kode_akun')    	
    	->whereIn('kode_daerah',$daerah_nuwas)
    	->groupBy('pad.kode_daerah')
    	->groupBy('pad.tahun')
    	->select(
    		'pad.kode_daerah',
    		   	DB::RAW("(select nama from public.master_daerah  as d where d.id::text=pad.kode_daerah::text ) as nama_daerah"),
    		DB::RAW("(select nama from public.master_daerah  as d where d.id::text=left(pad.kode_daerah::text,2) ) as nama_provinsi"),
    		DB::RAW("(tahun::numeric) as tahun"),
    		DB::RAW("sum(case when ma.kat is null then anggaran::numeric else 0 end) as anggaran"),
    		DB::RAW("sum(case when ma.kat is null then realisasi::numeric else 0 end) as realisasi"),
    		DB::RAW("( NULLIF(sum(case when ma.kat is null then anggaran::numeric else 0 end),0) / NULLIF( sum(case when ma.kat is null then realisasi::numeric else 0 end),0) ) as realisasi_persentase")
    	)
        ->orderBy('pad.tahun','desc')
    	->where('tahun','>=',($tahun-2))->where('tahun','<=',($tahun))->get();
         $query = DB::connection('sinkron_prokeg')->getQueryLog();
       // dd(end($query));
        $data_return=[];
        $kode_akun=0;

        $blue_print_data=[];
        for ($i=$tahun; $i>($tahun-3) ;$i--) { 
            $blue_print_data['T'.$i]=array(
                'kode_daerah'=>null,
                'nama_daerah'=>null,
                'nama_provinsi'=>null,
                'anggaran'=>null,
                'realisasi'=>null,
                'realisasi_persentase'=>0,
            );
        }

        foreach ($data as $key => $d) {
             $data_return[$d->kode_daerah]['kode_daerah']=$d->kode_daerah;
             $data_return[$d->kode_daerah]['nama_daerah']=$d->nama_daerah;
             $data_return[$d->kode_daerah]['nama_provinsi']=$d->nama_provinsi;

             $data_return[$d->kode_daerah]['anggaran']=$d->anggaran;
             $data_return[$d->kode_daerah]['realisasi']=$d->realisasi;

             $data_return[$d->kode_daerah]['realisasi_persentase']=$d->realisasi_persentase;



            $data_return[$d->kode_daerah]['tahun']['T'.$d->tahun]=(array)$d;

        }

    


    	return view('front.pad.index')->with([
    		'count_daerah_nuwas'=>count($daerah_nuwas),
    		'data'=>$data_return,
    		'tahun'=>$tahun,
    	]);

    }


    public function detail($kode_daerah,Request $request){
    	$tahun=HP::fokus_tahun();
    	if($request->tahun){
    		$tahun=$request->tahun;
    	}
DB::enableQueryLog();  
    	$daerah=DB::table('master_daerah as pad')->where('id',$kode_daerah)->select(
    		 DB::RAW("(select nama from public.master_daerah  as d where d.id::text=pad.id::text ) as nama_daerah"),
			DB::RAW("(select nama from public.master_daerah  as d where d.id::text=left(pad.id::text,2) ) as nama_provinsi")
    	)->first();
         $query = DB::getQueryLog();
      // dd(end($query));
  DB::connection('sinkron_prokeg')->enableQueryLog();   
    	$data=DB::connection('sinkron_prokeg')->table('pad.pad')
    	->leftJoin('pad.master_akun as ma','ma.id','=','pad.kode_akun')
    	->where([
    		['kode_daerah',$kode_daerah],
    		['tahun','>',($tahun-3)]
    	])
    	->where([
    		['tahun','<=',($tahun)],
    		['kode_daerah',$kode_daerah]
    	])->select(
    		'pad.akun as nama_akun',
    		'ma.kat as kat_akun',
    		'pad.tahun',
    		'pad.kode_akun',
    		'pad.anggaran',
    		'pad.realisasi',
    		DB::RAW("(CASE WHEN NULLIF((NULLIF(realisasi::numeric,0))/(NULLIF(anggaran::numeric,0)),0) IS NULL THEN 0 else  NULLIF((NULLIF(realisasi::numeric,0))/(NULLIF(anggaran::numeric,0)),0) END)::numeric as realisasi_persentase")

    	)->get();
$query = DB::connection('sinkron_prokeg')->getQueryLog();
        //dd(end($query));
    	$data_return=[];
    	$kode_akun=0;

    	$blue_print_data=[];
    	for ($i=$tahun; $i>($tahun-3) ;$i--) { 
    		$blue_print_data['T'.$i]=array(
    			'kode_akun'=>null,
    			'nama_akun'=>null,
    			'kat_akun'=>null,
    			'anggaran'=>null,
    			'realisasi'=>null,
    			'realisasi_persentase'=>0,
    		);
    	}

    	foreach ($data as $key => $d) {
    		$data_return[$d->kode_akun]['nama']=$d->nama_akun;
    		$data_return[$d->kode_akun]['kat_akun']=$d->kat_akun;


    		if(!isset($data_return[$d->kode_akun]['tahun'])){
    			$data_return[$d->kode_akun]['tahun']=$blue_print_data;
    		}
    		$d->anggaran=(float)$d->anggaran;
    		$d->realisasi=(float)$d->realisasi;


    		$dt=(array) $d;

    		$data_return[$d->kode_akun]['tahun']['T'.$d->tahun]=$dt;
    		

    		if(($d->realisasi>0 and $d->realisasi!=null) and ($d->anggaran>0 and !empty($d->anggaran))){
    			$data_return[$d->kode_akun]['tahun']['T'.$d->tahun]['realisasi_persentase']=(($d->realisasi/$d->anggaran)*100);
    		}else{
    			$data_return[$d->kode_akun]['tahun']['T'.$d->tahun]['realisasi_persentase']=0;
    		}

    	}



    	$data_return=array_values($data_return);




    	return view('front.pad.detail')->with([
  
    		'data'=>$data_return,
    		'daerah'=>$daerah,
    		'tahun'=>$tahun,

    	]);

    }
}
