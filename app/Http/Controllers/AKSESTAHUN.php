<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use HP;
class AKSESTAHUN extends Controller
{
    //


    public function index(){
    	return view('pilih_tahun')->with('tahun_akses_present',HP::fokus_tahun());
    }

    public function storing(Request $request){

    	if(session('fokus_tahun')!=$request->tahun_akses){
    		session(['fokus_tahun'=>$request->tahun_akses]);
    	}

    	if(session('fokus_tahun')!=$request->tahun_akses){
    		
    	}else{
    		return redirect('/');
    	}


    }
}
