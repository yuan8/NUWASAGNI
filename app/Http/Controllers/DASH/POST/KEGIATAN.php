<?php

namespace App\Http\Controllers\DASH\POST;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use Alert;
use Storage;
use HP;
use Carbon\Carbon;
class KEGIATAN extends Controller
{
    //


    public function create(){
    	return view('dash.post.kegiatan.create');

    }


    public function store(Request $request){
    	$tahun=HP::fokus_tahun();
    	$valid=Validator::make($request->all(),[
    		'title'=>'required|string',
    		'content'=>'required|string',
    		'thumbnail'=>'nullable|file'
    	]);

    	if($valid->fails()){
    		Alert::error('errro');
    		dd($valid);

    	}else{
    		$path_file=null;
    		if($request->thumbnail){
    			$path_file=Storage::put('public/dokumentasi_foto/'.$tahun.'/',$request->thumbnail);
    			$path_file=Storage::url($path_file);
    		}

    		DB::table('album')->insert([
    			'title'=>$request->title,
    			'content'=>$request->content,
    			'path'=>$path_file,
    			'created_at'=>$tahun.date('-m-d h:i'),
    			'updated_at'=>$tahun.date('-m-d h:i'),
    		]);

    		Alert::success('Success','Kegiatan Berhasil ditambahkan');

    		return back();
    	}


    }
}