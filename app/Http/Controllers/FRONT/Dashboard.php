<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use HP;
use DB;
use Carbon\Carbon;
class Dashboard extends Controller
{
    //

    public function index(){
    	$tahun=HP::fokus_tahun();

    	$data_kegiatan=DB::connection('sinkron_prokeg')->table('tb_'.$tahun.'_kegiatan')->where('id_urusan',3)
    	->select(
    		DB::raw("sum(anggaran) as jumlah_anggaran"),
    		DB::raw("count(*) jumlah_kegiatan")
    	)->where('id_sub_urusan',12)
    	->where('status',5)
    	->first();

        $data_pdam=DB::table('pdam')->count();

    	return view('front.v2.index')->with([
    		'data_kegiatan'=>$data_kegiatan,
            'data_pdam'=>$data_pdam,
            'tahun'=>$tahun
    	]);
        
    }


    public function api_daerah_nuwas($tahun){
          $map_data=[
            'title'=>'DAERAH NUWSP'.$tahun.' - '.(((int)$tahun)+1),
            'series'=>[
                [
                    'name_layer'=>'AIR MINUM KOTA / KABUPATEN',
                    'mapData_name'=>'ind_kota',
                    'name_data'=>'KOTA',

                    'legend'=>[
                        'cat'=>['TIDAK TERDAPAT DATA','MELAPORKAN RKPD','TERDAPAT KEGIATAN AIR MINUM'],
                        'color'=>['#fff','#32a852','#42f2f5'],
                    ],

                    'data'=>[]

                ],
                [
                    'name_layer'=>'AIR MINUM PROVINSI',
                    'mapData_name'=>'ind',
                    'name_data'=>'PROVINSI',
                    
                    'legend'=>[
                       'cat'=>['TIDAK TERDAPAT DATA','MELAPORKAN RKPD','TERDAPAT KEGIATAN AIR MINUM'],
                        'color'=>['#fff','#32a852','#42f2f5'],
                    ],
                    'data'=>[],
                ],
                 [
                    'name_layer'=>'PERSENTASE PELAPORAN PER-PROVINSI',
                    'mapData_name'=>'ind',
                    'name_data'=>'PROVINSI',
                    
                    'legend'=>[
                       'cat'=>['TIDAK TEDAPAT DATA - 0%','SANGAT RENDAH - <30%','RENDAH - <40%','SEDANG - <60%','TINGGI - <80%','SANGAT TINGGI - >80%'],
                        'color'=>['#fff','#ff0000','#cf6317','#2C4F9B','#ffff00','#00ff00'],
                    ],
                    'data'=>[],
                    


                ]
            ]
        ];
    }
}
