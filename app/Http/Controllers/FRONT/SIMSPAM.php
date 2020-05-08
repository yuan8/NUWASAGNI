<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class SIMSPAM extends Controller
{
    //

    public function index($id){
    	$pdam=DB::table('pdam')->where('kode_daerah',$id)->first();
    	
    	$data=DB::connection('simspam')->table('perpipaan')
    	->where('kode_daerah',$id)
    	->orderBy('updated_at')
    	->get();


    	return view('front.pdam.simspam.index')->with(

    		['data'=>$data,
    		'pdam'=>$pdam

    		]

    	);
    }
}
