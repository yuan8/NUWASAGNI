<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
class KINERJA extends Controller
{
    //

    public function index(Request $request){
    	$id_sub_urusan=12;
    	$id_urusan=3;

    	if($request->tahun){
    		$tahun=$request->tahun;
    	}else{
    		$tahun=HP::fokus_tahun();
    	}
      DB::enableQueryLog();  
        $data=DB::table('daerah_nuwas')->select(DB::RAW("(COUNT(*)) AS jumlah_daerah,sum(case when tahun=".$tahun." then 1 else 0 end ) as jumlah_prioritas, string_agg(concat('\"',kode_daerah,'\"'),',') as list_kode_pemda"))->first();
        $query = DB::getQueryLog();
       // dd(end($query));
    	$id_pemda_l=str_replace('"', "'", $data->list_kode_pemda);
 
    	

      DB::connection('rkpd')->enableQueryLog();   
    	$data=DB::connection('rkpd')->table('rkpd.master_'.$tahun."_kegiatan as k")
    	->leftJoin("rkpd.master_".$tahun.'_kegiatan_indikator as i',"k.id",'=','i.id_kegiatan')
    	->whereRaw(
			"((k.id_sub_urusan =".$id_sub_urusan."  and k.kodepemda in (".$id_pemda_l.") and i.jenis IS NOT NULL".") OR ((k.kode_lintas_urusan =".$id_sub_urusan."  and k.kodepemda in (".$id_pemda_l.") and i.jenis IS NOT NULL ".")))")
    	->select(
            DB::RAW("k.kodepemda as kodepemda"),
    		DB::raw("(select nama from master_daerah where id=k.kodepemda) as nama_daerah"),
    		DB::raw("(select nama from master_daerah where id=left(k.kodepemda,2)) as nama_provinsi"),
    		DB::RAW("sum(case when i.jenis=1 then 1 else 0 end) as indikator_dokumen"),
    		DB::RAW("sum(case when i.jenis=2 then 1 else 0 end) as indikator_sr"),
    		DB::RAW("sum(case when i.jenis=3 then 1 else 0 end) as indikator_lokasi"),
    		DB::RAW("sum(i.pagu)::numeric as anggaran_i")

    	)
    	->groupBy('k.kodepemda')
		->get();
        $query = DB::connection('rkpd')->getQueryLog();
        //dd(end($query));

 
		return view('front.kinerja.index')->with('data',$data);


    }


    public function detail($kodepemda,Request $request){
        $tahun=HP::fokus_tahun();

        if($request->tahun){
            $tahun=$request->tahun;
        }
        



        if($request->tipe){
            $tipe=$request->tipe;
        }else{
            $tipe=1;
        }

        $id_sub_urusan=12;
        $id_urusan=3;

        DB::enableQueryLog(); 
        $data=DB::table('daerah_nuwas')->select(DB::RAW("(COUNT(*)) AS jumlah_daerah,sum(case when tahun=".$tahun." then 1 else 0 end ) as jumlah_prioritas, string_agg(concat('\"',kode_daerah,'\"'),',') as list_kode_pemda"))->first();
        $query = DB::getQueryLog();
        //dd(end($query));
        $id_pemda_l=str_replace('"', "'", $data->list_kode_pemda);

   
        $daerah=DB::table('master_daerah as d')->where('id',$kodepemda)->select(
            DB::raw("d.nama as nama_daerah"),
            DB::raw("(select nama from public.master_daerah as p where p.id = left(d.id,2)) as nama_provinsi")
        )->first();


        DB::connection('rkpd')->enableQueryLog(); 
    	$data=DB::connection('rkpd')->table('rkpd.master_'.$tahun."_kegiatan as k")
    	->leftJoin("rkpd.master_".$tahun.'_kegiatan_indikator as i',"k.id",'=','i.id_kegiatan')
    	->leftJoin("rkpd.master_".$tahun."_program as p",'p.id','=','k.id_program')
    	->whereRaw(
			"((k.id_sub_urusan =".$id_sub_urusan."  and k.kodepemda in (".$id_pemda_l.") and i.jenis IS NOT NULL".") OR ((k.kode_lintas_urusan =".$id_sub_urusan."  and k.kodepemda in (".$id_pemda_l.") and i.jenis IS NOT  NULL ".")))")
    	->select(
    		DB::raw("(select nama from master_daerah where id=k.kodepemda) as nama_daerah"),
    		DB::raw("(select nama from master_daerah where id=left(k.kodepemda,2)) as nama_provinsi"),
    		DB::RAW("p.id as id_p,
    			k.id as id_k,
    			i.id as id_i,
    			p.kodeskpd,
    			p.uraiskpd,
    			p.kodebidang,
    			p.uraibidang,
    			k.kodekegiatan as kode_k,
    			k.uraikegiatan as urai_k,
                k.pagu as anggaran_k,
    			p.kodeprogram as kode_p,
    			p.uraiprogram as urai_p,
    			i.tolokukur as urai_i,
                i.kodeindikator as kode_i,
    			i.target as target_i,
    			i.jenis as jenis_i,
    			i.pagu as anggaran_i,
    			i.satuan as satuan_i")
    	)
        ->where('i.jenis',$tipe)
        ->where('k.kodepemda',$kodepemda)
        ->orderBy('p.id','asc')
        ->orderBy('k.id','asc')
        ->orderBy('i.jenis','asc')
		->get();
         $query = DB::connection('rkpd')->getQueryLog();
        //dd(end($query));
        return view('front.kinerja.detail')->with(['data'=>$data,'tipe'=>$tipe,'daerah'=>$daerah]);


    }
}
