<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
use SAT;
class DASH extends Controller
{
    //

    public function existing($tahun){


    	
    	$data=DB::table('daerah_nuwas')->select(DB::RAW("(COUNT(*)) AS jumlah_daerah,sum(case when tahun=".$tahun." then 1 else 0 end ) as jumlah_prioritas, string_agg(concat('\"',kode_daerah,'\"'),',') as list_kode_pemda"))->first();

    	$id_pemda_l=str_replace('"', "'", $data->list_kode_pemda);
    	$id_pemda=explode(',', $data->list_kode_pemda);

    	$id_urusan=3;
    	$id_sub_urusan=12;


    	$daerah_nuwas=$data->jumlah_daerah;
    	$daerah_prioritas=$data->jumlah_prioritas;

    	$data=DB::connection('rkpd')->table('rkpd.master_'.$tahun.'_kegiatan as k')
    	->select(DB::raw("sum(pagu) as anggaran_total,count(distinct(kodepemda)) as jumlah_pemda"))
    	->whereRAW("(kodepemda in (".$id_pemda_l.") and id_urusan= ".$id_urusan." and id_sub_urusan=".$id_sub_urusan." ) or (kodepemda in (".$id_pemda_l.") and  kode_lintas_urusan=".$id_sub_urusan." )")
    	->first();

    	$total_anggaran=$data->anggaran_total;
    	$total_pemda=$data->jumlah_pemda;

    	$data_jumlah_sl=DB::connection('bppspam')->table(DB::raw("(select max(kode_daerah) as kode_daerah,max(tahun) as tahun ,max(jumlah_unit) as jumlah_sl
			 FROM bppspam.bppspam_data_keterangan  where  tahun < ".$tahun." and kode_daerah  in (".$id_pemda_l.") group by kode_daerah  
			 order by tahun desc) as d "))
    	->select(DB::raw("sum(d.jumlah_sl) as jumlah_sl"))
    	->pluck('jumlah_sl')->first();



    	

    	return array(
	    		'code'=>200,
	    		'data'=>view('front.dash.existing')->with([
	    		'daerah_nuwas'=>$daerah_nuwas,
	    		'daerah_prioritas'=>$daerah_prioritas,
	    		'anggaran_total'=>$total_anggaran,
	    		'total_pemda'=>$total_pemda,
	    		'jumlah_sl'=>$data_jumlah_sl,
	    		'tahun'=>$tahun
	    	])->render()
    	);


    }

    public function kondisi_pdam_bppspam($tahun){

    	$data=DB::table('daerah_nuwas')->select(DB::RAW("(COUNT(*)) AS jumlah_daerah,sum(case when tahun=".$tahun." then 1 else 0 end ) as jumlah_prioritas, string_agg(concat('\"',kode_daerah,'\"'),',') as list_kode_pemda"))->first();

    	$id_pemda_l=str_replace('"', "'", $data->list_kode_pemda);
    	$id_pemda=explode(',', $data->list_kode_pemda);


    	$data=DB::connection('bppspam')->table(DB::RAW("(select max(kategori_pdam) as kategori_pdam,max(tahun) as tahun , kode_daerah from view_bppspam_penilaian_kategori where tahun < ".$tahun." and kode_daerah in (".$id_pemda_l.")  group by kode_daerah) as d"))
    	->select(DB::RAW("max(d.kategori_pdam) as kat , count(d.kode_daerah) as jumlah_pdam"))
    	->groupBy('d.kategori_pdam')
    	->get();


		return array(
	    		'code'=>200,
	    		'data'=>view('front.dash.pdam_bppspam')->with([
		    		'data'=>$data
		    	])->render()
    	);

    }


    public function ikfd($tahun){


    	$data=DB::table('daerah_nuwas')->select(DB::RAW("(COUNT(*)) AS jumlah_daerah,sum(case when tahun=".$tahun." then 1 else 0 end ) as jumlah_prioritas, string_agg(concat('\"',kode_daerah,'\"'),',') as list_kode_pemda"))->first();

    	$id_pemda_l=str_replace('"', "'", $data->list_kode_pemda);



    	$data=DB::connection('pemda')->table('master_ikfd as ik')
    	->select(
    		DB::RAW("(select nama from public.master_daerah as d where d.id=ik.kodepemda ) as nama_daerah"),
    		DB::raw("(select nama from public.master_daerah as p where p.id = left(ik.kodepemda,2)) as nama_provinsi"),
    		DB::RAW("max(kodepemda) as kodepemda,max(nilai) as nilai,max(kategori) as kategori,max(tahun) as tahun"))
    	->groupBy('kodepemda')
    	->orderby('tahun','desc')
    	->whereRAW("kodepemda in (".$id_pemda_l.")")
    	->get();

    	$data_return=[];


    	foreach ($data as $key => $d) {
    		# code...
    		switch (strtoupper($d->kategori)) {
    			case 'SANGAT RENDA':
    				$color='#900C3F';
    				# code...
    				break;
    			case 'RENDAH':
    				$color='#FF5733';
    				# code...
    				break;
    			case 'SEDANG':
    				$color='#FFC300';
    				# code...
    				break;
    			case 'TINGGI':
    				$color='#DAF7A6';
    				# code...
    				break;
    			case 'SANGAT TINGGI':
    				$color=' #7DFF33';
    				# code...
    				break;
    			default:
    				# code...
    				break;
    		}

    		if(!isset($data_retun[$d->kategori])){
    			$data_return[$d->kategori]=[
    				'nama'=>strtoupper($d->kategori),
    				'data'=>[]
    			];
    		}

    		$dom=('<h3><b>'.$d->nama_daerah.' - '.$d->tahun.'</b></h3><br>'.
    		"<h5><b>".$d->nama_provinsi."</b></h5><br><hr>".
    		"Nilai IKFD : ".$d->nilai."<br>".
    		"<b>".strtoupper($d->kategori)."</b>");

    		$data_return[$d->kategori]['data']=[(int)$d->kodepemda,$d->nama_daerah,$d->nilai,strtoupper($d->kategori),strtoupper($d->kategori),route('ikfd.index',['tahun'=>($tahun-1),'kodepemda'=>$d->kodepemda]),$color,$dom];

    	}

    	return array(
	    		'code'=>200,
	    		'data'=>view('front.dash.ikfd')->with([
		    		'data'=>array_values($data_return)
		    	])->render()
    	);
    }


    public function pdam_sat($tahun){

    	$sql=SAT::getdata(null,$tahun);

    	$data=DB::table("daerah_nuwas as d")
    	->leftJoin(DB::raw("(".$sql." ) as pdam"),'pdam.kode_daerah','=','d.kode_daerah')
    	->select(DB::raw("kategori_pdam_kode_1 as kat"),DB::raw("count(d.kode_daerah) as jumlah_pdam"))
    	->groupBy("kategori_pdam_kode_1")
    	->get();

    	return array(
	    		'code'=>200,

	    		'data'=>view('front.dash.pdam_bppspam')->with([
		    		'data'=>$data,
		    		'title'=>'PDAM SAT'
		    	])->render()
    	);
    	


    }



    public function rkpd($tahun){

    	$id_urusan=3;
    	$id_sub_urusan=12;
    	$data=DB::table('daerah_nuwas')->select(DB::RAW("(COUNT(*)) AS jumlah_daerah,sum(case when tahun=".$tahun." then 1 else 0 end ) as jumlah_prioritas, string_agg(concat('\"',kode_daerah,'\"'),',') as list_kode_pemda"))->first();

    	$id_pemda_l=str_replace('"', "'", $data->list_kode_pemda);
    	

    	$data=DB::connection('rkpd')->table('rkpd.master_'.$tahun.'_kegiatan as k')
    	->whereRAW("(kodepemda in (".$id_pemda_l.") and id_urusan= ".$id_urusan." and id_sub_urusan=".$id_sub_urusan." ) or (kodepemda in (".$id_pemda_l.") and  kode_lintas_urusan=".$id_sub_urusan." )")
    	->select(
    		DB::RAW("(select nama from public.master_daerah as d where d.id=k.kodepemda ) as nama_daerah"),
    		DB::raw("(select nama from public.master_daerah as p where p.id = left(k.kodepemda,2)) as nama_provinsi"),
    		DB::RAW("kodepemda,count(distinct(k.id_program)) as jumlah_proram,count(*) as jumlah_kegiatan,sum(k.pagu) as jumlah_anggaran"))
    	->groupBy('kodepemda')
    	->get();

    	return array(
    		'code'=>200,
    		'data'=>view('front.dash.rkpd')->with([
    			'data'=>$data
    		])->render()
    	);


    }

}
