<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use HP;
use DB;

class RPJMN extends Controller
{
    //

    public function index(Request $request){


        return view('front.v2.maint');

    	if($request->tahun){
    		$tahun=$request->tahun;
    	}else{
    		$tahun=HP::fokus_tahun();
    	}

	 	$table_rpjmn=HP::get_rpjmn_table(null,$tahun);
		$table_rpjmn_indikator=HP::get_rpjmn_table('indikator',$tahun);


		$data=DB::connection('rpjmn')->table($table_rpjmn.' as pn')
        ->leftjoin($table_rpjmn.' as pronas',[['pronas.id_pn','=','pn.id'],['pronas.jenis','=',DB::RAW("'PRONAS'")]])
        ->leftjoin($table_rpjmn_indikator.' as i',[['i.id_pn','=','pn.id'],['i.jenis','=',DB::RAW("'PN'")]])
        ->select(
            'pn.info_path as kode_pn',

            'pn.id as id_pn',
            'pn.nama as urai_pn',
            'pronas.info_path as kode_pronas',
            'pronas.nama as urai_pronas',
            'pronas.id as id_pronas',
            'i.id as id_i',
            'i.info_path as kode_i',
            'i.nama as urai_i',
            'i.satuan as satuan_i',
            'i.anggaran as pagu_i',
            'i.target_1_1',
            'i.target_1_2',
            'i.target_2_1',
            'i.target_2_2',
            'i.target_3_1',
            'i.target_3_2',
            'i.target_4_1',
            'i.target_4_2',
            'i.target_5_1',
            'i.target_5_2'
        )
        ->where('pn.jenis','PN')
        ->where('pronas.id_pp',NULL)
        ->where('pn.info_path','RPJMN.05')
        ->orderBy('pn.index','asc')
        ->orderBy('i.index','asc')

        ->orderBy('pronas.index','asc')
		->get();

        dd($data);

		return view('front.rpjmn.index')->with(['data'=>$data,'rpjmn_nama'=>str_replace('_','-',str_replace('master_','' , str_replace('_rpjmn','',$table_rpjmn))) ]);





    }
}
