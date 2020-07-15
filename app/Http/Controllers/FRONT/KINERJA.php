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

    	$data=DB::table('daerah_nuwas')->select(DB::RAW("(COUNT(*)) AS jumlah_daerah,sum(case when tahun=".$tahun." then 1 else 0 end ) as jumlah_prioritas, string_agg(concat('\"',kode_daerah,'\"'),',') as list_kode_pemda"))->first();

    	$id_pemda_l=str_replace('"', "'", $data->list_kode_pemda);

   

    	if($request->tipe){
    		$tipe=$request->tipe;
    	}else{
    		$tipe=1;
    	}


    	$data=DB::connection('rkpd')->table('rkpd.master_'.$tahun."_kegiatan as k")
    	->leftJoin("rkpd.master_".$tahun.'_kegiatan_indikator as i',"k.id",'=','i.id_kegiatan')
    	->whereRaw(
			"((k.id_sub_urusan =".$id_sub_urusan."  and k.kodepemda in (".$id_pemda_l.") and i.jenis IS NOT NULL".") OR ((k.kode_lintas_urusan =".$id_sub_urusan."  and k.kodepemda in (".$id_pemda_l.") and i.jenis IS NOT NULL ".")))")
    	->select(
    		DB::raw("(select nama from master_daerah where id=k.kodepemda) as nama_daerah"),
    		DB::raw("(select nama from master_daerah where id=left(k.kodepemda,2)) as nama_provinsi"),
    		DB::RAW("sum(case when i.jenis=1 then 1 else 0 end) as indikator_dokumen"),
    		DB::RAW("sum(case when i.jenis=2 then 1 else 0 end) as indikator_sr"),
    		DB::RAW("sum(case when i.jenis=3 then 1 else 0 end) as indikator_lokasi"),
    		DB::RAW("sum(i.pagu)::numeric as anggaran_i")

    	)
    	->groupBy('k.kodepemda')
		->get();


		dd($data);


    }


    public function detail($kodepemda){
    	$data=DB::connection('rkpd')->table('rkpd.master_'.$tahun."_kegiatan as k")
    	->leftJoin("rkpd.master_".$tahun.'_kegiatan_indikator as i',"k.id",'=','i.id_kegiatan')
    	->leftJoin("rkpd.master_".$tahun."_program as p",'p.id','=','k.id_program')
    	->whereRaw(
			"((k.id_sub_urusan =".$id_sub_urusan."  and k.kodepemda in (".$id_pemda_l.") and i.jenis <> NULL".") OR ((k.kode_lintas_urusan =".$id_sub_urusan."  and k.kodepemda in (".$id_pemda_l.") and i.jenis <> NULL ".")))")
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
    			k.kodekegiatan,
    			k.uraikegiatan,
    			p.kodeprogram,
    			p.uraiprogram,
    			i.tolokukur,
    			i.target,
    			i.jenis,
    			i.pagu,
    			i.satuan")
    	)
		->get();
    }
}
