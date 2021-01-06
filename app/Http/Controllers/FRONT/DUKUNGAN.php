<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;

class DUKUNGAN extends Controller
{
    //
	public function index(){
		$id_urusan=3;
		$id_sub_urusan=12;
		$tahun=HP::fokus_tahun();
		DB::enableQueryLog();  
		$kode_daerah=DB::table('public.daerah_nuwas')
		->select(
			'kode_daerah',
			DB::raw("concat(jenis_bantuan,'||',tahun::text,'||',nilai_bantuan) as jenis_bantuan")
		)
		->where('tahun','<=',($tahun+1))
		->get()->pluck(['jenis_bantuan'],'kode_daerah')->toArray();
 $query = DB::getQueryLog();
        // dd(end($query));
	   DB::connection('sinkron_prokeg')->enableQueryLog();  
		$data=DB::connection('sinkron_prokeg')->table('public.master_daerah as d')
		->leftJoin('public.master_regional as r','r.kode_daerah','=','d.id')
		->leftJoin(DB::raw("(select * from rkpd.master_".$tahun."_kegiatan as ka   where (kode_lintas_urusan=".$id_sub_urusan." and  ka.status=5) or ((id_sub_urusan=".$id_sub_urusan." and  ka.status=5))   ) as k"),'k.kodepemda','=','d.id')
		->select(
			DB::raw('max(r.regional) as regional'),
			'd.id as kode_daerah',
			'd.kode_daerah_parent',
			'd.nama as nama_daerah',
			DB::RAW("(case when d.kode_daerah_parent is not null then  (select nama from public.master_daerah as pr where pr.id=d.kode_daerah_parent) else d.nama end) as nama_provinsi"),
			DB::raw("count(distinct(k.id_program)) as jumlah_program"),
			DB::raw("count(k.id) as jumlah_kegiatan"),
			DB::raw("sum(k.pagu::numeric) as jumlah_anggaran"),
			DB::raw("(select sum(pagu::numeric) from rkpd.master_".$tahun."_kegiatan as ka where ka.kodepemda=d.id) as jumlah_anggaran_total "),
			DB::raw("(select count(*) from rkpd.master_".$tahun."_kegiatan as ka where ka.kodepemda=d.id limit 1) as terdapat_data_rkpd_di_sistem "),
			DB::raw("(select max(st.status) from rkpd.master_".$tahun."_status as st where st.kodepemda=d.id) as status_data_sipd "),
			DB::raw("replace('".route('d.detail',['kode_daerah'=>'xxxxxx'])."','xxxxxx',d.id) as link_detail")
		)
		->whereIn('d.id',array_keys($kode_daerah))
		->groupBy('d.id')
		->get();
		 $query = DB::connection('sinkron_prokeg')->getQueryLog();
        //dd(end($query));
		$data_return=[];
		$kode_provinsi=[];
		$regional=[];

		foreach ($data as $key => $d) {
			$d=(array)$d;
			$th=(int)explode('||',$kode_daerah[$d['kode_daerah']])[1];
			$d['jenis_bantuan']=explode('||', $kode_daerah[$d['kode_daerah']])[0];
			$d['tahun']=($th!=1?$th:null);
			if(!$d['regional']){
				
			}else{
				$regional[strtolower(str_replace(' ','' ,str_replace(',', '', $d['regional'])))]=$d['regional'];

			}

			$data_return[$d['kode_daerah']]=$d;

			if($d['kode_daerah_parent']){
				$kode_provinsi[]=$d['kode_daerah_parent'];
			}
		}


		$provinsi=DB::table('public.master_daerah')
		->whereIn('id',$kode_provinsi)->get();

		// dd($data_return);

		return view('front.v2.dukungan.index')->with([
			'tahun'=>$tahun,
			'data'=>array_values($data_return),
			'provinsi'=>$provinsi,
			'regional'=>$regional
		]);

	}


	public function detail($kodepemda,Request $request){
		$tahun=HP::fokus_tahun();

		if($request->tahun){
			$tahun=$request->tahun;
		}
		$id_sub_urusan=12;
		$schema='rkpd.';

		$daerah=DB::table('public.master_daerah as d')
		->select(
			'd.*',
			DB::RAW("(case when d.kode_daerah_parent is not null then  (select nama from public.master_daerah as pr where pr.id=d.kode_daerah_parent) else d.nama end) as nama_provinsi")
		)
		->where('d.id',$kodepemda)->first();
		DB::connection('sinkron_prokeg')->enableQueryLog(); 
		$data=DB::connection('sinkron_prokeg')->table($schema.'master_'.$tahun.'_program as p')
		->leftJoin($schema.'master_'.$tahun.'_program_capaian as c','p.id','=','c.id_program')
		->leftJoin($schema.'master_'.$tahun.'_kegiatan as k','p.id','=','k.id_program')
		->leftJoin($schema.'master_'.$tahun.'_kegiatan_indikator as i','k.id','=','i.id_kegiatan')
		->leftJoin($schema.'master_'.$tahun.'_kegiatan_sumberdana as ksd','ksd.id_kegiatan','=','k.id')
		->leftJoin('public.master_urusan as u','u.id','=','k.id_urusan')
		->leftJoin('public.master_sub_urusan as su','su.id','=','k.id_sub_urusan')
		->select(
			DB::raw('p.kodepemda as kodepemda'),
			DB::raw("(select nama from public.master_daerah as d where k.kodepemda=d.id limit 1) as nama_daerah"),
			DB::raw("(select nama from public.master_daerah as d where left(k.kodepemda,2)=d.id limit 1) as nama_provinsi"),
			DB::raw('p.id as id_p'),
			DB::raw('c.id as id_c'),
			DB::raw('k.id as id_k'),
			DB::raw('i.id as id_i'),
			"p.kodeskpd",
			"p.uraiskpd",
			"p.kodebidang",
			"p.uraibidang",
			"p.id_urusan",
			DB::raw('p.kodeprogram as kode_p'),
			DB::raw('k.kodekegiatan as kode_k'),
			DB::raw('c.kodeindikator as kode_c'),
			DB::raw('i.kodeindikator as kode_i'),
			DB::raw('p.uraiprogram as urai_p'),
			DB::raw('c.tolokukur as urai_c'),
			DB::raw('c.target as target_c'),
			DB::raw('c.satuan as satuan_c'),
			DB::raw('k.uraikegiatan as urai_k'),
			DB::raw('i.tolokukur as urai_i'),
			DB::raw('i.target as target_i'),
			DB::raw('i.satuan as satuan_i'),
			DB::raw("k.id_urusan as id_sub_urusan"),
			DB::raw("u.nama as urai_u"),
			DB::raw("su.nama as urai_s"),
			DB::raw("'' as kode_lintas_urusan_k"),
			DB::raw('k.pagu as anggaran_k'),
			DB::raw(" (case when k.jenis=1 then 'UTAMA' when k.jenis=2 then 'PENDUKUNG' else null end) as urai_jenis_k"),
			DB::raw('i.pagu as anggaran_i'),
			DB::raw('k.jenis as kode_jenis_k'),
			DB::raw('ksd.id as id_ksd'),
			DB::raw('ksd.kodesumberdana as kode_ksd'),
			DB::raw('ksd.sumberdana as urai_ksd'),
			DB::raw('ksd.pagu as anggaran_ksd')

		)
		->orderBy('k.id_urusan','desc')
		->orderBy('k.id_program','asc')
		->orderBy('c.id_program','asc')
		->orderBy('k.id','asc')
		->orderBy('i.id_kegiatan','asc');
		
		$data=$data->whereRaw(
				"(k.id_sub_urusan =".$id_sub_urusan."  and k.kodepemda ='".$kodepemda."') OR ((k.kode_lintas_urusan =".$id_sub_urusan."  and k.kodepemda ='".$kodepemda."'))")->get();

$query = DB::connection('sinkron_prokeg')->getQueryLog();
       // dd(end($query));
		return view('front.v2.dukungan.detail')->with(['data'=>$data,'daerah'=>$daerah,'tahun'=>$tahun]);

	}

	public function program($kode_daerah){
		$id_sub_urusan=12;

		$daerah=DB::table('public.master_daerah as d')
		->select(
			'd.*',
			DB::RAW("(case when d.kode_daerah_parent is not null then  (select nama from public.master_daerah as pr where pr.id=d.kode_daerah_parent) else d.nama end) as nama_provinsi")
		)
		->where('d.id',$kode_daerah)->first();
		$tahun=HP::fokus_tahun();
		

		$data=DB::connection('sinkron_prokeg')->table("rkpd.master_".$tahun."_kegiatan as k")
		->leftJoin(
			"rkpd.master_".$tahun."_program as p","p.id",'=','k.id_program'
		)
		->leftJoin(
			"rkpd.master_".$tahun."_program_capaian as ip","ip.id_program",'=','k.id_program'
		)
		->select(
			DB::raw("p.id as id_program"),
			DB::raw("p.kodepemda as kode_daerah"),
			DB::raw("p.kodeprogram as kode_program"),
			DB::raw("p.uraiprogram as nama_program"),
			DB::raw("ip.kodeindikator as kode_indikator"),
			DB::raw("ip.tolokukur as nama_indikator"),
			"ip.pagu as jumlah_anggaran_indikator",
			"ip.target as target_awal",
			DB::raw("'' as target_ahir"),
			"ip.satuan as satuan",
			DB::raw("(max(k.id_urusan)) as id_urusan"),
			DB::raw("(max(k.id_sub_urusan)) as id_sub_urusan"),
			DB::raw("count(k.id) as jumlah_kegiatan"),
			DB::raw("sum(k.pagu) as jumlah_anggaran"),
			DB::raw("(select sum(pagu) from rkpd.master_".$tahun."_kegiatan as ka where ka.kodepemda='".$kode_daerah."') as jumlah_anggaran_total "),
			DB::raw("replace((replace('".route('d.program.kegiatan',['kode_daerah'=>'xxxxxx','id_program'=>'yyyyyyy'])."','xxxxxx',p.kodepemda)),'yyyyyyy',p.id::text) as link_detail")
		)
		->groupBy('p.id','ip.id')
		->where([
			['k.kode_lintas_urusan','=',$id_sub_urusan],
			['k.status','=',5],
			['k.kodepemda','=',$kode_daerah]
		])
		->orWhere([
			['k.id_sub_urusan','=',$id_sub_urusan],
			['k.status','=',5],
			['k.kodepemda','=',$kode_daerah]
		])
		->get();



		return view('front.v2.dukungan.program')->with([
			'tahun'=>$tahun,
			'data'=>$data,
			'daerah'=>$daerah
		]);



	}

	public function kegiatan($kode_daerah,$id_program){
		$id_sub_urusan=12;
		$daerah=DB::table('public.master_daerah as d')
		->select(
			'd.*',
			DB::RAW("(case when d.kode_daerah_parent is not null then  (select nama from public.master_daerah as pr where pr.id=d.kode_daerah_parent) else d.nama end) as nama_provinsi")
		)
		->where('d.id',$kode_daerah)->first();
		$tahun=HP::fokus_tahun();
		

		$data=DB::connection('sinkron_prokeg')->table("rkpd.master_".$tahun."_kegiatan as k")
		->leftJoin(
			"rkpd.master_".$tahun."_program as p","p.id",'=','k.id_program'
		)
		->leftJoin(
			"rkpd.master_".$tahun."_ind_kegiatan as ip","ip.id_kegiatan",'=','k.id'
		)
		->select(
			"k.id as id_kegiatan",
			"k.kodepemda",
			"k.kode_kegiatan",
			"k.uraian as nama_kegiatan",
			"ip.kode_ind as kode_indikator",
			"ip.indikator as nama_indikator",
			"ip.anggaran as jumlah_anggaran_indikator",
			"ip.target_awal as target_awal",
			"ip.target_ahir as target_ahir",
			"ip.satuan as satuan",
			DB::raw("((k.id_urusan)) as id_urusan"),
			DB::raw("((k.id_sub_urusan)) as id_sub_urusan"),
			DB::raw("((k.id_sub_urusan)) as id_sub_urusan"),
			DB::raw("(k.anggaran) as jumlah_anggaran"),
			DB::raw("(select sum(anggaran) from rkpd.master_".$tahun."_kegiatan as ka where ka.kode_daerah='".$kode_daerah."') as jumlah_anggaran_total "),
			DB::raw("(select sum(pagu) from rkpd.master_".$tahun."_s_dana as su where su.id_kegiatan=k.id and kode_sumber_dana_supd=1) as jumlah_anggaran_apbd "),
			DB::raw("replace(replace((replace('".route('d.program.kegiatan.sumberdana',['kode_daerah'=>'xxxxxx','id_program'=>'yyyyyyy','id_kegiatan'=>'zzzzzzzz'])."','xxxxxx',p.kode_daerah)),'yyyyyyy',p.id::text),'zzzzzzzz',k.id::text) as link_detail")
		)
		->where('k.kodepemda',$kode_daerah)
		->where([
			'k.kode_lintas_urusan'=>$id_sub_urusan,
			'k.status'=>5,
			'k.id_program'=>$id_program
		])
		->get();

		// dd($data);


		return view('front.v2.dukungan.kegiatan')->with([
			'tahun'=>$tahun,
			'data'=>$data,
			'daerah'=>$daerah
		]);


	}

}
