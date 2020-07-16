<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
class KEGIATAN extends Controller
{
    //

    public function index(){
        $data=DB::table('album')->orderBy('id','desc')->paginate(10);


        return view('front.kegiatan.index')->with('data',$data);

    }

    


    public function show($id){
    	$data=DB::table('album')->find($id);


    	if($data){
    		$data_lain=DB::table('album')->where('id','!=',$data->id)->orderBy('id','DESC')->limit(10)->get();
    		return view('front.kegiatan.show')->with('data',$data)->with('data_lain',$data_lain);
    	}else{
    		return abort('404');
    	}
    }
}
