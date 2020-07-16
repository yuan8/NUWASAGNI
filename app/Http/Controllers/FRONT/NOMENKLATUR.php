<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
class NOMENKLATUR extends Controller
{
    //

    public function index(Request $request){
    	if($request->tahun){
            $tahun=$request->tahun;
        }else{
            $tahun=HP::fokus_tahun();
        }

        $data=DB::connection('sinkron_prokeg')->table('public.master_nomenklatur_kabkota as nom')
        ->where('bidang_urusan','03')
        ->where('program','03')
        ->select(
        	DB::RAW("(SELECT nomenklatur from public.master_nomenklatur_kabkota as u where u.urusan=nom.urusan and u.bidang_urusan is null limit 1) as nama_urusan"),
        	DB::RAW("(SELECT nomenklatur from public.master_nomenklatur_kabkota as bu where bu.urusan=nom.urusan and bu.bidang_urusan=nom.bidang_urusan and bu.program is null limit 1) as nama_bidang_urusan"),
        	'*'
        )
        ->orderBy('id','asc')
        ->get();

        return view('front.nomenklatur.index')->with('data',$data);

    }
}
