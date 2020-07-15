<?php

namespace App\Http\Controllers\BOT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Storage;
use HP;
use Illuminate\Support\Facades\Schema;

use DB;
class RPJMN extends Controller
{
    //

    static function trimParse($text){

    	if(strpos(trim($text),trim('PROGRAM  PRIORITAS  (PP)/ KEGIATAN PRIORITAS  (KP)/ PROYEK PRIORITAS (PROP)/ PROYEK'))!==false){

    		return '';
    	}

    	if(strpos($text,'A.')!==false){
    		return '';
    	}

    	if(empty($text)){
    		return '';
    	}

    	$text= preg_replace("/\r|\n/", " ", $text);
    	$text= trim($text);
    	if($text==' '){
    		$text='';
    	}

    	return ucwords($text);

    }

     static function numering($num,$min=2){
    	$num=''.$num;
    	
    	if(strlen($num)<$min){
    		for($i=($min-((int)strlen($num)));$i<$min;$i++){
    			$num='0'.$num;
    		}
    	}

    	return $num;

    }


    static function idproyek($pn,$pp,$kp,$propn){
    	return 'RPJMN'.($pn!=0??'.'.static::numering($pn)).($pp!=0??'.'.static::numering($pp)).($kp!=0??'.'.static::numering($kp)).($propn!=0??'.'.static::numering($propn));
    }


     static function trimNum($text){
    	$text= preg_replace("/\r|\n/", "", $text);
    	$text= preg_replace("/['.']/", "", $text);
    	$text= preg_replace("/,/", ".", $text);

    	return trim($text);
    }


    public static function index(){
    	$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/rpjmn.xlsx'));
    	$data_return=[];

    	$pn=[];
    	$pp=[];
    	$kp=[];
    	$propn=[];
    	$proyek=[];

    	$pnk=0;
    	$ppk=0;
    	$kpk=0;
    	$propnk=0;
    	$proyekk=0;

    	$pointer='';
    	$pnx=[];

    	$sheet_count=($spreadsheet->getSheetCount());
    	for($i=0;$i<$sheet_count;$i++){
    		$sheet = $spreadsheet->setActiveSheetIndex($i);

    		foreach ($sheet->toArray() as $key => $d) {

    			


    			if(strpos(static::trimParse($d[2]),'INDIKASI  TARGET')===false){


    					if((static::trimParse($d[0])!='') OR (static::trimParse($d[1])!='')){

    				// print_r($d);
    				// print('<br>');
    				// print('<br>');

    				if(strpos(static::trimParse($d[0]), 'PN :')!==false){
    					// print('<br>');
    					// print('-----PN--');
    					// print('<br>');
			    		$pnk+=1;
			    	

	    				$pn[$pnk]=[
				    		'info_path'=>'RPJMN.'.static::numering($pnk),
				    		// 'kode'=>,
				    		'nama'=>static::trimParse($d[0]),
		    				'jenis'=>'PN',
				    		'indikator'=>[],
				    		'child'=>[]
			    		];

				    	$ppk=0;
				    	$kpk=0;
				    	$propnk=0;
				    	$proyekk=0;

	    			}else if(static::trimParse($d[2])=='INDIKASI TARGET'){
	    				// if($pn!=[]){
		    			// 	$data_return[$pnk]=$pn;
	    				// 	$pn=[];
	    				// }

    					// $pn=$pnx;

	    				$pointer='PN';
	    				$pointer_ind='PN';


	    				
	    			}else{


	    				if(strpos(static::trimParse($d[0]),'PP :')!==false){
					    	$ppk+=1;
					    	$kpk=0;
					    	$propnk=0;
					    	$proyekk=0;
					    	$indikator=0;

	    					$pp=[
					    		'info_path'=>'RPJMN.'.static::numering($pnk).'.'.static::numering($ppk),
					    		'nama'=>trim(static::trimParse($d[0])),
		    					'jenis'=>'PP',
					    		'indikator'=>[],
					    		'child'=>[]
				    		];

			    			$pn[$pnk]['child'][$ppk]=$pp;
	    					


	    					$pointer='PP';
	    					$pointer_ind='PP';


	    				}else if(strpos(static::trimParse($d[0]),'KP :')!==false){
	    					$pointer='KP';
	    					$pointer_ind='KP';

	    					$kpk+=1;
	    					$propnk=0;
					    	$proyekk=0;
					    	$indikator=0;

					    	
	    					

	    					$kp=[
					    		'info_path'=>'RPJMN.'.static::numering($pnk).'.'.static::numering($ppk).'.'.static::numering($kpk),
					    		'nama'=>static::trimParse($d[0]),
		    					'jenis'=>'KP',

					    		'indikator'=>[],

					    		'child'=>[]
				    		];	

				    		$pn[$pnk]['child'][$ppk]['child'][$kpk]=$kp;

		    				
	    					

	    				}else if(strpos(static::trimParse($d[0]),'ProP :')!==false){
	    					$pointer='PROPN';
	    					$pointer_ind='PROPN';

	    					$propnk+=1;
					    	$proyekk=0;
					    	$indikator=0;


	    					$propn=[
					    		'info_path'=>'RPJMN.'.static::numering($pnk).'.'.static::numering($ppk).'.'.static::numering($kpk).'.'.static::numering($propnk),
					    		'nama'=>static::trimParse($d[0]),
		    					'jenis'=>'PROPN',
					    		'indikator'=>[],
					    		'child'=>[]
				    		];	

				    		$pn[$pnk]['child'][$ppk]['child'][$kpk]['child'][$propnk]=$propn;


	    				}else if((static::trimParse($d[0]))!=''){
	    					switch ($pointer) {
	    						case 'PP':
		    						$proyekk+=1;
					    			$indikator=0;


			    					$proyek=[
			    						'info_path'=>'RPJMN.'.static::numering($pnk).'.'.static::numering($ppk).'.PRY.'.static::numering($proyekk),
			    						'nama'=>static::trimParse($d[0]),
			    						'jenis'=>'PRONAS',
			    						'indikator'=>[],
			    						'child'=>[]
			    					];

			    					$pointer_ind='PROYEK';
				    				$pn[$pnk]['child'][$ppk]['proyek'][$proyekk]=$proyek;



	    							# code...
	    							break;
	    						case 'KP':
		    						$proyekk+=1;
					    			$indikator=0;


		    						
			    					$proyek=[
			    						'info_path'=>'RPJMN.'.static::numering($pnk).'.'.static::numering($ppk).'.'.static::numering($kpk).'.PRY.'.static::numering($proyekk),
			    						'nama'=>static::trimParse($d[0]),
			    						'jenis'=>'PRONAS',
			    						'indikator'=>[],
			    						'child'=>[]
			    					];
				    				$pn[$pnk]['child'][$ppk]['child'][$kpk]['proyek'][$proyekk]=$proyek;

			    					$$pointer_ind='PROYEK';


	    							# code...
	    							break;
  
	    						case 'PROPN':
		    						$proyekk+=1;
					    			$indikator=0;
		    						
			    					$proyek=[
			    						'info_path'=>'RPJMN.'.static::numering($pnk).'.'.static::numering($ppk).'.'.static::numering($kpk).'.'.static::numering($propnk).'.PRY.'.static::numering($proyekk),
			    						'nama'=>static::trimParse($d[0]),
			    						'jenis'=>'PRONAS',
			    						'indikator'=>[],
			    						'child'=>[]
			    					];
				    				$pn[$pnk]['child'][$ppk]['child'][$kpk]['child'][$propnk]['proyek'][$proyekk]=$proyek;


			    					$pointer_ind='PROYEK';


	    							# code...
	    							break;
	    						
	    						default:
	    							# code...
	    							break;
	    					}

	    				}

	    				if(static::trimParse($d[1])!=''){
	    					if(!isset($pointer_ind)){
	    						dd($d);
	    					}
	    					switch ($pointer_ind) {
	    						case 'PP':
	    							$indikator+=1;
	    							$pn[$pnk]['child'][$ppk]['indikator'][$indikator]=[
	    								'info_path'=>'RPJMN.'.static::numering($pnk).'.'.static::numering($ppk).'.IND.'.static::numering($indikator,3),
	    								'nama'=>static::trimParse($d[1]),
	    								'jenis'=>'PP',
	    								'target_1_1'=>static::trimNum($d[2]),
	    								'target_2_1'=>static::trimNum($d[3]),
	    								'target_3_1'=>static::trimNum($d[4]),
	    								'target_4_1'=>static::trimNum($d[5]),
	    								'target_5_1'=>static::trimNum($d[6]),
	    								'anggaran'=>(float)static::trimNum($d[7]),
	    								'lokasi'=>static::trimParse($d[8]),
	    								'major'=>static::trimParse($d[9]),
	    								'instansi'=>static::trimParse($d[10]),

	    							];
	    							
	    							# code...
	    							break;
	    						case 'KP':
	    							$indikator+=1;

	    							$pn[$pnk]['child'][$ppk]['child'][$kpk]['indikator'][$indikator]=[
	    								'info_path'=>'RPJMN.'.static::numering($pnk).'.'.static::numering($ppk).'.'.static::numering($kpk).'.IND.'.static::numering($indikator,3),
	    								'nama'=>static::trimParse($d[1]),
	    								'jenis'=>'KP',
	    								'target_1_1'=>static::trimNum($d[2]),
	    								'target_2_1'=>static::trimNum($d[3]),
	    								'target_3_1'=>static::trimNum($d[4]),
	    								'target_4_1'=>static::trimNum($d[5]),
	    								'target_5_1'=>static::trimNum($d[6]),
	    								'anggaran'=>(float)static::trimNum($d[7]),
	    								'lokasi'=>static::trimParse($d[8]),
	    								'major'=>static::trimParse($d[9]),
	    								'instansi'=>static::trimParse($d[10]),

	    							];
	    							# code...
	    							# code...
	    							break;
	    						
	    						case 'PROPN':
	    							$indikator+=1;

	    							$pn[$pnk]['child'][$ppk]['child'][$kpk]['child'][$propnk]['indikator'][$indikator]=[
	    								'info_path'=>'RPJMN.'.static::numering($pnk).'.'.static::numering($ppk).'.'.static::numering($kpk).'.'.static::numering($propnk).'.IND.'.static::numering($indikator,3),
	    								'nama'=>static::trimParse($d[1]),
	    								'jenis'=>'PROPN',
	    								'target_1_1'=>static::trimNum($d[2]),
	    								'target_2_1'=>static::trimNum($d[3]),
	    								'target_3_1'=>static::trimNum($d[4]),
	    								'target_4_1'=>static::trimNum($d[5]),
	    								'target_5_1'=>static::trimNum($d[6]),
	    								'anggaran'=>(float)static::trimNum($d[7]),
	    								'lokasi'=>static::trimParse($d[8]),
	    								'major'=>static::trimParse($d[9]),
	    								'instansi'=>static::trimParse($d[10]),

	    							];
	    							# code...
	    							break;

	    						case 'PROYEK':
	    							$indikator+=1;
	    						
	    							switch ($pointer) {
	    								case 'PP':
	    									$pn[$pnk]['child'][$ppk]['proyek'][$proyekk]['indikator'][$indikator]=[
		    								'info_path'=>'RPJMN.'.static::numering($pnk).'.'.static::numering($ppk).'.PRY.'.static::numering($proyekk).'.IND.'.static::numering($indikator,3),
		    								'nama'=>static::trimParse($d[1]),
		    								'jenis'=>'PRONAS',
		    								'target_1_1'=>static::trimNum($d[2]),
		    								'target_2_1'=>static::trimNum($d[3]),
		    								'target_3_1'=>static::trimNum($d[4]),
		    								'target_4_1'=>static::trimNum($d[5]),
		    								'target_5_1'=>static::trimNum($d[6]),
		    								'anggaran'=>(float)static::trimNum($d[7]),
		    								'lokasi'=>static::trimParse($d[8]),
		    								'major'=>static::trimParse($d[9]),
		    								'instansi'=>static::trimParse($d[10]),
		    							];
	    									# code...
	    									break;
	    								case 'KP':
	    									$pn[$pnk]['child'][$ppk]['child'][$kpk]['proyek'][$proyekk]['indikator'][$indikator]=[
    										'info_path'=>'RPJMN.'.static::numering($pnk).'.'.static::numering($ppk).'.'.static::numering($kpk).'.PRY.'.static::numering($proyekk).'.IND.'.static::numering($indikator,3),
		    								'nama'=>static::trimParse($d[1]),
		    								'jenis'=>'PRONAS',
		    								'target_1_1'=>static::trimNum($d[2]),
		    								'target_2_1'=>static::trimNum($d[3]),
		    								'target_3_1'=>static::trimNum($d[4]),
		    								'target_4_1'=>static::trimNum($d[5]),
		    								'target_5_1'=>static::trimNum($d[6]),
		    								'anggaran'=>(float)static::trimNum($d[7]),
		    								'lokasi'=>static::trimParse($d[8]),
		    								'major'=>static::trimParse($d[9]),
		    								'instansi'=>static::trimParse($d[10]),
		    							];
	    									# code...
	    									break;

	    								case 'PROPN':
	    									$pn[$pnk]['child'][$ppk]['child'][$kpk]['child'][$propnk]['proyek'][$proyekk]['indikator'][$indikator]=[
    										'info_path'=>'RPJMN.'.static::numering($pnk).'.'.static::numering($ppk).'.'.static::numering($kpk).'.'.static::numering($propnk).'.PRY.'.static::numering($proyekk).'.IND.'.static::numering($indikator,3),
		    								'nama'=>static::trimParse($d[1]),
		    								'jenis'=>'PRONAS',
		    								'target_1_1'=>static::trimNum($d[2]),
		    								'target_2_1'=>static::trimNum($d[3]),
		    								'target_3_1'=>static::trimNum($d[4]),
		    								'target_4_1'=>static::trimNum($d[5]),
		    								'target_5_1'=>static::trimNum($d[6]),
		    								'anggaran'=>(float)static::trimNum($d[7]),
		    								'lokasi'=>static::trimParse($d[8]),
		    								'major'=>static::trimParse($d[9]),
		    								'instansi'=>static::trimParse($d[10]),
		    							];
	    									# code...
	    									break;
	    								
	    								default:
	    									# code...
	    									break;
	    							}
	    							
	    							break;
	    						
	    						default:
	    							# code...
	    							break;
	    					}

	    				}

	    			}
    			}else{
    				
    			}
    			}	
    		

    		}
    	}

    	Storage::put('RPJMN_FINAL_2.json',json_encode(static::convert_from_latin1_to_utf8_recursively($pn),JSON_PRETTY_PRINT));
    	// dd($pn[1]['child'][1]);



    }

      public static function convert_from_latin1_to_utf8_recursively($dat)
   {
      if (is_string($dat)) {
         return utf8_encode($dat);
      } elseif (is_array($dat)) {
         $ret = [];
         foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);

         return $ret;
      } elseif (is_object($dat)) {
         foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

         return $dat;
      } else {
         return $dat;
      }
   }


   public static function susun($index,$data,$spreadsheet){
   	$kol='B';
   	$start=3;
   	$sheet = $spreadsheet->getActiveSheet();
   	if(!isset($data['jenis'])){
   		dd($data);
   	}

   	switch ($data['jenis']) {
   		case 'PN':
   			$kol='B';
   			# code...
   			break;
   		case 'PP':
   			$kol='C';
   			# code...
   			break;
   		case 'KP':
   			$kol='D';
   			# code...
   			break;
   		case 'PROPN':
   			$kol='E';
   			# code...
   			break;
   		case 'PROYEK':
   			$kol='F';
   			# code...
   			break;
   		
   		default:
   			# code...
   			break;
   	}

   	if(!(in_array($kol,['B','C']))){
   		// dd($kol);
   	}

   		$pn=$data;
		$sheet->setCellValue('A'.($start+$index), $pn['id']);
		$sheet->setCellValue($kol.($start+$index), $pn['nama']);
		$index+=1;

		foreach ($pn['indikator'] as $key => $ind) {
			$sheet->setCellValue('A'.($start+$index), $ind['id']);
			$sheet->setCellValue('G'.($start+$index), $ind['nama']);
			$sheet->setCellValue('H'.($start+$index), $ind['major']);
			$sheet->setCellValue('I'.($start+$index), $ind['lokasi']);
			$sheet->setCellValue('J'.($start+$index), $ind['anggaran']);
			$sheet->setCellValue('K'.($start+$index), $ind['target_1_1']);
			$sheet->setCellValue('L'.($start+$index), $ind['target_2_1']);
			$sheet->setCellValue('M'.($start+$index), $ind['target_3_1']);
			$sheet->setCellValue('N'.($start+$index), $ind['target_4_1']);
			$sheet->setCellValue('O'.($start+$index), $ind['target_5_1']);
			$sheet->setCellValue('P'.($start+$index), $ind['instansi']);
			$index+=1;

		}

		if(isset($pn['proyek'])){
			foreach ($pn['proyek'] as $key => $proyek) {
				$sheet->setCellValue('A'.($start+$index), $proyek['id']);
				$sheet->setCellValue('F'.($start+$index), $proyek['nama']);
				$index+=1;

				foreach ($proyek['indikator'] as $key => $ind) {
					$sheet->setCellValue('A'.($start+$index), $ind['id']);
					$sheet->setCellValue('G'.($start+$index), $ind['nama']);
					$sheet->setCellValue('H'.($start+$index), $ind['major']);
					$sheet->setCellValue('I'.($start+$index), $ind['lokasi']);
					$sheet->setCellValue('J'.($start+$index), $ind['anggaran']);
					$sheet->setCellValue('K'.($start+$index), $ind['target_1_1']);
					$sheet->setCellValue('L'.($start+$index), $ind['target_2_1']);
					$sheet->setCellValue('M'.($start+$index), $ind['target_3_1']);
					$sheet->setCellValue('N'.($start+$index), $ind['target_4_1']);
					$sheet->setCellValue('O'.($start+$index), $ind['target_5_1']);
					$sheet->setCellValue('P'.($start+$index), $ind['instansi']);
					$index+=1;
				}
			}
		};

		

	return  array('spreadsheet' =>$spreadsheet ,'index'=>$index);

   }

   public function build(){


   		$data=file_get_contents(storage_path('app/RPJMN_FINAL_2.json'));
   		$data=json_decode($data,true);

   		$spreadsheet = new Spreadsheet();
		
		$start=3;
		$index=0;
		
		foreach ($data as $key => $pn) {
				$r=static::susun($index,$pn,$spreadsheet);
				$spreadsheet=$r['spreadsheet'];
				$index=$r['index'];
			foreach ($pn['child'] as $key => $pp) {
				$r=static::susun($index,$pp,$spreadsheet);
				$spreadsheet=$r['spreadsheet'];
				$index=$r['index'];
				foreach ($pp['child'] as $key => $kp) {
				$r=static::susun($index,$kp,$spreadsheet);
				$spreadsheet=$r['spreadsheet'];
				$index=$r['index'];
					foreach ($kp['child'] as $key => $propn) {
							$r=static::susun($index,$propn,$spreadsheet);
							$spreadsheet=$r['spreadsheet'];
							$index=$r['index'];

					}
				# code...
				}
				# code...
			}
			# code...
		}
		
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="myfile.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');

		// $writer = new Xlsx($spreadsheet);
		// $writer->save('hello world.xlsx');


   }


   public function store(){
   	static::index();
   	
   	$table_rpjmn=HP::get_rpjmn_table(null,2020);
   	$table_rpjmn_indikator=HP::get_rpjmn_table('indikator',2020);

   	$data=file_get_contents(storage_path('app/RPJMN_FINAL_2.json'));

   	$data=json_decode($data,true);

   	if(!Schema::connection('rpjmn')->hasTable($table_rpjmn)){
   		DB::connection('rpjmn')->statement("
   			CREATE TABLE ".$table_rpjmn." (
			id bigserial NOT NULL,
			nama text NOT NULL,
			jenis varchar(10) NOT NULL,
			id_pn int8 NULL,
			id_pp int8 NULL,
			id_kp int8 NULL,
			id_propn int8 NULL,
			info_path varchar(255) NULL,
			created_at timestamp NULL,
			updated_at timestamp NULL,
			CONSTRAINT ".$table_rpjmn."_info_path_unique UNIQUE (info_path),
			CONSTRAINT ".$table_rpjmn."_pkey PRIMARY KEY (id),
			CONSTRAINT ".$table_rpjmn."_id_kp_foreign FOREIGN KEY (id_kp) REFERENCES kebijakan.master_2020_2024_rpjmn(id) ON UPDATE CASCADE ON DELETE CASCADE,
			CONSTRAINT ".$table_rpjmn."_id_pn_foreign FOREIGN KEY (id_pn) REFERENCES kebijakan.master_2020_2024_rpjmn(id) ON UPDATE CASCADE ON DELETE CASCADE,
			CONSTRAINT ".$table_rpjmn."_id_pp_foreign FOREIGN KEY (id_pp) REFERENCES kebijakan.master_2020_2024_rpjmn(id) ON UPDATE CASCADE ON DELETE CASCADE,
			CONSTRAINT ".$table_rpjmn."_id_propn_foreign FOREIGN KEY (id_propn) REFERENCES kebijakan.master_2020_2024_rpjmn(id) ON UPDATE CASCADE ON DELETE CASCADE
			)
   		");
   	}

   	   	if(!Schema::connection('rpjmn')->hasTable($table_rpjmn_indikator)){
   		DB::connection('rpjmn')->statement("
   			CREATE TABLE ".$table_rpjmn_indikator." (
			id bigserial NOT NULL,
			nama text NOT NULL,
			major text NULL,
			jenis varchar(10) NOT NULL,
			id_pn int8 NULL,
			id_pp int8 NULL,
			id_kp int8 NULL,
			id_propn int8 NULL,
			id_pronas int8 NULL,
			target_1_1 varchar(255) NULL,
			target_1_2 varchar(255) NULL,
			target_2_1 varchar(255) NULL,
			target_2_2 varchar(255) NULL,
			target_3_1 varchar(255) NULL,
			target_3_2 varchar(255) NULL,
			target_4_1 varchar(255) NULL,
			target_4_2 varchar(255) NULL,
			target_5_1 varchar(255) NULL,
			target_5_2 varchar(255) NULL,
			satuan varchar(255) NULL,
			cal_type varchar(255) NULL,
			this_numeric_type bool NOT NULL DEFAULT false,
			info_path varchar(255) NULL,
			anggaran float8 NOT NULL DEFAULT 0,
			lokasi text NULL,
			instansi text NULL,
			created_at timestamp NULL,
			updated_at timestamp NULL,
			CONSTRAINT ".$table_rpjmn_indikator."_indikator_info_path_unique UNIQUE (info_path),
			CONSTRAINT tb_2020_2024_rpjmn_indikator_pkey PRIMARY KEY (id),
			CONSTRAINT ".$table_rpjmn_indikator."_indikator_id_kp_foreign FOREIGN KEY (id_kp) REFERENCES kebijakan.master_2020_2024_rpjmn(id) ON UPDATE CASCADE ON DELETE CASCADE,
			CONSTRAINT ".$table_rpjmn_indikator."_indikator_id_pn_foreign FOREIGN KEY (id_pn) REFERENCES kebijakan.master_2020_2024_rpjmn(id) ON UPDATE CASCADE ON DELETE CASCADE,
			CONSTRAINT ".$table_rpjmn_indikator."_indikator_id_pp_foreign FOREIGN KEY (id_pp) REFERENCES kebijakan.master_2020_2024_rpjmn(id) ON UPDATE CASCADE ON DELETE CASCADE,
			CONSTRAINT ".$table_rpjmn_indikator."_indikator_id_pronas_foreign FOREIGN KEY (id_pronas) REFERENCES kebijakan.master_2020_2024_rpjmn(id) ON UPDATE CASCADE ON DELETE CASCADE,
			CONSTRAINT ".$table_rpjmn_indikator."_indikator_id_propn_foreign FOREIGN KEY (id_propn) REFERENCES kebijakan.master_2020_2024_rpjmn(id) ON UPDATE CASCADE ON DELETE CASCADE
		)
   		");

   	}



   	$id_pn=null;
   	$id_pp=null;
   	$id_kp=null;
   	$id_propn=null;


   	foreach ($data as  $pn) {

	   	$id_pn=null;
	   	$id_pp=null;
	   	$id_kp=null;
	   	$id_propn=null;

   		$id_pn=static::storing_data($pn,$id_pn,$id_pp,$id_kp,$id_propn);
   		
		foreach ($pn['child'] as   $pp) {

		   	$id_pp=null;
		   	$id_kp=null;
		   	$id_propn=null;

   			$id_pp=static::storing_data($pp,$id_pn,$id_pp,$id_kp,$id_propn);
			

   			foreach ($pp['child'] as   $kp) {
   				$id_kp=null;
		   		$id_propn=null;
   				$id_kp=static::storing_data($kp,$id_pn,$id_pp,$id_kp,$id_propn);
				foreach ($kp['child'] as   $propn) {
					$id_propn=null;
   					$id_propn=static::storing_data($propn,$id_pn,$id_pp,$id_kp,$id_propn);
						foreach ($kp['child'] as   $proyek) {
   						static::storing_data($proyek,$id_pn,$id_pp,$id_kp,$id_propn);

   						}

   				}


   			}

		}
   		
   	}




   }

   static function storing_data($data=[],$id_pn,$id_pp,$id_kp,$id_propn){
   		$d=$data;
   		$d_indi=$data['indikator'];

   		$table_rpjmn=HP::get_rpjmn_table(null,2020);
   		$table_rpjmn_indikator=HP::get_rpjmn_table('indikator',2020);
   		foreach ($d as $keyk=>$kk) {
   			# code...
   			if(is_array($kk)){
   				unset($d[$keyk]);
   			}
   		}
   		$d['id_pn']=$id_pn;
   		$d['id_pp']=$id_pp;
   		$d['id_kp']=$id_kp;
   		$d['id_propn']=$id_propn;


   		$check1=DB::connection('rpjmn')->table($table_rpjmn)->where('info_path',$d['info_path'])->first();
   		if($check1){
   			$check1= $check1->id;
   		}else{
   			$check1= DB::connection('rpjmn')->table($table_rpjmn)->insertGetId($d);
   		}

   		foreach($d_indi as $ind){

	   		$ind['id_pn']=$id_pn;
	   		$ind['id_pp']=$id_pp;
	   		$ind['id_kp']=$id_kp;
	   		$ind['id_propn']=$id_propn;

	   		$ind['id_'.strtolower($d['jenis'])]=$check1;

   			$check2=DB::connection('rpjmn')->table($table_rpjmn_indikator)->where('info_path',$ind['info_path'])->first();
	   		if($check2){
	   		}else{
	   			$check2=DB::connection('rpjmn')->table($table_rpjmn_indikator)->insertGetId($ind);
	   		}
   		}

   		return $check1;



   }

}
