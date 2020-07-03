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


    public function file_store(Request $request){
        $valid=Validator::make($request->all(),[
            'image'=>'required|file|mimes:png,jpg,jpeg'
        ]);

        if($valid->fails()){

        }else{
            $path=($request->image->store('public/kegiatan/file'));
            $path=url(Storage::url($path));

            return [
                'success'=>true,
                'file'=>[
                    'url'=>$path
                ]
            ];
        }

    }

    public function index(Request $request){
        $tahun=HP::fokus_tahun();
            $data=DB::table('album')
            ->where('created_at','<=',Carbon::parse($tahun.'/12/01')->endOfMonth())
            ->where('created_at','>=',Carbon::parse($tahun.'/01/01')->startOfMonth());

            if($request->q){
                $data=$data->where('title','ilike',('%'.$request->q.'%'));
            }
            $data=$data->paginate(10);


            return view('dash.post.kegiatan.index',['data'=>$data]);
    }

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
    		
            return back()->withInput();

    	}else{
    		$path_file=null;
    		if($request->thumbnail){
    			$path_file=Storage::put('public/dokumentasi_foto/'.$tahun.'/',$request->thumbnail);
    			$path_file=Storage::url($path_file);
    		}

            $content=$request->content;
            $meta_content='';

            if(substr($content,0,1)=='{'){
                $content=json_decode($content,true);
                foreach ($content['blocks'] as $key => $value) {
                    if(in_array($value['type'], ['header','paragraph'])){
                        if(is_string($value['data']['text'])){
                            $meta_content.=($meta_content==''?'':' ').$value['data']['text'];
                        }

                    }

                    # code...
                }

            }

            $meta_content=substr($meta_content,0,200);


    		DB::table('album')->insert([
    			'title'=>$request->title,
    			'content'=>$request->content,
    			'path'=>$path_file,
                'meta_content'=>$meta_content,
    			'created_at'=>$tahun.date('-m-d h:i'),
    			'updated_at'=>$tahun.date('-m-d h:i'),
    		]);

    		Alert::success('Success','Kegiatan Berhasil ditambahkan');

    		return back();
    	}


    }
}
