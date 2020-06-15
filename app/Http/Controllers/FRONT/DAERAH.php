<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;

class DAERAH extends Controller
{
    //

    public function api_snap($kode_daerah){
    	$tahun=HP::fokus_tahun();

    	$data=(array)DB::table('public.master_daerah as d')
    	->select(
    		DB::raw('d.id as kode_daerah'),
    		DB::raw("(select concat(nama_pdam,' -> ',kategori_pdam) from public.pdam  where pdam.kode_daerah = d.id ) as pdam "),
    		 DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=d.id) as nama_daerah"),
    		 DB::raw("(select concat(tahun,'->',case when (nilai_bantuan is not null) then nilai_bantuan else 0 end,'->',case when (jenis_bantuan is not null) then jenis_bantuan else '[]' end) from public.daerah_nuwas as n where n.kode_daerah=d.id order by id desc limit 1) as target_nuwas"),
    		 DB::raw("(SELECT string_agg(distinct(jenis),'@') 
			FROM public.dokumen_kebijakan_daerah as f where f.kode_daerah =d.id and tahun >=".$tahun." and tahun_selesai >=".$tahun.") as file_kebijakan")

    	)->where('d.id',$kode_daerah)
    	->first();

    	$program_kegiatan=DB::connection('sinkron_prokeg')->table('prokeg.tb_'.$tahun.'_kegiatan as k')
    	->select(
    		DB::raw("count(*) as jumlah_kegiatan"),
    		DB::raw("count(distinct(id_program)) as jumlah_program")
    	)->where(
    		[
    			'id_urusan'=>3,
    			'id_sub_urusan'=>4,
    			'status'=>5,
    			'kode_daerah'=>$kode_daerah
    		]
    	)->first();

    	$jumlah_program=0;
    	$jumlah_kegiatan=0;


    	if($program_kegiatan){
    		$jumlah_kegiatan=$program_kegiatan->jumlah_kegiatan;
    		$jumlah_program=$program_kegiatan->jumlah_program;

    	}else{

    	}

    	$data['jumlah_program']=$jumlah_program;
    	$data['jumlah_kegiatan']=$jumlah_kegiatan;

    	return array(
    		'title'=>$data['nama_daerah'],
    		'data'=>view('front.daerah.snap')->with('data',$data)->render());
    	// return $data;





    }
}
