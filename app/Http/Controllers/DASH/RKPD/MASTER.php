<?php

namespace App\Http\Controllers\DASH\RKPD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

use HP;
class MASTER extends Controller
{
    //

    public function upload(){
        return view('dash.prokeg.master.upload');
    }


    public function index(Request $request){
    	$tahun=HP::fokus_tahun();

    	$pemda=DB::table('daerah_nuwas')->get()->pluck('kode_daerah');


 
        $id_urusan=[3,4];
        $data=DB::connection('myfinal')->table('master_'.$tahun.'_kegiatan as k')->select(
            DB::raw("sum(pagu) as pagu"),
            DB::raw("count(distinct(id_program)) as jumlah_program"),
            DB::raw("count(*) as jumlah_kegiatan"),
            'kodepemda',
            DB::raw("(select nama from master_daerah as d where d.id = k.kodepemda ) as nama_daerah"),
            DB::raw("(select nama from master_daerah as d where d.id = left(k.kodepemda,2) ) as nama_provinsi")

        )
        ->groupBy('k.kodepemda')
        ->where('status',5)
        ->whereIn('k.kodepemda',$pemda)
        ->whereIn('k.id_urusan',$id_urusan)
        ->get();

        return view('dash.prokeg.master.index')->with('data',$data);
    	// return 
    }
}
