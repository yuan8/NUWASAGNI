<?php

namespace App\Http\Controllers\OUTPUT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Storage;
use Carbon\Carbon;
use DB;
class MAP extends Controller
{
    //

	static function DefTooltip($data){

	}

    public function buildOffLine($tahun,$id,$id_doc){

        $path_file=storage_path('app/public/output/map/'.$tahun.'/'.$id.'/'.$id_doc.'.zip');
        if(!file_exists($path_file)){
            $rootPath = realpath(storage_path('/app/public/output/map/'.$tahun.'/'.$id.'/output/'.$id_doc));
            $zip = new \ZipArchive();
            $zip->open(storage_path('app/public/output/map/'.$tahun.'/'.$id.'/'.$id_doc.'.zip'), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($rootPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file)
            {
                // Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);

                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);
                }
            }

            // Zip archive will be created only after closing object
            $zip->close();

            return response()->download($path_file);
        }else{
            return response()->download($path_file);
        }

    }


    public function show($tahun,$id,Request $request){

        if(is_dir(storage_path('/app/public/output/map/'.$tahun.'/'.$id.'/json'))){
            $json=scandir(storage_path('/app/public/output/map/'.$tahun.'/'.$id.'/json'));
            $id_doc=$json[count($json)-1];
            $id_doc=$id.str_replace('-','_', str_replace('.json', '', $id_doc)).'id';
        
            $id_map=$id_doc;
            return view('output.map.themplate')->with([
                'id_map'=>$id_map,
                'own_content'=>true,
                'data_path'=>url('/storage/output/map/'.$tahun.'/'.$id.'/output/'.$id_doc.'/asset/data_builder.js'),
                'file_path'=>url('/storage/output/map/'.$tahun.'/'.$id.'/output/'.$id_doc.'/asset/'.$id_doc.'.xlsm'),
                'build_ofline_path'=>route('own.out.offline',['tahun'=>$tahun,'id'=>$id,'id_doc'=>$id_doc])
             ]);

        }else{
            return abort(404);
        }
    	
    	

    }



    public static function convertion($tahun,$id,$file){
        ini_set("memory_limit", "-1");
    	
    	$path=storage_path('app/public/output/map/'.$tahun);

    	// conversi

    	$not_ex='AM2Y4N27278';
    	$map_series=[
    		'title'=>'',
    		'sub_title'=>'',
    		'series'=>[]
    	];
    	$start=10;

    	$spreadsheet=\PhpOffice\PhpSpreadsheet\IOFactory::load($file);
    	$sheet_count=($spreadsheet->getSheetCount());

        
    	for($i=0;$i<$sheet_count;$i++){
    		$sheet = $spreadsheet->setActiveSheetIndex($i);
    		$dat_array=$sheet->toArray();
    		if($dat_array[0][0]!=$not_ex){
              

    			$data_series=[
    				'name_layer'=>$dat_array[2][1],
    				'name_data'=>$dat_array[6][1],
    				'satuan'=>$dat_array[6][4],
    				'mapData_name'=>strtoupper($dat_array[1][1])=='PROVINSI'?'ind':'ind_kota',
    				'legend'=>[
    					'cat'=>[],
    					'color'=>[]
    					],
    				'data'=>[]
    			];

    			for($l=1;$l<19;$l++){
    				if(($dat_array[3][$l]!=null)||trim($dat_array[3][$l])!=""){
    					$data_series['legend']['cat'][]= $dat_array[3][$l].' '.$dat_array[4][$l];
    					$data_series['legend']['color'][]= $dat_array[5][$l];
    				}
    			}

    			foreach ($dat_array as $key => $d) {
    				$x=[];
    				$d=(array)$d;
    				$d=array_values($d);

    				if($key>=$start){

    					foreach ($d as $indt=>$di) {
    						$d[$indt]=$di?trim($di):null;
    					}

    					if(!empty($d[2])){
    						$x['kode_daerah']=$d[0];
    						$x['daerah']=$d[1];
    						$x['value']=$d[2];
    						$x['kategori']=$d[3];
    						$x['action_link']=!empty($d[4])?$d[4]:null;
    						$x['color']=!empty($d[6])?str_replace('[ERROR]', '',($d[6])):null;
    						$x['tooltip']=!empty($d[5])?$d[5]:static::DefTooltip($x);

    						$data_series['data'][]=array_values($x);

    					}else{
    						$x['kode_daerah']=$d[0];
    						$x['daerah']=$d[1];
    						$x['value']=0;
    						$x['kategori']='TIDAK TERDAPAT DATA';
    						$x['action_link']=null;
    						$x['color']='#ffffff';
    						$x['tooltip']=static::DefTooltip($d);
    						$data_series['data'][]=array_values($x);
    					}


    				}
    			}


    			$map_series['series'][]=$data_series;
              
               
                
               



    		}else{
    				$map_series['title']=$dat_array[4][1];
    				$map_series['sub_title']=$dat_array[5][1];

    		}


    	}
		
    	// KEMANADIMANAXXXBERADA
    	//scure_content_yoneKEMANADIMANAXXXBERADA

		$time_action=Carbon::now()->format('d-m-y-h-i-s');    	

        $sigle_script='';

        $json=$time_action.'.json';
        $asset_map_path=public_path('L_MAP');
        $asset_path=public_path('vendor');
        $id_map=$id.str_replace('-', '_', str_replace('.json', 'id', $json));


        $sigle_script.=file_get_contents($asset_map_path.'/asset/jq.js');
        $sigle_script.=file_get_contents($asset_map_path.'/asset/hi.js');
        $sigle_script.=file_get_contents($asset_map_path.'/asset/proj4.js');
        $sigle_script.= file_get_contents($asset_map_path.'/ind/ind.js');
        $sigle_script.= file_get_contents($asset_map_path.'/ind/kota.js');
              $sigle_script.=file_get_contents($asset_map_path.'/asset/bootstrap.min.js');
        Storage::put('public/output/map/'.$tahun.'/'.$id.'/output/'.$id_map.'/asset/'.$id_map.'.js',$sigle_script);




    	Storage::put('public/output/map/'.$tahun.'/'.$id.'/json/'.$time_action.'.json',json_encode($map_series));
      
    	 
    	Storage::put('public/output/map/'.$tahun.'/'.$id.'/output/'.$id_map.'/asset/data_builder.js','var '.$id_map.'='.json_encode($map_series));
    	Storage::put('public/output/map/'.$tahun.'/'.$id.'/output/'.$id_map.'/asset/.README',file_get_contents($asset_map_path.'/asset/.README'));
        Storage::put('public/output/map/'.$tahun.'/'.$id.'/output/'.$id_map.'/asset/logo.png',file_get_contents($asset_map_path.'/asset/'.('logo.png')));

        Storage::put('public/output/map/'.$tahun.'/'.$id.'/output/'.$id_map.'/asset/bootstrap.min.css',file_get_contents($asset_map_path.'/asset/bootstrap.min.css'));
        // Storage::put('public/output/map/'.$tahun.'/'.$id.'/output/'.$id_map.'/asset/bootstrap.min.js',file_get_contents($asset_map_path.'/asset/bootstrap.min.js'));


        Storage::put('public/output/map/'.$tahun.'/'.$id.'/output/'.$id_map.'/index.html',view('output.map.themplate')->with('id_map',$id_map)->render());


        return array(
            'path_asset'=>'public/output/map/'.$tahun.'/'.$id.'/output/'.$id_map.'/asset',
            'id_map'=>$id_map,
            'public_path'=>str_replace(url(''),'',route('own.out.map',['tahun'=>$tahun,'id'=>$id]))
        );

        return '<a href="'.route('own.out.map',['tahun'=>$tahun,'id'=>$id,'id_doc'=>$id_map]).'" target="_blank">LIHAT</a>';

    } 
}
