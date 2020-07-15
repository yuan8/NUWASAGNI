<?php

namespace App\Http\Controllers\DASH\OUTPUT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
use Auth;
use Carbon\Carbon;
use Storage;
use Validator;
use Alert;
class POST extends Controller
{
    //

    public function destroy($id){
         if($id){
           $data= DB::table('output_publish')->find($id);
           if($data){
                $del= DB::table('output_publish')->where('id',$data->id)->delete();
                Alert::success('Success','Output Berhasil Dihapus');
                return back();
                            

           }else{
            return abort(404);
           }
        }else{
            return abort(404);

        }

    }

    public function show($id){
        if($id){
           $data= DB::table('output_publish')->find($id);
           if($data){

                return view('dash.output.post.update')->with('data',$data); 

           }else{
            return abort(404);
           }
        }else{
            return abort(404);

        }

    }

    public function update($id,Request $request){
        if($id){
            $data=DB::table('output_publish')->find($id);
            if($data){
                $tahun=HP::fokus_tahun();
                $valid=Validator::make($request->all(),[
                    'title'=>'required|string',
                    'content'=>'required|string',
                    'thumbnail'=>'nullable|file',
                    'public_date'=>'nullable|date'
                ]);

                if($valid->fails()){
                    Alert::error('error');
                    return back()->withInput();

                }else{
                    $path_file=null;
                    if($request->thumbnail){
                        $path_file=$request->thumbnail->store('public/output/post/thumbnails');
                        $path_file=Storage::url($path_file);
                    }

                    $content=$request->content;
                    $meta_content='';

                    if($request->publish_date){
                        $publish_date=Carbon::parse($request->publish_date);
                    }else{
                        $publish_date=Carbon::now();
                    }

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

                    if(strlen($meta_content)>200){
                        $meta_content=substr($meta_content,0,200).'...';
                    }


                    $data_update=[
                        'title'=>$request->title,
                        'content'=>$request->content,
                        'publish_date'=>$publish_date,
                        'tahun'=>$tahun,
                        'type'=>2,
                        'user_id'=>Auth::id(),
                        'meta_content'=>$meta_content,
                        'updated_at'=>$tahun.date('-m-d h:i'),
                    ];

                    if($path_file){
                        $data_update['file_path']=$path_file;
                    }


                    DB::table('output_publish')->where('id',$data->id)->update($data_update);

                    Alert::success('Success','Output Berhasil Update');

                    return back();
                }
            }
        }
    }

	public function show_article($id,$slug,Request $request){
		if($id){
			$data=DB::table('output_publish')->find($id);
			if($data){

				return view('front.v2.output.post.show')->with(['data'=>$data]);

			}else{
				return back(404);
			}
		}else{
				return back(404);

		}

	}

    public function post_create(){
    	return view('dash.output.post.create');
    }


    public function post_store(Request $request){
    	$tahun=HP::fokus_tahun();
    	$valid=Validator::make($request->all(),[
    		'title'=>'required|string',
    		'content'=>'required|string',
    		'thumbnail'=>'nullable|file',
    		'public_date'=>'nullable|date'
    	]);

    	if($valid->fails()){
    		Alert::error('error');
            return back()->withInput();

    	}else{
    		$path_file=null;
    		if($request->thumbnail){
    			$path_file=$request->thumbnail->store('public/output/post/thumbnails');
    			$path_file=Storage::url($path_file);
    		}

            $content=$request->content;
            $meta_content='';

            if($request->publish_date){
            	$publish_date=Carbon::parse($request->publish_date);
            }else{
            	$publish_date=Carbon::now();
            }

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

            if(strlen($meta_content)>200){
            	$meta_content=substr($meta_content,0,200).'...';
            }



    		DB::table('output_publish')->insert([
    			'title'=>$request->title,
    			'content'=>$request->content,
    			'file_path'=>$path_file,
    			'publish_date'=>$publish_date,
    			'tahun'=>$tahun,
    			'type'=>2,
    			'user_id'=>Auth::id(),
                'meta_content'=>$meta_content,
    			'created_at'=>$tahun.date('-m-d h:i'),
    			'updated_at'=>$tahun.date('-m-d h:i'),
    		]);

    		Alert::success('Success','Output Berhasil ditambahkan');

    		return back();
    	}
    }
}
