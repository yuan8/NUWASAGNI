<?php

namespace App\Http\Controllers\DASH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MEET extends Controller
{
    //

    public function index(){
    	return view('dash.meet.index');
    }

    public function video(Request $request){
    	$key=explode('/',$request->path());
    	$key=$key[count($key)-1];
        $key=str_replace('-', '_', $key);

    	if(in_array($key,['TACT_LG', 'DSS_TEAM','CAMPURAN'])){

    		return view('dash.meet.video')->with('key',strtoupper($key));

    	}else{
    		return redirect()->route('d.meet.index');
    	}

    }

    public function initial(){
        return view('dash.meet.initial');
    }
}
