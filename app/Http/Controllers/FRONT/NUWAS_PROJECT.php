<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use HP;
use DB;
class NUWAS_PROJECT extends Controller
{
    //

    public function index(){
    	$tahun=HP::fokus_tahun();

        $output=DB::table('output_publish as p')
        ->leftJoin('users as u','u.id','=','p.user_id')
        ->select('p.*','u.name as nama_user')
        ->where('p.tahun',$tahun)
        ->orderBy('p.updated_at','DESC')->limit(11)->get();



    	
    	$data_target_nuwas=DB::table('daerah_nuwas as n')
    	
    	->where('n.tahun',$tahun)
    	->count();

    	$public_world_bank=scandir(storage_path('app/public/publikasi_world_bank_air_bersih'));
    	unset($public_world_bank[0]);
    	unset($public_world_bank[1]);
    	$public_world_bank=array_values($public_world_bank);

    	foreach ($public_world_bank as $key => $value) {
    		# code...
    		$public_world_bank[$key]=array(
    			'nama'=>$value,
    			'url'=>'storage/publikasi_world_bank_air_bersih/'.$value
    		);
    	}


    	return view('front.nuwas_project.index')->with(
    		[
    			'target_nuwas'=>$data_target_nuwas,
    			'public_world_bank'=>$public_world_bank,
                'output'=>$output

    		]
    	);
    }
}
