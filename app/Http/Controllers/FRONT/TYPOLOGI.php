<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
class TYPOLOGI extends Controller
{
    //


    public function index(){
    	$tahun=HP::fokus_tahun();

    	$kode_daerah=DB::table('public.daerah_nuwas')
		->where('tahun',$tahun)
		->select('kode_daerah','jenis_bantuan','nilai_bantuan')
		->get()
		->pluck('jenis_bantuan','kode_daerah')
		->toArray();

    	$data=DB::connection('sinkron_prokeg')
    	->table('public.tipologi_dinas as td')
    	->select(
    		DB::raw("(select nama from public.master_daerah d  where d.id = td.kode_daerah) as nama_daerah"),
    		DB::raw("case when length(td.kode_daerah) <3 then '' else 
    		 (select nama from public.master_daerah d  where d.id = td.kode_daerah) end as nama_provinsi"), 
    		'td.*',
    		DB::raw("replace('".route('ty.daerah',['kode_daerah'=>'xxxxxx'])."','xxxxxx',td.kode_daerah) as link_detail")
    	)
    	->whereIn('td.kode_daerah',array_keys($kode_daerah))
    	->orderBy('td.kode_daerah','asc')
    	->get();



    	return view('front.v2.tipologi.index')
    	->with(
    		[
    			'data'=>$data,
    		]
    	);

    }

    public function detail_daerah($kode_daerah){
    	$daerah=DB::table('public.master_daerah as d')
    	->select(
    		DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=d.id) as nama_daerah")
    	)->where('id',$kode_daerah)
    	->first();


    	$data=DB::connection('sinkron_prokeg')
    	->table('public.tipologi_dinas')
    	->where('kode_daerah',$kode_daerah)
    	->get();


    	return view('front.v2.tipologi.detail')
    	->with(
    		[
    		'data'=>$data,
    		'daerah'=>$daerah
    		]
    	);

    }

}
