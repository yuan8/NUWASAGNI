<?php

namespace App\Http\Controllers\DASH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;

class DEARAHNUWAS extends Controller
{
    //


    public function index(Request $request){

        if($request->tahun){
            $tahun=$request->tahun;
        }else{
            $tahun=HP::fokus_tahun();

        }

        $data=DB::table('daerah_nuwas as n')
        ->select(
            'n.*',
            DB::raw("(select nama from master_daerah where id=n.kode_daerah)as nama_daerah"),
            DB::raw("(case when (length(n.kode_daerah)>3) then (select nama from master_daerah where id=left(n.kode_daerah,2)) else ''  end) as nama_provinsi")
        )
        ->where('tahun','<=',($tahun+1))
        ->get();

        return view('dash.daerah_nuwas.index')->with(['data'=>$data,'tahun'=>$tahun]);

        
    }

    public function show($kode_daerah){

        if($kode_daerah){
            $data=DB::table('daerah_nuwas as n')->where('kode_daerah',$kode_daerah)->first();

            if($data){

            }else{
                return abort('404');
            }
        }else{
             return abort('404');

        }

    }

    public function create(){
        return view('dash.daerah_nuwas.create');
    }

    public function delete(){
    	
    }

    public function store(Request $request){
        
        

    }
}
