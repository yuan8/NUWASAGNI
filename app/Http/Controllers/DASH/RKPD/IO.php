<?php

namespace App\Http\Controllers\DASH\RKPD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class IO extends Controller
{

	static $con='myfinal';

    //

    static function condition($d,$col,$index,$sheet,$f,$section){

    	$colom_parent=0;
    	$lp=0;
    	foreach (static::cols()[$section] as $key => $value) {
   			if($key=='urai_u'){
   				$colom_parent=$lp;
   			}
   			$lp++;
   		}


    	$formula=str_replace('yyy', static::$abj[$colom_parent].$index, str_replace('mmm', static::$abj[$col].$index, $f['err_valid']['sub_u']));
    	$conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
    	$conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION);
    	// $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_NONE);
		$conditional1->addCondition(''.$formula);
		$conditional1->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
		$conditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');
		$conditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('FFFFFF00');

		$conditional1->getStyle()->getFont()->setBold(true);

		$sheet->getStyle(static::$abj[$col].$index)->setConditionalStyles([$conditional1]);

		return $sheet;
    }



    static function cols(){
    	$urutan=[
    		'program'=>[
				'context'=>[
					'p'=>1,
					'c'=>1,
					'd'=>0,
					'nama'=>'KONTEXT'
				],
				'kodepemda'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODEPEMDA'
				],
				'nama_daerah'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'NAMA PEMDA'
				],
				'nama_provinsi'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'NAMA PROVINSI'
				],
				'kodeskpd'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODESKPD'
				],
				
				'uraiskpd'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI SKPD'
				],
				'kodebidang'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODE BIDANG'
				],
				'uraibidang'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI BIDANG'
				],
				
				'id_p'=>[
					'p'=>1,
					'c'=>1,
					'd'=>0,
					'nama'=>'ID PROGRAM'
				],
				'kode_p'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODE PROGRAM'
				],
				'kode_c'=>[
					'p'=>0,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODE CAPAIAN'
				],
				'urai_p'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI PROGRAM'
				],
				'urai_c'=>[
					'p'=>0,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI CAPAIAN'
				],
				'target_c'=>[
					'p'=>0,
					'c'=>1,
					'd'=>1,
					'nama'=>'TARGET '
				],
				'satuan_c'=>[
					'p'=>0,
					'c'=>1,
					'd'=>1,
					'nama'=>'SATUAN'
				],
			],
    		'kegiatan'=>[
	    		'context'=>[
					'p'=>1,
					'c'=>1,
					'd'=>0,
					'nama'=>'KONTEXT'
				],
				'kodepemda'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODEPEMDA'
				],
				'nama_daerah'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'NAMA PEMDA'
				],
				'nama_provinsi'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'NAMA PROVINSI'
				],

				'kodeskpd'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODESKPD'
				],
				
				'uraiskpd'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI SKPD'
				],
				'kodebidang'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODE BIDANG'
				],
				'uraibidang'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI BIDANG'
				],
				
				'id_p'=>[
					'p'=>1,
					'c'=>1,
					'd'=>0,
					'nama'=>'ID PROGRAM'
				],
				'id_k'=>[
					'p'=>1,
					'c'=>1,
					'd'=>0,
					'nama'=>'ID KEGIATAN'
				],
				'kode_p'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODE PROGRAM'
				],
				'kode_k'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODE KEGIATAN'
				],
				'kode_i'=>[
					'p'=>0,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODE INDIKATOR'
				],
				'urai_p'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI PROGRAM'
				],
				'urai_k'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI KEGIATAN'
				],
				'anggaran_k'=>[
					'd'=>1,
					'p'=>1,
					'c'=>0,
					'nama'=>'PAGU KEGIATAN'

				],
				'urai_i'=>[
					'p'=>0,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI INDIKATOR'
				],
				'urai_u'=>[
					'f'=>'nama_bidang',
					'd'=>1,
					'im'=>[
						'p'=>1,
						'c'=>0,
						'd'=>1,
					],
					'nama'=>'URAI BIDANG'
				],
				'urai_s'=>[
					'f'=>'nama_sub_urusan',
					'd'=>1,
					'im'=>[
						'p'=>1,
						'c'=>0,
						'd'=>1,
					
					],
					'nama'=>'URAI SUB URUSAN'
				],
				'id_u'=>[
					'f'=>'id_urusan',
					'd'=>0,
					'im'=>[
						'p'=>1,
						'c'=>0,
						'd'=>1,
					
					],
					'nama'=>'ID URUSAN'
				],
				'id_s'=>[
					'f'=>'id_sub_urusan',
					'd'=>0,
					'im'=>[
						'p'=>1,
						'c'=>0,
						'd'=>1,
					
					],
					'nama'=>'ID SUB URUSAN'
				],
				'urai_jenis_k'=>[
					'f'=>'nama_jenis_kegiatan',
					'd'=>1,
					'im'=>[
						'p'=>1,
						'c'=>0,
						'd'=>1,
					],
					'nama'=>'JENIS KEGIATAN'
				],
				'kode_jenis_k'=>[
					'f'=>'kode_jenis_kegiatan',
					'd'=>0,
					'im'=>[
						'p'=>1,
						'c'=>0,
						'd'=>1,
					],
					'nama'=>'KODE JENIS KEGIATAN'
				],
				'anggaran_i'=>[
					'd'=>1,
					'p'=>0,
					'c'=>1,
					'nama'=>'PAGU INDIKATOR'
				],
				'target_i'=>[
					'd'=>1,
					'p'=>0,
					'c'=>1,
					'nama'=>'TARGET INDIKATOR'
				],
				'satuan_i'=>[
					'd'=>1,
					'p'=>0,
					'c'=>1,
					'nama'=>'SATUAN INDIKATOR'


				]

			],
			'kegiatan_sumberdana'=>[
				'context'=>[
					'p'=>1,
					'c'=>1,
					'd'=>0,
					'nama'=>'KONTEXT'
				],
				'kodepemda'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODEPEMDA'
				],
				'nama_daerah'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'NAMA PEMDA'
				],
				'nama_provinsi'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'NAMA PROVINSI'
				],

				'kodeskpd'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODESKPD'
				],
				
				'uraiskpd'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI SKPD'
				],
				'kodebidang'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODE BIDANG'
				],
				'uraibidang'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI BIDANG'
				],
				
				'id_p'=>[
					'p'=>1,
					'c'=>1,
					'd'=>0,
					'nama'=>'ID PROGRAM'
				],
				'id_k'=>[
					'p'=>1,
					'c'=>1,
					'd'=>0,
					'nama'=>'ID KEGIATAN'
				],
				'id_ksd'=>[
					'p'=>0,
					'c'=>1,
					'd'=>0,
					'nama'=>'ID SUMBERDANA'
				],
				'kode_p'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODE PROGRAM'
				],
				'kode_k'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'KODE KEGIATAN'
				],
				
				'urai_p'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI PROGRAM'
				],
				'urai_k'=>[
					'p'=>1,
					'c'=>1,
					'd'=>1,
					'nama'=>'URAI KEGIATAN'
				],
				'anggaran_k'=>[
					'd'=>1,
					'p'=>1,
					'c'=>0,
					'nama'=>'PAGU KEGIATAN'

				],
				'kode_ksd'=>[
					'p'=>0,
					'c'=>1,
					'd'=>0,
					'nama'=>'KODE SUMBERDANA'
				],
				'urai_ksd'=>[
					'p'=>0,
					'c'=>1,
					'd'=>1,
					'nama'=>'SUMBERDANA'
				],
				'anggaran_ksd'=>[
					'p'=>0,
					'c'=>1,
					'd'=>1,
					'nama'=>'PAGU SUMBERDANA'
				],
			]
			
	    ];

		return $urutan;
    }




    static function border($index,$sheet,$section,$max_coll=null){
    	$styleArray = [
		    'borders' => [
		        'allBorders' => [
		            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
		            'color' => ['argb' => 'dddddd'],
		        ],
		    ],
		];

		if($max_coll==null){
			$sheet->getStyle('A'.$index.':'.static::$abj[count(static::cols()[$section])-1].$index )->applyFromArray($styleArray);

		}else{
			$sheet->getStyle($max_coll)->applyFromArray($styleArray);

		}

    
		return $sheet;
    }



    static function nama_spm($d,$col,$index,$sheet,$f,$section){

    }



    static function id_urusan($d,$col,$index,$sheet,$f,$section){


   		$colom_parent=0;
   		$lp=0;
   		foreach (static::cols()[$section] as $key => $value) {
   			
   			if($key=='urai_u'){
   				$colom_parent=$lp;
   			}
   			$lp++;
   		}

   		$formula=str_replace('yyy',static::$abj[$colom_parent].$index, str_replace('mmm',static::$abj[$colom_parent].$index, $f['peta_kode_urusan_sub']));

    	$sheet->setCellValue((static::$abj[$col].$index),'='.$formula);
		

		return $sheet;

    }

    static function id_sub_urusan($d,$col,$index,$sheet,$f,$section){


   		$colom_parent=0;
   		$colom_parent_2=0;

   		$lp=0;

   		foreach (static::cols()[$section] as $key => $value) {
   			
   			if($key=='urai_u'){
   				$colom_parent=$lp;
   			}
   			if($key=='urai_s'){
   				$colom_parent_2=$lp;
   			}

   			$lp++;
   		}
   		$formula=str_replace('yyy',static::$abj[$colom_parent].$index, str_replace('mmm',static::$abj[$colom_parent_2].$index, $f['peta_kode_urusan_sub']));

    	$sheet->setCellValue((static::$abj[$col].$index),'='.$formula);
		

		return $sheet;
    	
    }



    static function kode_jenis_kegiatan($d,$col,$index,$sheet,$f,$section){
    	$colom_parent=0;
    	$lp=0;
    	foreach (static::cols()[$section] as $key => $value) {
   			if($key=='urai_jenis_k'){
   				$colom_parent=$lp;
   			}
   			$lp++;
   		}

	
		$formula="IF(".static::$abj[$colom_parent].$index.'="PENDUKUNG",2,(IF('.static::$abj[$colom_parent].$index.'="UTAMA",1,"")))';	
		
   		$sheet->setCellValue(static::$abj[$col].$index,'='.$formula);
   		return $sheet;

    }

    static function kode_spm(){
    	

    }


    static  function nama_jenis_kegiatan($d,$col,$index,$sheet,$f,$section){
    	$validation_ur = $sheet->getCell(static::$abj[$col].$index)
		    ->getDataValidation();
		$validation_ur->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$validation_ur->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
		$validation_ur->setAllowBlank(true);
		$validation_ur->setShowInputMessage(true);
		$validation_ur->setShowErrorMessage(true);
		$validation_ur->setShowDropDown(true);
		$validation_ur->setErrorTitle('Input error');
		$validation_ur->setError('Value is not in list.');
		$validation_ur->setPromptTitle('Pick from list');
		$validation_ur->setPrompt('Please pick a value from the drop-down list.');
		$validation_ur->setFormula1('"PENDUKUNG,UTAMA"');
		$sheet->setCellValue(static::$abj[$col].$index,$d['urai_jenis_k']);



		return $sheet;
    }

    static function nama_bidang($d,$col,$index,$sheet,$f,$section){


    	$validation_ur = $sheet->getCell(static::$abj[$col].$index)
		    ->getDataValidation();

		$validation_ur->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$validation_ur->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
		$validation_ur->setAllowBlank(true);
		$validation_ur->setShowInputMessage(true);
		$validation_ur->setShowErrorMessage(true);
		$validation_ur->setShowDropDown(true);
		$validation_ur->setErrorTitle('Input error');
		$validation_ur->setError('Value is not in list.');
		$validation_ur->setPromptTitle('Pick from list');
		$validation_ur->setPrompt('Please pick a value from the drop-down list.');
		$validation_ur->setFormula1($f['peta_urusan']);
		$sheet->setCellValue(static::$abj[$col].$index,$d['urai_u']);

		return $sheet;

   	}

   	static function nama_sub_urusan($d,$col,$index,$sheet,$f,$section){

   		$colom_parent=0;
   		$lp=0;
   		foreach (static::cols()[$section] as $key => $value) {
   			
   			if($key=='urai_u'){
   				$colom_parent=$lp;
   			}
   			$lp++;
   		}


    	$validation_ur = $sheet->getCell(static::$abj[$col].$index)
		    ->getDataValidation();
		$validation_ur->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$validation_ur->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
		$validation_ur->setAllowBlank(true);
		$validation_ur->setShowInputMessage(true);
		$validation_ur->setShowErrorMessage(true);
		$validation_ur->setShowDropDown(true);
		$validation_ur->setErrorTitle('Input error');
		$validation_ur->setError('Value is not in list.');
		$validation_ur->setPromptTitle('Pick from list');
		$validation_ur->setPrompt('Please pick a value from the drop-down list.');
		$formula=str_replace('yyy',static::$abj[$colom_parent].$index, $f['peta_sub_urusan']);
		$validation_ur->setFormula1($formula);
		$sheet->setCellValue(static::$abj[$col].$index,$d['urai_s']);


   		$sheet=static::condition($d,$col,$index,$sheet,$f,$section);


		return $sheet;

   	}


   	static $parent='';

    static function append($d,$index,$context,$sheet,$f,$section){
    	$data=[];
    	$ll=0;
    	$sheet=static::border($index,$sheet,$section);

    	if($context=='p'){
    		$sheet->getStyle(static::$abj[0].$index.':'.static::$abj[count(static::cols()[$section])-1].$index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('aadfba');
    	}else{
    		$sheet->getStyle(static::$abj[0].$index.':'.static::$abj[count(static::cols()[$section])-1].$index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('6fc1e4');
    	}


    	foreach (static::cols()[$section] as $key => $v) {
    		if(isset($v['f'])){
    			

    			if($context=='p'){
    				if($v['im']['p']==1){
    					$sheet->getStyle(static::$abj[$ll].$index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffffff');
    					
    					static::$parent=$index;
    			

    					switch ($v['f']) {
    						case 'nama_bidang':
    							# code...
    							$sheet=static::nama_bidang($d,$ll,$index,$sheet,$f,$section);

    							break;
    						case 'nama_sub_urusan':
    							# code...
    							$sheet=static::nama_sub_urusan($d,$ll,$index,$sheet,$f,$section);

    							break;
    						case 'id_urusan':
    							# code...
    							$sheet=static::id_urusan($d,$ll,$index,$sheet,$f,$section);

    							break;
    						case 'id_sub_urusan':
    							# code...
    							$sheet=static::id_sub_urusan($d,$ll,$index,$sheet,$f,$section);

    							break;
    						case 'nama_jenis_kegiatan':
    							# code...
    							$sheet=static::nama_jenis_kegiatan($d,$ll,$index,$sheet,$f,$section);

    							break;
    						
    						case 'kode_jenis_kegiatan':
    							# code...
    							$sheet=static::kode_jenis_kegiatan($d,$ll,$index,$sheet,$f,$section);

    						break;
    						
    						default:
    							# code...
    							break;
    					}

    				}else{
    					$sheet->setCellValue(static::$abj[$ll].$index,null);	
    				}


    			}else{

    				if($v['im']['c']==1){

	    					switch ($v['f']) {
	    						case 'nama_bidang':
	    							# code...
	    							$sheet=static::nama_bidang($d,$ll,$index,$sheet,$f,$section);

	    							break;
	    						case 'nama_sub_urusan':
	    							# code...
	    							$sheet=static::nama_sub_urusan($d,$ll,$index,$sheet,$f,$section);

	    							break;
	    						case 'id_urusan':
	    							# code...
	    							$sheet=static::id_urusan($d,$ll,$index,$sheet,$f,$section);

	    							break;
	    						case 'id_sub_urusan':
	    							# code...
	    							$sheet=static::id_sub_urusan($d,$ll,$index,$sheet,$f,$section);

	    							break;
	    						case 'nama_jenis_kegiatan':
	    							# code...
	    							$sheet=static::nama_jenis_kegiatan($d,$ll,$index,$sheet,$f,$section);

	    							break;
	    						
	    						case 'kode_jenis_kegiatan':
	    							# code...
	    							$sheet=static::kode_jenis_kegiatan($d,$ll,$index,$sheet,$f,$section);

	    							break;
	    						
	    						default:
	    							# code...
	    							break;

	    					}

    					
    					$sheet->getStyle(static::$abj[$ll].$index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffffff');
    					
    				}else{

    					if(static::$parent!=''){
    						// $formula='=IF('.static::$abj[$ll].static::$parent."='',NULL,(IF(".static::$abj[$ll].static::$parent."=NULL,NULL,".static::$abj[$ll].static::$parent.")";
    						$sheet->setCellValue(static::$abj[$ll].$index,'='.static::$abj[$ll].static::$parent);	
    					}else{
    						$sheet->setCellValue(static::$abj[$ll].$index,null);	
    					}
    				}

    			}

    		}else{
    			if($context=='p'){
    				if($v['p']==1){
    					
    					$sheet->setCellValue(static::$abj[$ll].$index,$d[$key]);
    				}else{
    					$sheet->setCellValue(static::$abj[$ll].$index,null);
    				}
    			}else{
    				if($v['c']==1){
    					
    					$sheet->setCellValue(static::$abj[$ll].$index,$d[$key]);
    				}else{
    					$sheet->setCellValue(static::$abj[$ll].$index,null);    					
    				}
    			}
    		}
    		$ll++;
    		# code...
    	}

    	return $sheet;

    }


    static function header($start,$sheet,$section){
    	$data=[];
    	$ll=0;
    	foreach (static::cols()[$section] as $key => $value) {
			$sheet->setCellValue(static::$abj[$ll].$start,$value['nama']);
			
			$sheet->getStyle(static::$abj[$ll].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('6587C0');
			
			
			if($value['d']!=1){
				$sheet->getColumnDimension(static::$abj[$ll])->setVisible(FALSE);
			}

			$ll++;
    	}

    	return $sheet;
    }


    static function mastering($spreadsheet,$kurusan){
    // 	$DD="";

    // 	$abj=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    // 	for($i=0;$i<3;$i++){
    // 		foreach ($abj as $key => $a) {

    // 				$DD.= "'".$abj[$i].$abj[$key]."',";
    // 		}
    // 	}

    // dd($DD);




		$urusan=DB::table('master_urusan as u')->leftJoin('master_sub_urusan as su','u.id','=','su.id_urusan')
		->select('u.id as kode_u','u.nama as nama_u','su.id as kode_s','su.nama as nama_s')
		->orderBy('u.id','asc')
		->orderBy('su.id','asc')
		->whereIn('u.id',$kurusan)
		->get();

		$sheet = $spreadsheet->getSheetByName('MASTER');
		// dd($sheet->getStyle('A1')->getConditionalStyles());

		$col=1;
		$col_nama_u=0;
		$id_u='';

		$max_row=0;

		$start_master=2;
		$start=$start_master;
		$sub_look_up='xxx';
		$kode_look_up='xxx';
		$minil='';
		$mini='';


		foreach ($urusan as $key => $u) {
			if($id_u!=$u->kode_u){
				if($id_u==''){
					$mini='MASTER!$'.static::$abj[$col-1].'$'.($start_master+1);
					$minil='MASTER!$'.static::$abj[$col-1].'$'.($start_master);

				}else{
					$mini.=':$'.static::$abj[$col-1].'$'.($start);
					$minil.=':$'.static::$abj[$col].'$'.($start);

					$kode_look_up=str_replace('xxx','IF(yyy='.'MASTER!$'.static::$abj[$col-1].'$'.$start_master.','.'VLOOKUP(mmm,'.$minil.',2,FALSE)'.',xxx)',$kode_look_up);

					$sub_look_up=str_replace('xxx','IF(yyy='.'MASTER!$'.static::$abj[$col-1].'$'.$start_master.','.$mini.',xxx)',$sub_look_up);
					$col+=2;
					$mini='MASTER!$'.static::$abj[$col-1].'$'.($start_master+1);
					$minil='MASTER!$'.static::$abj[$col-1].'$'.($start_master);
				}


				$start=$start_master;

				$sheet->setCellValue(static::$abj[$col-1].$start,strtoupper($u->nama_u));
				$sheet->getStyle(static::$abj[$col-1].$start.':'.static::$abj[$col].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffc3a0');

				$sheet->setCellValue(static::$abj[$col_nama_u].'1',strtoupper($u->nama_u));
				$sheet->getStyle(static::$abj[$col_nama_u].'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffd700');
				$col_nama_u++;

				$sheet->setCellValue(static::$abj[$col].$start,strtoupper($u->kode_u));

				$start++;
				$id_u=$u->kode_u;
			}
			
			$sheet->setCellValue(static::$abj[$col-1].$start,strtoupper($u->nama_s));
			$sheet->setCellValue(static::$abj[$col].$start,strtoupper($u->kode_s));
			$sheet->getStyle(static::$abj[$col-1].$start.':'.static::$abj[$col].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('afeeee');

			$sheet=static::border($start,$sheet,null,static::$abj[$col-1].$start.':'.static::$abj[$col].$start);
			
			$start++;

			if($start>$max_row){
				$max_row=$start;
			}

			# code...
		}

		$mini.=':$'.static::$abj[$col-1].'$'.($start);
		$minil.=':$'.static::$abj[$col].'$'.($start);

		$kode_look_up=str_replace('xxx','IF(yyy='.'MASTER!$'.static::$abj[$col-1].'$'.$start_master.','.'VLOOKUP(mmm,'.$minil.',2,FALSE)'.',xxx)',$kode_look_up);

		$sub_look_up=str_replace('xxx','IF(yyy='.'MASTER!$'.static::$abj[$col-1].'$'.$start_master.','.$mini.',xxx)',$sub_look_up);

		$sub_look_up=str_replace('xxx','""', $sub_look_up);
		$kode_look_up=str_replace('xxx','""', $kode_look_up);


		$sk=0;
		foreach (static::cols() as $keys => $s) {
			$m_index=0;
			$sheet->setCellValue(static::$abj[$sk].'23',str_replace('_', ' ', strtoupper($keys)));

			foreach ($s as $key => $value) {

				# code...
				$sheet->setCellValue(static::$abj[$m_index].(24+$sk),$key);
				$sheet->getStyle(static::$abj[$m_index].(24+$sk))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff1a55');
				
				$m_index++;
			}

			$sheet->setCellValue(static::$abj[$m_index].(24+$sk),str_replace('_', ' ', strtoupper($keys)));
			$sheet->getStyle(static::$abj[$m_index].(24+$sk))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('6fa832');

			$sk++;

			$sheet=static::border(24,$sheet,$keys,static::$abj[0].(24+$sk).":".static::$abj[$m_index].(24+$sk));

			
		}
		





		return [
			'peta_sub_urusan'=>$sub_look_up,
			'peta_kode_urusan_sub'=>$kode_look_up,
			'peta_urusan'=>'MASTER!$'.static::$abj[0].'$1:$'.static::$abj[$col_nama_u].'$1',
			'err_valid'=>[
				'sub_u'=>'IF(mmm="",FALSE,ISERROR(VLOOKUP(mmm,INDEX(MASTER!$'.static::$abj[0].'$'.$start_master.':$'.static::$abj[$col+1].'$'.$max_row
				.',,MATCH(yyy,MASTER!$'.static::$abj[0].'$'.$start_master.
				':$'.static::$abj[$col+1].'$'.$start_master.',0)),1,0)))'
			],
			'f'=>[
				'spreadsheet'=>$spreadsheet
			]
		];

    }


    static $abj=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ'];


    // static function 

	public function index($kodepemda,Request $request){

		if($request->pemda){
			$kodepemda=$request->pemda;
		}else{
			$kodepemda=[$kodepemda];
		}

		$tahun=HP::fokus_tahun();

		if($request->tahun){
			$tahun=$request->tahun;
		}

		$pemda_d=DB::table('master_daerah')->whereIn('id',$kodepemda)->get();

		if(count($pemda_d)!=1){
			$pemda_d=null;
		}


        // $kurusan=[1,2,3,4,5,6,7,8,9,10,11,12,13,1,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32];

		$kurusan=[3,4];

		$data=DB::connection(static::$con)->table('master_'.$tahun.'_program as p')

		->leftJoin('master_'.$tahun.'_program_capaian as c','p.id','=','c.id_program')
		->leftJoin('master_'.$tahun.'_kegiatan as k','p.id','=','k.id_program')
		->leftJoin('master_'.$tahun.'_kegiatan_indikator as i','k.id','=','i.id_kegiatan')
		->leftJoin('master_'.$tahun.'_kegiatan_sumberdana as ksd','ksd.id_kegiatan','=','k.id')
		->leftJoin('master_urusan as u','u.id','=','k.id_urusan')
		->leftJoin('master_sub_urusan as su','su.id','=','k.id_sub_urusan')
		->select(
			DB::raw('p.kodepemda as kodepemda'),
			DB::raw("(select nama from master_daerah as d where k.kodepemda=d.id limit 1) as nama_daerah"),
			DB::raw("(select nama from master_daerah as d where left(k.kodepemda,2)=d.id limit 1) as nama_provinsi"),
			DB::raw('p.id as id_p'),
			DB::raw('c.id as id_c'),
			DB::raw('k.id as id_k'),
			DB::raw('i.id as id_i'),
			"p.kodeskpd",
			"p.uraiskpd",
			"p.kodebidang",
			"p.uraibidang",
			"p.id_urusan",
			DB::raw('p.kodeprogram as kode_p'),
			DB::raw('k.kodekegiatan as kode_k'),
			DB::raw('c.kodeindikator as kode_c'),
			DB::raw('i.kodeindikator as kode_i'),
			DB::raw('p.uraiprogram as urai_p'),
			DB::raw('c.tolokukur as urai_c'),
			DB::raw('c.target as target_c'),
			DB::raw('c.satuan as satuan_c'),
			DB::raw('k.uraikegiatan as urai_k'),
			DB::raw('i.tolokukur as urai_i'),
			DB::raw('i.target as target_i'),
			DB::raw('i.satuan as satuan_i'),
			DB::raw("k.id_urusan as id_sub_urusan"),
			DB::raw("u.nama as urai_u"),
			DB::raw("su.nama as urai_s"),
			DB::raw("'' as kode_lintas_urusan_k"),
			DB::raw('k.pagu as anggaran_k'),
			DB::raw(" (case when k.jenis=1 then 'UTAMA' when k.jenis=2 then 'PENDUKUNG' else null end) as urai_jenis_k"),
			DB::raw('i.pagu as anggaran_i'),
			DB::raw('k.jenis as kode_jenis_k'),
			DB::raw('ksd.id as id_ksd'),
			DB::raw('ksd.kodesumberdana as kode_ksd'),
			DB::raw('ksd.sumberdana as urai_ksd'),
			DB::raw('ksd.pagu as anggaran_ksd')

		)
		->orderBy('p.id_urusan','desc')
		->orderBy('p.id','asc')
		->orderBy('k.id','asc')
		->orderBy('c.id','asc')
		->orderBy('i.id','asc');


		if($request->urusan){
			$kurusan=$request->urusan;
			$data=$data->whereRaw(
			"k.id_urusan in (".implode(',', $kurusan).") and k.kodepemda in ('".implode(",'",$kodepemda)."')"
			);
		}else{

			$data=$data->whereRaw(
			"k.id_urusan in (".implode(',', $kurusan).") and k.kodepemda in ('".implode(",'",$kodepemda)."')"
			);

			// ->orWhereRaw(
			// 	"k.id_urusan is null and k.kodepemda in ('".implode(',',$kodepemda)."')"
			// );

		}
		
		$shname=['PROGRAM','KEGIATAN'];

		$data=$data->get()->toArray();
    	$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('template_export/RKPD.xlsx'));
		$master=(static::mastering($spreadsheet,$kurusan));

		// dd($master);

		$spreadsheet=$master['f']['spreadsheet'];

		$f=[];

		foreach ($master as $key => $value) {
			if($key!='f'){
				$f[$key]=$value;
			}
		}

		$meta['tanggal_download']=Carbon::now()->format('d/m/y h:i:s');
		$meta['tanggal_update']='';
		$meta['tahun']=$tahun;
		$meta['pemda']=[
			'nama'=>'',
			'kode'=>'',

		];

		if($pemda_d){
			$meta['pemda']['nama']=$pemda_d[0]->nama;
			$meta['pemda']['kode']=$pemda_d[0]->id;
			$meta['tanggal_update']='';
		}





		$spreadsheet=static::kegiatan(5,$spreadsheet,$data,$f,$meta);
		$spreadsheet=static::program(5,$spreadsheet,$data,$f,$meta);
		$spreadsheet=static::kegiatan_sumberdana(5,$spreadsheet,$data,$f,$meta);






		$writer = new Xlsx($spreadsheet);

		 $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="RKPD-'.implode('-', $kodepemda).'-'.$tahun.'.xlsx"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
	}


	static function metadata($sheet,$meta){
		$sheet->setCellValue('B2',$meta['pemda']['kode']);
		$sheet->setCellValue('C2',$meta['pemda']['nama']);
		$sheet->setCellValue('D2',$meta['tahun']);
		$sheet->setCellValue('E2',$meta['tanggal_update']);
		$sheet->setCellValue('F2',$meta['tanggal_download']);


		return $sheet;

	}

	static function program($start,$spreadsheet,$data,$f,$meta){
		$id_p='';
		$id_c='';

		$start_master=$start;

		$sheet = $spreadsheet->getSheetByName('PROGRAM');
		$sheet=static::metadata($sheet,$meta);
		$section='program';
		$sheet=static::header($start,$sheet,$section);
		
		$start++;
	
		foreach ($data as $key => $d) {
			$d=(array)$d;

			if($id_p!=$d['id_p']){
				$d['context']='P';
				$sheet=static::append($d,$start,'p',$sheet,$f,$section);
				$id_p=$d['id_p'];
				$start++;		

			}

			if($id_c!=$d['id_c']){
				$d['context']='C';
				$sheet=static::append($d,$start,'c',$sheet,$f,$section);
				$id_c=$d['id_c'];
				$start++;		

			}

		}

		$sheet->setAutoFilter(static::$abj[0].$start_master.':'.static::$abj[count(static::cols()[$section])-1].($start-1));
		$sheet->getStyle(static::$abj[0].$start_master.':'.static::$abj[count(static::cols()[$section])-1].($start-1))->getFont()->setSize(8);

		return $spreadsheet;
		
	}


	static function kegiatan($start,$spreadsheet,$data,$f,$meta){
		$id_k='';
		$id_i='';

		$start_master=$start;

		$sheet = $spreadsheet->getSheetByName('KEGIATAN');
		$sheet=static::metadata($sheet,$meta);

		$section='kegiatan';
		$sheet=static::header($start,$sheet,$section);
		
		$start++;
	
		foreach ($data as $key => $d) {
			$d=(array)$d;

			if($id_k!=$d['id_k']){
				$d['context']='P';
				$sheet=static::append($d,$start,'p',$sheet,$f,$section);
				$id_k=$d['id_k'];
				$start++;		

			}

			if($id_i!=$d['id_i']){
				$d['context']='C';
				$sheet=static::append($d,$start,'c',$sheet,$f,$section);
				$id_i=$d['id_i'];
				$start++;		

			}

		}

		$sheet->setAutoFilter(static::$abj[0].$start_master.':'.static::$abj[count(static::cols()[$section])-1].($start-1));
		$sheet->getStyle(static::$abj[0].$start_master.':'.static::$abj[count(static::cols()[$section])-1].($start-1))->getFont()->setSize(8);

		return $spreadsheet;

	}

	static function kegiatan_sumberdana($start,$spreadsheet,$data,$f,$meta){
		$id_k='';
		$id_ksd='';

		$start_master=$start;

		$sheet = $spreadsheet->getSheetByName('KEGIATAN SUMBERDANA');
		$sheet=static::metadata($sheet,$meta);

		$section='kegiatan_sumberdana';
		$sheet=static::header($start,$sheet,$section);
		
		$start++;
	
		foreach ($data as $key => $d) {
			$d=(array)$d;

			if($id_k!=$d['id_k']){
				$d['context']='P';
				$sheet=static::append($d,$start,'p',$sheet,$f,$section);
				$id_k=$d['id_k'];
				$start++;		

			}

			if($id_ksd!=$d['id_ksd']){
				$d['context']='C';
				$sheet=static::append($d,$start,'c',$sheet,$f,$section);
				$id_ksd=$d['id_ksd'];
				$start++;		

			}

		}

		$sheet->setAutoFilter(static::$abj[0].$start_master.':'.static::$abj[count(static::cols()[$section])-1].($start-1));
		$sheet->getStyle(static::$abj[0].$start_master.':'.static::$abj[count(static::cols()[$section])-1].($start-1))->getFont()->setSize(8);

		return $spreadsheet;

	}

	

	public function upload(Request $request){

		if($request->file){
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
			$sheet = $spreadsheet->getSheetByName('MASTER');
			$master=$sheet->toArray()[23];

			$data=[
				'program'=>[
					'field'=>[
						'kodepemda'=>'kodepemda',
						'tahun'=>'tahun',
						'kodeskpd'=>'kodeskpd',
						'uraiskpd'=>'uraiskpd',
						'kodebidang'=>'kodebidang',
						'uraibidang'=>'uraibidang',
						'id_p'=>'id',
						'urai_p'=>'uraiprogram',
						'kode_p'=>'kodeprogram',

					],
					'data'=>[]
				],
				'capaian'=>[
					'field'=>[
						'kodepemda'=>'kodepemda',
						'tahun'=>'tahun',
						'kodeskpd'=>'kodeskpd',
						'kodebidang'=>'kodebidang',
						'kode_p'=>'kodeprogram',
						'id_p'=>'id_program',
						'kode_c'=>'kodeindikator',
						'id_c'=>'id',
						'urai_c'=>'tolokukur',
						'target_c'=>'target',
						'satuan_c'=>'satuan',

					],
					'data'=>[]
				],
				'kegiatan'=>[
					'field'=>[
						'kodepemda'=>'kodepemda',
						'tahun'=>'tahun',
						'kodeskpd'=>'kodeskpd',
						'kodebidang'=>'kodebidang',
						'kode_p'=>'kodeprogram',
						'id_p'=>'id_program',
						'kode_k'=>'kodekegiatan',
						'id_k'=>'id',
						'urai_k'=>'uraikegiatan',
						'anggaran_k'=>'pagu',


						
					],
					'data'=>[]
				],
				'kegiatan_prioritas'=>[
					'field'=>[],
					'data'=>[]
				],
				'kegiatan_sumberdana'=>[
					'field'=>[],
					'data'=>[]
				],
				'kegiatan_lokasi'=>[
					'field'=>[],
					'data'=>[]
				],
				'kegiatan_indikator'=>[
					'field'=>[
						'kodepemda'=>'kodepemda',
						'tahun'=>'tahun',
						'kodeskpd'=>'kodeskpd',
						'kodebidang'=>'kodebidang',
						'kode_p'=>'kodeprogram',
						'id_p'=>'id_program',
						'kode_k'=>'kodekegiatan',
						'kode_i'=>'kodeindikator',
						'id_i'=>'id',
						'urai_i'=>'tolokukur',
						'target_i'=>'target',
						'satuan_i'=>'target',
						'anggaran_i'=>'pagu'

					],
					'data'=>[]
				],
				'sub_kegiatan'=>[
					'field'=>[],
					'data'=>[]
				],
			];

			$sheet = $spreadsheet->getSheetByName('KEGIATAN');

			foreach ($sheet->toArray() as $k => $d) {
				# code...

				if($k>=5){
					$dy=[];
					foreach ($d as $key => $dx) {
						if(in_array($master[$key], ['id_p','id_k','id_i','id_c','id_u','id_s','id','kode_jenis_k','kode_spm','anggaran_k','anggaran_i'])){
							$dy[$master[$key]]=(float)(!empty($dx)?($dx!=0?$dx:null):null);
						}else{
							$dy[$master[$key]]=(string)($dx);
						}
					}
					
					if($dy['context']='p'){
						$di=[];
						foreach ($dy as $key => $value) {
							if(in_array($key,array_keys($data['kegiatan']['field']))){
								
								$di[$data['kegiatan']['field'][$key]]=$value;
							}
						}

						if($di!=[]){
							dd($di);
							$data['kegiatan']['data'][]=$di;

						}

					}else{

					}

				}



			}





		}


	}

}
