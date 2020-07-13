<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
class BPPSPAM extends Controller
{
    //


    public function index(Request $request){

    	if($request->tahun){
    		$tahun=$request->tahun;
    	}else{
    		$tahun=HP::fokus_tahun();
    	}

    	$data=DB::table('daerah_nuwas')
    	->leftJoin('bppspam.')


    }
}
