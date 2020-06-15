<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class DOKUMEN extends Controller
{
    //

    public function list($kode_daerah,$jenis){

    	$daerah=DB::table('master_daerah as d')
    	->select(
    		 DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=d.id) as nama_daerah")
    	)
    	->find($kode_daerah);

    	if($daerah){
    		$data=DB::table('public.dokumen_kebijakan_daerah as f')
    		->select(
    			'f.*',
    			DB::raw("(select name from public.users as u where u.id=f.user_id ) as nama_user")
    		)
    		->where('kode_daerah',$kode_daerah)
    		->where('jenis',strtoupper($jenis))
    		->orderBy('tahun','desc')->get();


    		return view('front.daerah.dokumen.list')->with([
    			'daerah'=>$daerah,
    			'data'=>$data,
    			'jenis'=>$jenis
    		]);
    	}
    }
}
