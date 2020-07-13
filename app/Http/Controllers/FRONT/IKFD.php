<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
class IKFD extends Controller
{
    //

    public function index(Request $request){

    	if($request->tahun){
    		$tahun=$request->tahun;
    	}else{
    		$tahun=HP::fokus_tahun();
    	}

    	$daerah_nuwas=DB::table('daerah_nuwas as dn')->get()->pluck('kode_daerah');

    	$data=DB::connection('sinkron_prokeg')->table('pemda.master_ikfd as ikfd')
    	->where('ikfd.tahun','>=',($tahun-2))
    	->where('ikfd.tahun','<=',($tahun) )
    	->whereIn('ikfd.kodepemda',$daerah_nuwas)
    	->select(
    		"ikfd.*",
    		DB::RAW("ikfd.kodepemda as kode_daerah"),
    		 	DB::RAW("(select nama from public.master_daerah  as d where d.id::text=ikfd.kodepemda::text ) as nama_daerah"),
    		DB::RAW("(select nama from public.master_daerah  as d where d.id::text=left(ikfd.kodepemda::text,2) ) as nama_provinsi")
    	)
    	->orderBy('ikfd.tahun','desc')->get();


    	$data_return=[];
        $kode_akun=0;
        $blue_print_data=[];
        $tahun_data=[];

        $blue_print_data=[];
        for ($i=$tahun; $i>($tahun-3) ;$i--) { 
             $tahun_data[$i]=$i;
            $blue_print_data['T'.$i]=array(
                'kode_daerah'=>null,
                'nama_daerah'=>null,
                'nama_provinsi'=>null,
                'nilai'=>null,
                'kategori'=>null,
            );
        }

		        
        foreach ($data as $key => $d) {
        	if(!isset($data_return[$d->kode_daerah]['tahun'])){
        		$data_return[$d->kode_daerah]['tahun']=$blue_print_data;
        	}

             $data_return[$d->kode_daerah]['kode_daerah']=$d->kode_daerah;
             $data_return[$d->kode_daerah]['nama_daerah']=$d->nama_daerah;
             $data_return[$d->kode_daerah]['nama_provinsi']=$d->nama_provinsi;
             $data_return[$d->kode_daerah]['nilai']=$d->nilai;
             $data_return[$d->kode_daerah]['kategori']=$d->kategori;
             $data_return[$d->kode_daerah]['tahun']['T'.$d->tahun]=(array)$d;
        }


        return view('front.ikfd.index')->with(['data'=>$data_return,'tahun'=>$tahun,'tahun_data'=>$tahun_data]);


    }
}
