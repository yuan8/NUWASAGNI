<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use HP;
use DB;

class RPJMN extends Controller
{
    //

    public function index(Request $request){

    	if($request->tahun){
    		$tahun=$request->tahun;
    	}else{
    		$tahun=HP::fokus_tahun();
    	}

	 	$table_rpjmn=HP::get_rpjmn_table(null,$tahun);
		$table_rpjmn_indikator=HP::get_rpjmn_table('indikator',$tahun);

		$data=DB::connection('rpjmn')->table($table_rpjmn.' as pn')
		
		)->where('pn.jenis','PN')
		->get();


		return view('front.rpjmn.index')->with(['data'=>$data,'rpjmn_nama'=>str_replace('_','-',str_replace('master_','' , str_replace('_rpjmn','',$table_rpjmn))) ]);





    }
}
