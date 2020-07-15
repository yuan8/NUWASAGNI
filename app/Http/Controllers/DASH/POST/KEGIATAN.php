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


    public function striky(Request $request){

        if($request->id){
            $data=DB::table('album')
            ->find($request->id);

            if($data){
                if($data->sticky){
                    DB::table('album')->where('id',$data->id)->update(['sticky'=>false,'updated_at'=>Carbon::now()]);
                     return array(
                        'code'=>200,
                        'status'=>false
                    );

                }else{
                    $sticky=DB::table('album')->where('sticky',true)->orderBy('updated_at','DESC')->limit(10)->get()->toArray();

                    if(count($sticky)==10){
                        DB::table('album')->where('id',$sticky[0]->id)->update(['sticky'=>false,'updated_at'=>Carbon::now()]);
                    }

                   $data= DB::table('album')->where('id',$data->id)->update(['sticky'=>true,'updated_at'=>Carbon::now()]);
                    return array(
                        'code'=>200,
                        'status'=>true
                    );
                }

            }else{

                return array(
                    'code'=>500,
                    'status'=>false
                );

            }
        }else{
            return array(
                'code'=>500,
                'status'=>false
            );
        }

    }

    public function active_url(Request $request){

         $valid=Validator::make($request->all(),[
            'url'=>'required|active_url',
        ]);
        if($valid->fails()){
              return [
                'success'=>false,
                'meta'=>[
                ]
            ];

        }else{

            $meta=get_meta_tags($request->url);
            // return $meta;

            return [
                'success'=>true,
                'meta'=>[
                    'title'=>isset($meta['title'])?$meta['title']:'',
                    'description'=>isset($meta['description'])?$meta['description']:'',
                    'image'=>[
                        'url'=>isset($meta['image'])?$meta['image']:''
                    ]
                ]
            ];
        }


    }
    public function file_store(Request $request){
        $valid=Validator::make($request->all(),[
            'image'=>'nullable|file|mimes:png,jpg,jpeg',
            'file'=>'nullable|file'
        ]);

        if($valid->fails()){
            return [
                'success'=>false,
                'file'=>[
                    'url'=>''
                ]
            ];

        }else{
           
            $out='kegiatan';
            if(strpos($request->headers->get('referer'),'output/post')!==false){
                $out='ouput';
            }

           if($request->image){
             $path=($request->image->store('public/'.$out.'/images'));
            $path=url(Storage::url($path));

            return [
                'success'=>true,
                'file'=>[
                    'url'=>$path
                ]
            ];
           }

           if($request->file){

            $path=($request->file->store('public/'.$out.'/files'));
            $path=url(Storage::url($path));

            return [
                'success'=>true,
                'file'=>[
                    'url'=>$path,
                    'name'=>$request->file->getClientOriginalName()
                ]
            ];

           }

            
        }

    }


    public function show($id){
        if($id){
             $data=DB::table('album')->find($id);

             if($data){
                return view('dash.post.kegiatan.update')->with('data',$data);
             }else{
                return abort('404');

             }


        }else{

            return abort('404');

        }
    }



    public function update($id,Request $request){
        if($id){
             $data=DB::table('album')->find($id);

             if($data){
               
                $tahun=HP::fokus_tahun();
                $valid=Validator::make($request->all(),[
                    'title'=>'required|string',
                    'content'=>'required|string',
                    'thumbnail'=>'nullable|file'
                ]);

                if($valid->fails()){
                    Alert::error('error');
                    
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

                    $data_update=[
                        'title'=>$request->title,
                        'content'=>$request->content,
                        'meta_content'=>$meta_content,
                        'updated_at'=>$tahun.date('-m-d h:i'),
                    ];

                    if($path_file){
                        $data_update['path']=$path_file;
                    }

                    DB::table('album')->where('id',$id)->update($data_update);

                    Alert::success('Success','Kegiatan Berhasil diupdate');

                    return back();
                }


             }else{
                return abort('404');

             }

        }else{

            return abort('404');

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


            $data->appends(['q'=>$request->q]);


            return view('dash.post.kegiatan.index',['data'=>$data,'q'=>$request->q]);
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
    		Alert::error('error');
    		
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
