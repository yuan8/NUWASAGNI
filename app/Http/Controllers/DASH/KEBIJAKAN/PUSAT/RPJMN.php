<?php

namespace App\Http\Controllers\DASH\KEBIJAKAN\PUSAT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use HP;
use DB;
class RPJMN extends Controller
{
    //


    public function index(){
    	$rpjm_table=Hp::get_rpjmn_table();
    	$rpjm_table_indikator=Hp::get_rpjmn_table('indikator');

    	set_time_limit(-1);

    	

    	// ----------------------------------------------------------
    	$tahun=HP::fokus_tahun();

    	$data=DB::connection('sinkron_prokeg')->table('kebijakan.'.$rpjm_table)->where('jenis','PN')
    	->get();
    	$data_re=[];

    	// foreach ($data as $pnk=>$pn) {
    	// 	$pn=(array)$pn;
    	// 	$data_re[$pnk]=$pn;
    	// 	$data_re[$pnk]['PP']=DB::table($rpjm_table)->where('jenis','PP')
    	// 	->where('id_pn',$pn['id'])->get()->toArray();
    	// 	foreach ($data_re[$pnk]['PP'] as $ppk => $pp) {
    	// 		$pp=(array)$pp;
    	// 		$data_re[$pnk]['PP'][$ppk]=$pp;

    	// 		$data_re[$pnk]['PP'][$ppk]['KP']=DB::table($rpjm_table)->where('jenis','KP')
    	// 		->where([
    	// 			['id_pn','=',$pn['id']],
    	// 			['id_pp','=',$pp['id']]
    	// 		])->get()->toArray();
    	// 		foreach ($data_re[$pnk]['PP'][$ppk]['KP'] as $kpk => $kp) {
    	// 			$kp=(array)$kp;
    	// 			$data_re[$pnk]['PP'][$ppk]['KP'][$kpk]=$kp;

    	// 			$data_re[$pnk]['PP'][$ppk]['KP'][$kpk]['PROPN']=DB::table($rpjm_table)->where('jenis','PROPN')
	    // 			->where([
	    // 				['id_pn','=',$pn['id']],
	    // 				['id_pp','=',$pp['id']],
	    // 				['id_kp','=',$kp['id']]
	    // 			])->get()->toArray();
	    // 			foreach ($data_re[$pnk]['PP'][$ppk]['KP'][$kpk]['PROPN'] as $propnk => $propn){
	    // 				$propn=(array)$propn;
    	// 				$data_re[$pnk]['PP'][$ppk]['KP'][$kpk]['PROPN'][$propnk]=$propn;

	    // 				$data_re[$pnk]['PP'][$ppk]['KP'][$kpk]['PROPN'][$propnk]['PRONAS']=DB::table($rpjm_table)->where('jenis','PRONAS')
		   //  			->where([
		   //  				['id_pn','=',$pn['id']],
		   //  				['id_pp','=',$pp['id']],
		   //  				['id_kp','=',$kp['id']],
		   //  				['id_propn','=',$propn['id']],
		   //  			])->get()->toArray();

	    // 			}

    	// 			# code...
    	// 		}
    	// 	}

    	// 	# code...
    	// }

    	return view('dash.kebijakan.pusat.rpjmn.index')->with('data',$data);
    }


    public function storing(){

    }

    public function api_turunan(Request $request){
    	$tahun=HP::fokus_tahun();
    	$rpjm_table=Hp::get_rpjmn_table();
    	$rpjm_table_indikator=Hp::get_rpjmn_table('indikator');
    	$index_target=Hp::get_tahun_rpjmn();


    	$where=[];
    	$where2=[];

    	$turunan=[];

    	switch ($request->index) {
    		case 1:
    			$where=[
    				['id_pn','=',$request->value],
    				['jenis','=','PP']
    			];

    			$where2=[
    				['id_pn','=',$request->value],
    				['jenis','=','PN']
    			];

    			# code...
    			break;
    		case 2:
    			$where=[
    				['id_pp','=',$request->value],
    				['jenis','=','KP']
    			];
    			$where2=[
    				['id_pp','=',$request->value],
    				['jenis','=','PP']
    			];

    			# code...
    			break;
    		case 3:
    			$where=[
    				['id_kp','=',$request->value],
    				['jenis','=','PROPN'],

    			];
    			$where2=[
    				['id_kp','=',$request->value],
    				['jenis','=','KP']
    			];

    			# code...
    			break;
    		case 4:
    			$where=[
    				['id_propn','=',$request->value],
    				['jenis','=','PRONAS']

    			];
    			$where2=[
    				['id_propn','=',$request->value],
    				['jenis','=','PROPN']
    			];

    			# code...
    			break;
    		case 5:
    			$where=[
    				['id_pronas','=',$request->value],
    			];
    			$where2=[
    				['id_pronas','=',$request->value],
    				['jenis','=','PRONAS']
    			];

    			# code...
    			break;
    		
    		default:
    			# code...
    			break;
    	}



    	if($request->index<5){
    		$turunan=DB::connection('sinkron_prokeg')->table('kebijakan.'.$rpjm_table)
    		->where($where)->get();
    	}

		$indikator=DB::connection('sinkron_prokeg')->table('kebijakan.'.$rpjm_table_indikator)
		->where($where2)
		->select('id','nama',
			'lokasi',
			'instansi',
			'anggaran',
            'jenis',
			'satuan',
            'lokasi',
            'instansi',
            DB::raw("concat(target_".$index_target."_1,' ',satuan,' ',(case when (target_".$index_target."_2 is not null OR target_".$index_target."_2 <> '0') then concat('- ',target_".$index_target."_2,' ',satuan)else '' end)) as target")
		)
		->get();


		return array(
			'turunan'=>$turunan,
			'indikator'=>$indikator,
			'index_target'=>$index_target
		);



    }
}
