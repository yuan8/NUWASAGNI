<?php

namespace App\Http\Controllers\DASH\KEBIJAKAN;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use  DB;
use HP;
use Alert;
use Storage;
use Auth;
use Carbon\Carbon;
class FILE extends Controller
{
    //



    public function index($jenis=null){
     	$tahun=HP::fokus_tahun();
    	if($jenis){
    		$jenis=strtoupper(trim($jenis));
    		if(in_array($jenis, ['RKPD','RENJA','RENSTRA','RPAM','RISPAM','JAKSTRA','RKA','LAIN_LAIN'])){
                $daerah=DB::table('master_daerah as c')->select(
                    'id',

                    DB::raw("concat(c.nama,
                        (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) as nama_daerah")
                )->orderBy('c.id','ASC')->get();

    			$data=DB::table('public.dokumen_kebijakan_daerah as f')->select('f.*',
                DB::raw("CONCAT('".url('')."/',f.path) as path_file"),
                DB::Raw("(select name from public.users as u where u.id = f.user_id) as nama_user "),
		     	DB::raw("(select concat(c.nama,
		                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=f.kode_daerah) as nama_daerah")
		     	)->where('tahun','<=',$tahun)->where('tahun_selesai','>=',$tahun)->where('jenis',$jenis)->get();

     			return view('dash.kebijakan.file.index')->with(['data'=>$data,'jenis'=>$jenis,'daerah'=>$daerah]);


    		}else{
    			return abort(404);
    		}
    		
    	}else{
    		return abort(404);
    	}

    }


    public function upload($jenis=null,Request $request){
        $tahun=HP::fokus_tahun();
        $tahun_mulai=$request->tahun_mulai;
        if($jenis){
            $jenis=strtoupper(trim($jenis));
            if(in_array($jenis, ['RKPD','RENJA','RENSTRA','RPAM','RISPAM','JAKSTRA','RKA','LAIN_LAIN'])){

                $data=DB::table('public.dokumen_kebijakan_daerah as f')->select('f.*')
               ->where('tahun','=',$tahun_mulai)->where('jenis',$jenis)
               ->where('kode_daerah',$request->kode_daerah)
               ->first();
               if($data){
                Alert::error('Error', 'Mohon Hapus data sebelumnya');
                return back();

               }else{
                    if($request->file){
                        $ext= $request->file->getClientOriginalExtension();
                       $name='file_kebijakan_daerah/'.$request->kode_daerah.'/'.$jenis;
                        $file=Storage::put(('public/'.$name),$request->file('file'));

                        $file=(Storage::url($file));
                        
                        DB::table('public.dokumen_kebijakan_daerah as f')->insert([
                            'jenis'=>$jenis,
                            'kode_daerah'=>$request->kode_daerah,
                            'nama'=>$request->nama,
                            'tahun'=>$tahun_mulai,
                            'tahun_selesai'=>$request->tahun_selesai,
                            'path'=>$file,
                            'extension'=>$ext,
                            'user_id'=>Auth::user()->id

                        ]);

                       }
                    
                        Alert::success('Success', 'Data Berhasil Dimasukan');


                       return back();

                    }


            }else{
                return abort(500);
            }
            
        }else{
            return abort('500');
        }

    }

    public function view($jenis=null,$id=null){
        $tahun=HP::fokus_tahun();
        if($jenis){
            $jenis=strtoupper(trim($jenis));
            if(in_array($jenis, ['RKPD','RENJA','RENSTRA','RPAM','RISPAM','JAKSTRA','RKA','LAIN_LAIN'])){
                $daerah=DB::table('master_daerah as c')->select(
                    'id',

                    DB::raw("concat(c.nama,
                        (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) as nama_daerah")
                )->orderBy('c.id','ASC')->get();
                $data=DB::table('public.dokumen_kebijakan_daerah as f')->select('f.*',
                        DB::Raw("(select name from public.users as u where u.id = f.user_id) as nama_user "),

                DB::raw("(select concat(c.nama,
                        (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=f.kode_daerah) as nama_daerah")
                )->where('id',$id)->first();

                if($data){
                    return view('dash.kebijakan.file.update')->with(['data'=>$data,'jenis'=>$jenis,'daerah'=>$daerah]);

                }else{
                    return abort(404);
                }

            }else{
                return abort(500);
            }
            
        }else{
            return abort('500');
        }

    }

      public function update($jenis=null,$id=null,Request $request){

        $tahun=HP::fokus_tahun();

        if($jenis){
            $tahun_mulai=$request->tahun_mulai;

            $jenis=strtoupper(trim($jenis));
            if(in_array($jenis, ['RKPD','RENJA','RENSTRA','RPAM','RISPAM','JAKSTRA','RKA','LAIN_LAIN'])){
                if($request->file('file')){
                    $ext= $request->file->getClientOriginalExtension();
                    $name='file_kebijakan_daerah/'.$request->kode_daerah.'/'.$jenis;
                    $file=Storage::put(('public/'.$name),$request->file('file'));

                    $file=(Storage::url($file));

                    $data=DB::table('public.dokumen_kebijakan_daerah as f')->where('id',$id)->first();

                    if($data){
                        $data=DB::table('public.dokumen_kebijakan_daerah as f')->where('id',$id)->update([
                                'jenis'=>$jenis,
                                'kode_daerah'=>$request->kode_daerah,
                                'nama'=>$request->nama,
                                'tahun'=>$request->tahun_mulai,
                                'tahun_selesai'=>$request->tahun_selesai,
                                'path'=>$file,
                                'extension'=>$ext,
                                'user_id'=>Auth::user()->id,
                                // 'created_at'=>Carbon::now(),
                                // 'updated_at'=>Carbon::now(),

                            ]);
                         Alert::success('Success', 'Data Berhasil diupdate');
                        return back();

                    }else{
                        return abort(404);
                    }

                }else{


                    $data=DB::table('public.dokumen_kebijakan_daerah as f')->where('id',$id)->first();

                    if($data){
                        $data=DB::table('public.dokumen_kebijakan_daerah as f')->where('id',$id)->update([
                                'jenis'=>$jenis,
                                'kode_daerah'=>$request->kode_daerah,
                                'nama'=>$request->nama,
                                'tahun'=>$request->tahun_mulai,
                                'tahun_selesai'=>$request->tahun_selesai,
                                'path'=>$data->path,
                                'extension'=>$data->extension,
                                'user_id'=>Auth::user()->id,
                                // 'updated_at'=>Carbon::now(),

                            ]);
                         Alert::success('Success', 'Data Berhasil diupdate');
                        return back();

                    }else{
                        return abort(404);
                    }


                }

            }else{
                return abort(500);
            }
            
        }else{
            return abort('500');
        }

    }

    public function delete(Request $request){
          $data=DB::table('public.dokumen_kebijakan_daerah as f')->where('id',$request->id)->first();
          if($data){
             $data=DB::table('public.dokumen_kebijakan_daerah as f')->where('id',$request->id)->delete();
                Alert::success('Success', 'Data Berhasil Dihapus');
          }else{
                Alert::error('Error', 'Gagal Mengapus Data');
          }

          return back();
    }



    public function store($jenis=null){
        $tahun=HP::fokus_tahun();
        if($jenis){
            $jenis=strtoupper(trim($jenis));
            if(in_array($jenis, ['RKPD','RENJA','RENSTRA','RPAM','RISPAM','JAKSTRA','RKA','LAIN_LAIN'])){

                $data=DB::table('public.dokumen_kebijakan_daerah as f')->select('f.*',
                DB::raw("(select concat(c.nama,
                        (case when length(c.id)>3 then (select concat(' / ',d5.nama) from public.master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from public.master_daerah as c where c.id=f.kode_daerah) as nama_daerah")
                )->where('tahun','<=',$tahun)->where('tahun_selesai','>=',$tahun)->where('jenis',$jenis)->get();

                return view('dash.kebijakan.file.index')->with(['data'=>$data,'jenis'=>$jenis]);


            }else{
                return abort(500);
            }
            
        }else{
            return abort('500');
        }

    }
    


}
