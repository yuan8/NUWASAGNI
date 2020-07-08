<?php

namespace App\Http\Controllers\DASH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
use Validator;
use Alert;

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
        $ids=DB::table('daerah_nuwas')->get()->pluck('kode_daerah');


        $daerah= DB::table('master_daerah as d')
        ->select(
            DB::RAW("(select case when length(d.id)<3 then d.nama else concat(d.nama,' / ',p.nama) end from master_daerah as p where p.id=left(d.id,2) limit 1) as nama_daerah"),
            'id'
        )
        ->whereNotIn('id',$ids)->get();
        
        return view('dash.daerah_nuwas.create')->with('daerah',$daerah);
    }

    public function delete(){
    	
    }

    public function store(Request $request){
        
        $valid=Validator::make($request->all(),[
            'kode_daerah'=>'string|unique:daerah_nuwas,kode_daerah',
            'tahun'=>'nullable|numeric',
            'jeni_bantuan'=>'nullable|array',
            'nilai_bantuan'=>'nullable|numeric'
        ]);


        if($valid->fails()){
            Alert::error('Error');
            return back()->withInput();
        }else{

            $jenis_bantuan=null;
            $nilai_bantuan=null;

            if($request->tahun){
                $nilai_bantuan=$request->nilai_bantuan;
                $jenis_bantuan=[];
                foreach ($request->jenis_bantuan as $key => $value) {
                    # code...
                    $jenis_bantuan[]='@'.strtoupper($value);
                }
                
                $jenis_bantuan=implode(',', $jenis_bantuan);
            }
        
            DB::table('daerah_nuwas')->insert([
                'kode_daerah'=>$request->kode_daerah,
                'tahun'=>$request->tahun,
                'tahun_selesai'=>$request->tahun,
                'jenis_bantuan'=>$jenis_bantuan,
                'nilai_bantuan'=>$nilai_bantuan
            ]);

            Alert::success('Success','Target NUWSP berhasil ditambah');

            return back();

        }

    }
}
