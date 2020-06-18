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
		$kode_daerah=DB::table('public.daerah_nuwas')
		->select(
			'kode_daerah',
			DB::raw("concat(jenis_bantuan,'||',tahun::text,'||',nilai_bantuan) as jenis_bantuan")
		)
		->where('tahun','<=',$tahun+1)
		->orWhere('tahun',null)
		->get()->pluck(['jenis_bantuan'],'kode_daerah')->toArray();


		$data=DB::connection('sinkron_prokeg')->table('public.master_daerah as d')
		->leftJoin('public.master_regional as r','r.kode_daerah','=','d.id')
		->leftJoin(DB::raw("(select * from prokeg.tb_".$tahun."_kegiatan as ka   where kode_lintas_urusan=".$id_sub_urusan." and  ka.status=5  ) as k"),'k.kode_daerah','=','d.id')
		->select(
			DB::raw('max(r.regional) as regional'),
			'd.id as kode_daerah',
			'd.kode_daerah_parent',
			'd.nama as nama_daerah',
			DB::RAW("(case when d.kode_daerah_parent is not null then  (select nama from public.master_daerah as pr where pr.id=d.kode_daerah_parent) else d.nama end) as nama_provinsi"),
			DB::raw("count(distinct(k.id_program)) as jumlah_program"),
			DB::raw("count(k.id) as jumlah_kegiatan"),
			DB::raw("sum(k.anggaran) as jumlah_anggaran"),
			DB::raw("(select sum(anggaran) from prokeg.tb_".$tahun."_kegiatan as ka where ka.kode_daerah=d.id) as jumlah_anggaran_total "),
			DB::raw("replace('".route('d.program',['kode_daerah'=>'xxxxxx'])."','xxxxxx',d.id) as link_detail")
		)
		->whereIn('d.id',array_keys($kode_daerah))
		->groupBy('d.id')
		->get();

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

	public function program($kode_daerah){
		$id_sub_urusan=12;

		$daerah=DB::table('public.master_daerah as d')
		->select(
			'd.*',
			DB::RAW("(case when d.kode_daerah_parent is not null then  (select nama from public.master_daerah as pr where pr.id=d.kode_daerah_parent) else d.nama end) as nama_provinsi")
		)
		->where('d.id',$kode_daerah)->first();
		$tahun=HP::fokus_tahun();
		

		$data=DB::connection('sinkron_prokeg')->table("prokeg.tb_".$tahun."_kegiatan as k")
		->leftJoin(
			"prokeg.tb_".$tahun."_program as p","p.id",'=','k.id_program'
		)
		->leftJoin(
			"prokeg.tb_".$tahun."_ind_program as ip","ip.id_program",'=','k.id_program'
		)
		->select(
			"p.id as id_program",
			"p.kode_daerah",
			"p.kode_program",
			"p.uraian as nama_program",
			"ip.kode_ind as kode_indikator",
			"ip.indikator as nama_indikator",
			"ip.anggaran as jumlah_anggaran_indikator",
			"ip.target_awal as target_awal",
			"ip.target_ahir as target_ahir",
			"ip.satuan as satuan",
			DB::raw("(max(k.id_urusan)) as id_urusan"),
			DB::raw("(max(k.id_sub_urusan)) as id_sub_urusan"),
			DB::raw("(max(k.id_sub_urusan)) as id_sub_urusan"),
			DB::raw("count(k.id) as jumlah_kegiatan"),
			DB::raw("sum(k.anggaran) as jumlah_anggaran"),
			DB::raw("(select sum(anggaran) from prokeg.tb_".$tahun."_kegiatan as ka where ka.kode_daerah='".$kode_daerah."') as jumlah_anggaran_total "),
			DB::raw("replace((replace('".route('d.program.kegiatan',['kode_daerah'=>'xxxxxx','id_program'=>'yyyyyyy'])."','xxxxxx',p.kode_daerah)),'yyyyyyy',p.id::text) as link_detail")
		)
		->where('k.kode_daerah',$kode_daerah)
		->groupBy('p.id','ip.id')
		->where([
			'k.kode_lintas_urusan'=>$id_sub_urusan,
			'k.status'=>5
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
		

		$data=DB::connection('sinkron_prokeg')->table("prokeg.tb_".$tahun."_kegiatan as k")
		->leftJoin(
			"prokeg.tb_".$tahun."_program as p","p.id",'=','k.id_program'
		)
		->leftJoin(
			"prokeg.tb_".$tahun."_ind_kegiatan as ip","ip.id_kegiatan",'=','k.id'
		)
		->select(
			"k.id as id_kegiatan",
			"k.kode_daerah",
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
			DB::raw("(select sum(anggaran) from prokeg.tb_".$tahun."_kegiatan as ka where ka.kode_daerah='".$kode_daerah."') as jumlah_anggaran_total "),
			DB::raw("(select sum(pagu) from prokeg.tb_".$tahun."_s_dana as su where su.id_kegiatan=k.id and kode_sumber_dana_supd=1) as jumlah_anggaran_apbd "),
			DB::raw("replace(replace((replace('".route('d.program.kegiatan.sumberdana',['kode_daerah'=>'xxxxxx','id_program'=>'yyyyyyy','id_kegiatan'=>'zzzzzzzz'])."','xxxxxx',p.kode_daerah)),'yyyyyyy',p.id::text),'zzzzzzzz',k.id::text) as link_detail")
		)
		->where('k.kode_daerah',$kode_daerah)
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
