<?php

namespace App\Http\Controllers\DASH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
use Validator;

class PROKEG extends Controller
{
    //

    public function index(){
    	$tahun=HP::fokus_tahun();
    	$data=DB::connection('sinkron_prokeg')->table('public.master_daerah as d')
    	->select(
    		'd.*',
    		DB::raw("null as target_nuwas"),
    		DB::raw("null as jenis_bantuan"),
    		 DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=d.id) as nama_daerah"),
    		DB::raw("(select count(*) from rkpd.master_".$tahun."_kegiatan  as k where ( k.kodepemda=d.id and k.id_urusan=3 and k.id_sub_urusan=12 and status=5) or ( k.kodepemda=d.id and k.id_urusan=3 and k.kode_lintas_urusan=12 and status=5)) as jumlah_kegiatan")
    	)->orderBy('id','ASC')->get();
    	
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





    	return view('dash.prokeg.index')->with('data',$data)->with('provinsi',$provinsi);
    }

    public function xdetail($id){
    	$data=DB::connection('sinkron_prokeg')->table("prokeg.tb_".$tahun."_kegiatan  as k")
		->leftJoin("prokeg.tb_".$tahun."_program as p",'p.id','=','k.id_program');

    }

    public function detail($id){
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

		return view('dash.prokeg.detail')->with(['data'=>$data,'daerah'=>$daerah]);




    }

    public function pemetaan_store(Request $request){
    	$tahun=HP::fokus_tahun();

    	$valid=Validator::make($request->all(),[
    		'ind_keg'=>'required|numeric',
    		'ind_keg_pusat'=>'required|numeric',

    	]);

    	if($valid->fails()){
    		return back();
    	}else{

    		$in=DB::connection('sinkron_prokeg')->table('kebijakan.tb_'.$tahun.'_ind_keg_pen_pusat')
    		->where('id_ind',$request->ind_keg)->first();

    		if($in){
    			$in=DB::connection('sinkron_prokeg')->table('kebijakan.tb_'.$tahun.'_ind_keg_pen_pusat')
		    		->where('id_ind',$request->ind_keg)->update([
		    			'id_ind_pusat'=>$request->ind_keg_pusat
		    		]);

    		}else{

    			$in=DB::connection('sinkron_prokeg')->table('kebijakan.tb_'.$tahun.'_ind_keg_pen_pusat')
		    		->insertOrIgnore([
		    			'id_ind_pusat'=>$request->ind_keg_pusat,
		    			'id_ind'=>$request->ind_keg
		    		]);

    		}

    	}

    	return back();

    }


    public function pemetaan($id_ind_k){
    	$tahun=HP::fokus_tahun();
    	$rpjmn_ind_table=HP::get_rpjmn_table('indikator');
    	$rpjmn_table=HP::get_rpjmn_table();
    	$dekade=HP::get_tahun_rpjmn();

    	$daerah=DB::connection('sinkron_prokeg')->table('prokeg.tb_'.$tahun.'_ind_kegiatan as d')->select(
    		 DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=d.kode_daerah) as nama_daerah")
    	)->where('id',$id_ind_k)->first();

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
		->where('indk.id',$id_ind_k)
		->orderBy('id_p','ASC')
		->orderBy('id_ind_p','ASC')
		->orderBy('id_k','ASC')
		->orderBy('id_ind_k','ASC')
		->orderBy('id_indp_ps','ASC')


		->first();

		$pn=DB::connection('sinkron_prokeg')->table('kebijakan.'.$rpjmn_table)->where('jenis','PN')->where('id',5)->get();

		return view('dash.prokeg.pemetaan')->with(['d'=>$data,'daerah'=>$daerah,'pn'=>$pn]);
    }
}
