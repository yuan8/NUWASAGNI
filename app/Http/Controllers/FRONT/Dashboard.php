<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use HP;
use DB;
use Carbon\Carbon;
class Dashboard extends Controller
{
    //


    public function index(){
    	$tahun=HP::fokus_tahun();

    	$data_kegiatan=DB::connection('sinkron_prokeg')->table('tb_'.$tahun.'_kegiatan')->where('id_urusan',3)
    	->select(
    		DB::raw("sum(anggaran) as jumlah_anggaran"),
    		DB::raw("count(*) jumlah_kegiatan")
    	)
    	->where('id_sub_urusan',12)
    	->where('status',5)
    	->first();


        $data_pdam=DB::table('pdam')->count();

    

    	return view('index')->with([
    		'data_kegiatan'=>$data_kegiatan,
            'data_pdam'=>$data_pdam,


    	]);
    }
}
