<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
class CAPAIANSPM extends Controller
{
    //
    public function index(){
        DB::connection('fgd')->enableQueryLog();
    	$data=DB::connection('fgd')->table('fgd.data_fgd as fgd')
    	->leftJoin('public.daerah_nuwas as d','fgd.kodepemda','=','d.kode_daerah')
    	->select(
    		DB::RAW("(select nama from public.master_daerah as d1 where d1.id=fgd.kodepemda ) as nama_daerah"),
    		DB::raw("(select nama from public.master_daerah as p where p.id = left(fgd.kodepemda,2)) as nama_provinsi"),
    		"fgd.*"
    	)
    	->whereRaw('d.id is not null')
    	->get();
 $query = DB::connection('fgd')->getQueryLog();
// dd(end($query));
    	return view('front.fgd.index')->with([
    		'data'=>$data
    	]);

    }
}
