<?php

namespace App\Http\Controllers\DASH\OUTPUT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
use Auth;
use Carbon\Carbon;
use App\Http\Controllers\OUTPUT\MAP as MAPMOD;
use Storage;
class MAP extends Controller
{
    //

    public function index(){
      

    	$tahun=HP::fokus_tahun();
    	$data=DB::table('output_publish as p')
    	->leftJoin('users as u','u.id','=','p.user_id')
    	->select('p.*','u.name as nama_user')
    	->where('p.tahun',$tahun)
    	->where('p.type',1)
    	->orderBy('p.updated_at','DESC')->paginate(10);
    	return view('dash.output.map.index')->with('data',$data);
    }

    public function upload(){
    	return view('dash.output.map.upload');
    }

    public function store(Request $request){
    	$tahun=HP::fokus_tahun();
    	$id_map=strtoupper('MAPMOD'.(Auth::user()->id.Carbon::now()->format('ymdhis'))).rand(10,100000);
    	$data=MAPMOD::convertion($tahun,$id_map,$request->file('file'));
    	if(isset($data['path_asset'])){
    		if(Storage::put($data['path_asset'].'/'.$data['id_map'].'.xlsm',file_get_contents($request->file('file')))){
    			DB::table('output_publish')
    			->insert([
    				'type'=>1,
    				'tahun'=>$tahun,
    				'title'=>$request->title,
    				'file_path'=>$data['public_path'],
    				'user_id'=>Auth::User()->id,
    				'created_at'=>Carbon::now(),
    				'updated_at'=>Carbon::now()

    			]);

               return redirect()->route('d.out.map.index'); 
    		}
    	}

    }

    public function detail(){
    	return view('dash.output.map.detail');
    }

    public function update(){
    	return view('dash.output.map.detail');
    }

    public function update_file(){
    	return view('dash.output.map.detail');
    }

     public function delete(){
    	return view('dash.output.map.delete');
    }
}
