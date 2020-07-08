<?php

namespace App\Http\Controllers\DASH\RKPD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;


class IO extends Controller
{

	static $con='myranwal';

    //

    static $abj=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD'];

	public function index(){


	}

	public function download($kodepemda,Request $request){
		$tahun=HP::fokus_tahun();

		if($request->tahun){
			$tahun=$request->tahun;
		}

		$kurusan=[3,4,1,2,5,6];

		$data=DB::connection(static::$con)->table('master_'.$tahun.'_program as p')

		->leftJoin('master_'.$tahun.'_program_capaian as c','p.id','=','c.id_program')
		->leftJoin('master_'.$tahun.'_kegiatan as k','p.id','=','k.id_program')
		->leftJoin('master_'.$tahun.'_kegiatan_indikator as i','k.id','=','i.id_kegiatan')
		->leftJoin('master_urusan as u','u.id','=','k.id_urusan')
		->leftJoin('master_sub_urusan as su','su.id','=','k.id_sub_urusan')


		->where([
			['p.kodepemda','=',$kodepemda],
			// ['p.status','=',5],
		])
		->select(
			DB::raw('p.kodepemda as kodepemda'),
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
			DB::raw("u.nama as nama_urusan"),
			DB::raw("su.nama as nama_sub_urusan"),
			DB::raw("'' as kode_lintas_urusan_k"),
			DB::raw('k.pagu as anggaran_k'),
			DB::raw(" (case when k.jenis=1 then 'UTAMA' when k.jenis=2 then 'PENDUKUNG' else null end) as jenis_kegiatan"),
			DB::raw('i.pagu as anggaran_i')

		)
		->orderBy('p.id_urusan','desc')
		->orderBy('p.id','asc')
		->orderBy('k.id','asc')
		->orderBy('c.id','asc')
		->orderBy('i.id','asc');


		if($request->urusan){
			$kurusan=$request->urusan;
			$data=$data->whereIn('k.id_urusan',$kurusan)->orWhere('k.id_urusan',null);
		}else{


		}
		
		$shname=['PROGRAM','KEGIATAN'];

		$data=$data->get()->toArray();

		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('template_export/RKPD.xlsx'));

		$urusan=DB::table('master_urusan as u')->leftJoin('master_sub_urusan as su','u.id','=','su.id_urusan')
		->select('u.id as kode_u','u.nama as nama_u','su.id as kode_s','su.nama as nama_s')
		->orderBy('su.id_urusan','asc')
		->whereIn('u.id',$kurusan)
		->get();

		$sheet = $spreadsheet->getSheetByName('MASTER');
		$col=1;
		$col_nama_u=0;
		$id_u='';

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
					$mini.=':$'.static::$abj[$col-1].'$'.($start+1);
					$minil.=':$'.static::$abj[$col].'$'.($start+1);

					$kode_look_up=str_replace('xxx','IF(yyy='.'MASTER!$'.static::$abj[$col-1].'$'.$start_master.','.'VLOOKUP(mmm,'.$minil.',2,FALSE)'.',xxx)',$kode_look_up);

					$sub_look_up=str_replace('xxx','IF(yyy='.'MASTER!$'.static::$abj[$col-1].'$'.$start_master.','.$mini.',xxx)',$sub_look_up);
					$col+=2;
					$mini='MASTER!$'.static::$abj[$col-1].'$'.($start_master+1);
					$minil='MASTER!$'.static::$abj[$col-1].'$'.($start_master);


				}
				$start=$start_master;

				$sheet->setCellValue(static::$abj[$col-1].$start,strtoupper($u->nama_u));
				$sheet->setCellValue(static::$abj[$col_nama_u].'1',strtoupper($u->nama_u));
				$col_nama_u++;

				$sheet->setCellValue(static::$abj[$col].$start,strtoupper($u->kode_u));



				$start++;
				

				$id_u=$u->kode_u;
			}
			
			$sheet->setCellValue(static::$abj[$col-1].$start,strtoupper($u->nama_s));
			$sheet->setCellValue(static::$abj[$col].$start,strtoupper($u->kode_s));

			$start++;

			# code...
		}


		$mini.=':$'.static::$abj[$col-1].'$'.($start+1);
		$minil.=':$'.static::$abj[$col].'$'.($start+1);

		$kode_look_up=str_replace('xxx','IF(yyy='.'MASTER!$'.static::$abj[$col-1].'$'.$start_master.','.'VLOOKUP(mmm,'.$minil.',2,FALSE)'.',xxx)',$kode_look_up);

		$sub_look_up=str_replace('xxx','IF(yyy='.'MASTER!$'.static::$abj[$col-1].'$'.$start_master.','.$mini.',xxx)',$sub_look_up);

		$sub_look_up=str_replace('xxx', 'NULL', $sub_look_up);
		$kode_look_up=str_replace('xxx', 'NULL', $kode_look_up);






				// $spreadsheet->createSheet();
		$sheet = $spreadsheet->getSheetByName('PROGRAM');
		// $sheet->


// =INDIRECT(SUBSTITUTE(E24," ","_"))

		$start=3;


		$sheet->setCellValue(static::$abj[0].$start,"KODEPEMDA");
		$sheet->setCellValue(static::$abj[1].$start,"KODESKPD");
		$sheet->setCellValue(static::$abj[2].$start,"URAI SKPD");
		$sheet->setCellValue(static::$abj[3].$start,"KODE BIDANG");
		$sheet->setCellValue(static::$abj[4].$start,"URAI BIDANG");
		$sheet->setCellValue(static::$abj[5].$start,"ID URUSAN");
		$sheet->setCellValue(static::$abj[6].$start,"ID PROGRAM");
		$sheet->setCellValue(static::$abj[7].$start,"KODE PROGRAM");
		$sheet->setCellValue(static::$abj[8].$start,"ID INDIKATOR / CAPAIAN");
		$sheet->setCellValue(static::$abj[9].$start,"KODE INDIKATOR / CAPAIAN");
		$sheet->setCellValue(static::$abj[10].$start,"KODE SPM INDIKATOR / CAPAIAN");
		$sheet->setCellValue(static::$abj[11].$start,"URAI PROGRAM");
		$sheet->setCellValue(static::$abj[12].$start,"URAI INDIKATOR / CAPAIAN");
		$sheet->setCellValue(static::$abj[13].$start,"TARGET INDIKATOR / CAPAIAN");
		$sheet->setCellValue(static::$abj[14].$start,"SATUAN INDIKATOR / CAPAIAN");
		$sheet->setCellValue(static::$abj[15].$start,"TAGING SPM INDIKATOR / CAPAIAN");
		
		$sheet->getStyle(static::$abj[0].$start.':'.static::$abj[15].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('6587C0');

		$start++;
		// $sheet->freezePane('O'.$start);
		$filter_start=$start-1;


		$id_p='';
		$id_c='';
		$id_k='';
		$id_i='';
		$pindex=0;
		$cindex=0;
		$validation_spm=null;
		$row_program=$start;
		$row_kegiatan=$start;


		foreach ($data as $key => $d) {
			$d=(array)$d;
			$d['kode_spm_c']='';
			$d['kode_spm_i']='';
			$d['jenis_k']='';


			if($id_p!=$d['id_p']){
				$pindex++;
				$sheet->setCellValue(static::$abj[0].$start, $d['kodepemda']);
				$sheet->setCellValue(static::$abj[1].$start, $d['kodeskpd']);
				$sheet->setCellValue(static::$abj[2].$start, $d['uraiskpd']);
				$sheet->setCellValue(static::$abj[3].$start, $d['kodebidang']);
				$sheet->setCellValue(static::$abj[4].$start, $d['uraibidang']);
				$sheet->setCellValue(static::$abj[5].$start, $d['id_urusan']);
				$sheet->setCellValue(static::$abj[6].$start, $d['id_p']);
				$sheet->setCellValue(static::$abj[7].$start, $d['kode_p']);
				$sheet->setCellValue(static::$abj[8].$start, null);
				$sheet->setCellValue(static::$abj[9].$start, null);
				$sheet->setCellValue(static::$abj[10].$start, null);
				$sheet->setCellValue(static::$abj[11].$start, $d['urai_p']);
				$id_p=$d['id_p'];
				$row_program=$start;


				if($pindex%2){
					$sheet->getStyle(static::$abj[0].$start.':'.static::$abj[15].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('6fc1e4');
				}else{
					$sheet->getStyle(static::$abj[0].$start.':'.static::$abj[15].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('a7c6da');
				}

				$start++;

			}

			if($id_c!=$d['id_c']){
				$cindex++;

				


				$sheet->setCellValue(static::$abj[0].$start, $d['kodepemda']);
				$sheet->setCellValue(static::$abj[1].$start, $d['kodeskpd']);
				$sheet->setCellValue(static::$abj[2].$start, $d['uraiskpd']);
				$sheet->setCellValue(static::$abj[3].$start, $d['kodebidang']);
				$sheet->setCellValue(static::$abj[4].$start, $d['uraibidang']);
				$sheet->setCellValue(static::$abj[5].$start, $d['id_urusan']);
				$sheet->setCellValue(static::$abj[6].$start, $d['id_p']);
				$sheet->setCellValue(static::$abj[7].$start, $d['kode_p']);
				$sheet->setCellValue(static::$abj[8].$start, $d['id_c']);
				$sheet->setCellValue(static::$abj[9].$start, $d['kode_c']);
				$sheet->setCellValue(static::$abj[10].$start,'');
				$sheet->setCellValue(static::$abj[11].$start, $d['urai_p']);
				$sheet->setCellValue(static::$abj[12].$start, $d['urai_c']);
				$sheet->setCellValue(static::$abj[13].$start, $d['target_c']);
				$sheet->setCellValue(static::$abj[14].$start, $d['satuan_c']);


				if($cindex%2){
					$sheet->getStyle(static::$abj[0].$start.':'.static::$abj[15].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('aadfba');
				}else{
					$sheet->getStyle(static::$abj[0].$start.':'.static::$abj[15].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d7e8d7');
				}

				if(!$validation_spm){
					$validation_spm = $sheet->getCell(static::$abj[15].$start)
					    ->getDataValidation();
					$validation_spm->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
					$validation_spm->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
					$validation_spm->setAllowBlank(false);
					$validation_spm->setShowInputMessage(true);
					$validation_spm->setShowErrorMessage(true);
					$validation_spm->setShowDropDown(true);
					$validation_spm->setErrorTitle('Input error');
					$validation_spm->setError('Value is not in list.');
					$validation_spm->setPromptTitle('Pick from list');
					$validation_spm->setPrompt('Please pick a value from the drop-down list.');
					$validation_spm->setFormula1('MASTER!$A$2:$A$14');
				}else{
					$sheet->getCell(static::$abj[15].$start)->setDataValidation(clone $validation_spm);
				}

				$sheet->getStyle(static::$abj[15].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffffff');




				
				$id_c=$d['id_c'];
				$start++;

			}


		

		}
		$sheet->setAutoFilter(static::$abj[0].$filter_start.':'.static::$abj[15].($start-1));

		$sheet = $spreadsheet->getSheetByName('KEGIATAN');
		// $sheet->

		$start=3;


		$sheet->setCellValue(static::$abj[0].$start,"KODEPEMDA");
		$sheet->setCellValue(static::$abj[1].$start,"KODESKPD");
		$sheet->setCellValue(static::$abj[2].$start,"URAI SKPD");
		$sheet->setCellValue(static::$abj[3].$start,"KODE BIDANG");
		$sheet->setCellValue(static::$abj[4].$start,"URAI BIDANG");
		$sheet->setCellValue(static::$abj[5].$start,"ID URUSAN");
		$sheet->setCellValue(static::$abj[6].$start,"ID SUB URUSAN");
		$sheet->setCellValue(static::$abj[7].$start,"ID PROGRAM");
		$sheet->setCellValue(static::$abj[8].$start,"ID KEGIATAN");
		$sheet->setCellValue(static::$abj[9].$start,"KODE JENIS KEGIATAN");
		$sheet->setCellValue(static::$abj[10].$start,"KODE KEGIATAN");
		$sheet->setCellValue(static::$abj[11].$start,"ID INDIKATOR / CAPAIAN");
		$sheet->setCellValue(static::$abj[12].$start,"KODE INDIKATOR / CAPAIAN");
		$sheet->setCellValue(static::$abj[13].$start,"KODE SPM INDIKATOR / CAPAIAN");
		$sheet->setCellValue(static::$abj[14].$start,"URUSAN");
		$sheet->setCellValue(static::$abj[15].$start,"SUB URUSAN");
		$sheet->setCellValue(static::$abj[16].$start,"URAI KEGIATAN");
		$sheet->setCellValue(static::$abj[17].$start,"JENIS KEGIATAN");
		$sheet->setCellValue(static::$abj[18].$start,"URAI INDIKATOR / CAPAIAN");
		$sheet->setCellValue(static::$abj[19].$start,"TARGET INDIKATOR / CAPAIAN");
		$sheet->setCellValue(static::$abj[20].$start,"SATUAN INDIKATOR / CAPAIAN");
		$sheet->setCellValue(static::$abj[21].$start,"TAGING SPM INDIKATOR / CAPAIAN");
		
		$sheet->getStyle(static::$abj[0].$start.':'.static::$abj[21].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('6587C0');

		$start++;
		$validation_sub='';
		$validation_ur='';


		foreach ($data as $key => $d) {
			$d=(array)$d;
			$d['kode_spm_c']='';
			$d['kode_spm_i']='';
			$d['jenis_k']='';

			if($id_k!=$d['id_k']){
				$sheet->setCellValue(static::$abj[0].$start, $d['kodepemda']);
				$sheet->setCellValue(static::$abj[1].$start, $d['kodeskpd']);
				$sheet->setCellValue(static::$abj[2].$start, $d['uraiskpd']);
				$sheet->setCellValue(static::$abj[3].$start, $d['kodebidang']);
				$sheet->setCellValue(static::$abj[4].$start, $d['uraibidang']);
				$sheet->setCellValue(static::$abj[5].$start, '='.str_replace('yyy',static::$abj[14].$start, str_replace('mmm',static::$abj[14].$start,$kode_look_up ))); //id urusan
				$sheet->setCellValue(static::$abj[6].$start,'='.str_replace('yyy',static::$abj[14].$start, str_replace('mmm',static::$abj[15].$start,$kode_look_up ))); //id sub urusan
				// dd( );
				$sheet->setCellValue(static::$abj[7].$start, $d['urai_p']);
				$sheet->setCellValue(static::$abj[8].$start, $d['id_k']);
				$sheet->setCellValue(static::$abj[9].$start, null ); // kode jenis kegiatan
				$sheet->setCellValue(static::$abj[10].$start, $d['kode_k'] ); 
				$sheet->setCellValue(static::$abj[14].$start, $d['nama_urusan']);
				$sheet->setCellValue(static::$abj[15].$start, $d['nama_sub_urusan']); 
				$sheet->setCellValue(static::$abj[16].$start, $d['urai_k']);
				$sheet->setCellValue(static::$abj[17].$start, $d['jenis_kegiatan']); 

				$sheet->getStyle(static::$abj[0].$start.':'.static::$abj[21].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('6fc1e4');

				$id_k=$d['id_k'];
				$row_kegiatan=$start;

				if(!$validation_ur){
					$validation_ur = $sheet->getCell(static::$abj[14].$start)
					    ->getDataValidation();
					$validation_ur->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
					$validation_ur->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
					$validation_ur->setAllowBlank(false);
					$validation_ur->setShowInputMessage(true);
					$validation_ur->setShowErrorMessage(true);
					$validation_ur->setShowDropDown(true);
					$validation_ur->setErrorTitle('Input error');
					$validation_ur->setError('Value is not in list.');
					$validation_ur->setPromptTitle('Pick from list');
					$validation_ur->setPrompt('Please pick a value from the drop-down list.');
					$validation_ur->setFormula1('=MASTER!$'.static::$abj[0].'$'.'1'.':'.static::$abj[$col_nama_u].'$'.'1');
				}else{
					$sheet->getCell(static::$abj[14].$start)->setDataValidation(clone $validation_ur);
				}
				$sheet->getStyle(static::$abj[14].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffffff');


				if(!$validation_sub){
					$validation_sub = $sheet->getCell(static::$abj[15].$start)
					    ->getDataValidation();
					$validation_sub->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
					$validation_sub->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
					$validation_sub->setAllowBlank(false);
					$validation_sub->setShowInputMessage(true);
					$validation_sub->setShowErrorMessage(true);
					$validation_sub->setShowDropDown(true);
					$validation_sub->setErrorTitle('Input error');
					$validation_sub->setError('Value is not in list.');
					$validation_sub->setPromptTitle('Pick from list');
					$validation_sub->setPrompt('Please pick a value from the drop-down list.');
					$validation_sub->setFormula1('='.str_replace('yyy',"$".static::$abj[14].$start,$sub_look_up));
					// dd('='.str_replace('yyy',"$".static::$abj[14].$start,$sub_look_up));
				}else{
					$validation_sub = $sheet->getCell(static::$abj[15].$start)
					    ->getDataValidation();
					$validation_sub->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
					$validation_sub->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
					$validation_sub->setAllowBlank(false);
					$validation_sub->setShowInputMessage(true);
					$validation_sub->setShowErrorMessage(true);
					$validation_sub->setShowDropDown(true);
					$validation_sub->setErrorTitle('Input error');
					$validation_sub->setError('Value is not in list.');
					$validation_sub->setPromptTitle('Pick from list');
					$validation_sub->setPrompt('Please pick a value from the drop-down list.');
					$validation_sub->setFormula1('='.str_replace('yyy',"$".static::$abj[14].$start,$sub_look_up));
				}

				$sheet->getStyle(static::$abj[15].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffffff');


				$start++;

			}

			if($id_i!=$d['id_i']){

				$sheet->setCellValue(static::$abj[0].$start, $d['kodepemda']);
				$sheet->setCellValue(static::$abj[1].$start, $d['kodeskpd']);
				$sheet->setCellValue(static::$abj[2].$start, $d['uraiskpd']);
				$sheet->setCellValue(static::$abj[3].$start, $d['kodebidang']);
				$sheet->setCellValue(static::$abj[4].$start, $d['uraibidang']);
				$sheet->setCellValue(static::$abj[5].$start, '='.static::$abj[5].$row_kegiatan); //id urusan
				$sheet->setCellValue(static::$abj[6].$start, '='.static::$abj[6].$row_kegiatan); //id sub urusan
				$sheet->setCellValue(static::$abj[7].$start, $d['urai_p']);
				$sheet->setCellValue(static::$abj[8].$start, $d['id_k']);
				$sheet->setCellValue(static::$abj[9].$start, '='.static::$abj[9].$row_kegiatan ); // kode jenis kegiatan
				$sheet->setCellValue(static::$abj[10].$start, $d['kode_k'] ); 
				$sheet->setCellValue(static::$abj[11].$start, $d['id_i'] );
				$sheet->setCellValue(static::$abj[12].$start, $d['kode_i'] ); 
				$sheet->setCellValue(static::$abj[13].$start, null); // kodespm
				$sheet->setCellValue(static::$abj[14].$start, '='.static::$abj[14].$row_kegiatan);
				$sheet->setCellValue(static::$abj[15].$start, '='.static::$abj[15].$row_kegiatan); 
				$sheet->setCellValue(static::$abj[16].$start, '='.static::$abj[16].$row_kegiatan);
				$sheet->setCellValue(static::$abj[17].$start, '='.static::$abj[17].$row_kegiatan);
				$sheet->setCellValue(static::$abj[18].$start, $d['urai_i'] );
				$sheet->setCellValue(static::$abj[19].$start, $d['target_i'] );
				$sheet->setCellValue(static::$abj[20].$start, $d['satuan_i'] ); 

				$sheet->getStyle(static::$abj[0].$start.':'.static::$abj[21].$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('aadfba');


				

				$id_i=$d['id_i'];
				$start++;

			}


		

		}

		$sheet->setAutoFilter(static::$abj[0].$filter_start.':'.static::$abj[21].($start-1));





		// $sheet->setCellValue('A'.$start, 'kodepemda');
		// $sheet->setCellValue('B'.$start, 'id_p');
		// $sheet->setCellValue('C'.$start, 'id_c');
		// $sheet->setCellValue('D'.$start, 'id_k');
		// $sheet->setCellValue('E'.$start, 'id_i');
		// $sheet->setCellValue('F'.$start, 'kodeskpd');
		// $sheet->setCellValue('G'.$start, 'uraiskpd');
		// $sheet->setCellValue('H'.$start, 'kodebidang');
		// $sheet->setCellValue('I'.$start, 'uraibidang');
		// $sheet->setCellValue('J'.$start, 'id_urusan');
		// $sheet->setCellValue('K'.$start, 'kode_p');
		// $sheet->setCellValue('L'.$start, 'urai_p');
		// $sheet->setCellValue('M'.$start, 'kode_c');
		// $sheet->setCellValue('N'.$start, 'urai_c');
		// $sheet->setCellValue('O'.$start, 'target_c');
		// $sheet->setCellValue('P'.$start, 'satuan_c');
		// $sheet->setCellValue('Q'.$start, 'kode_spm_c');
		// $sheet->setCellValue('R'.$start, 'kode_k');
		// $sheet->setCellValue('S'.$start, 'urai_k');
		// $sheet->setCellValue('T'.$start, 'anggaran_k');
		// $sheet->setCellValue('U'.$start, 'jenis_k');
		// $sheet->setCellValue('V'.$start, 'id_sub_urusan_k');
		// $sheet->setCellValue('W'.$start, 'kode_lintas_urusan_k');			
		// $sheet->setCellValue('X'.$start, 'kode_i');
		// $sheet->setCellValue('Y'.$start, 'urai_i');
		// $sheet->setCellValue('Z'.$start, 'target_i');
		// $sheet->setCellValue('AA'.$start, 'satuan_i');
		// $sheet->setCellValue('AB'.$start, 'kode_spm_i');
		// $start++;


		// foreach ($data as $key =>$d) {
		// 	$d=(array)$d;
		// 	$d['kode_spm_c']='';
		// 	$d['kode_spm_i']='';
		// 	$d['jenis_k']='';

		// 	$sheet->setCellValue('A'.$start, $d['kodepemda']);
		// 	$sheet->setCellValue('B'.$start, $d['id_p']);
		// 	$sheet->setCellValue('C'.$start, $d['id_c']);
		// 	$sheet->setCellValue('D'.$start, $d['id_k']);
		// 	$sheet->setCellValue('E'.$start, $d['id_i']);
		// 	$sheet->setCellValue('F'.$start, $d['kodeskpd']);
		// 	$sheet->setCellValue('G'.$start, $d['uraiskpd']);
		// 	$sheet->setCellValue('H'.$start, $d['kodebidang']);
		// 	$sheet->setCellValue('I'.$start, $d['uraibidang']);
		// 	$sheet->setCellValue('J'.$start, $d['id_urusan']);
		// 	$sheet->setCellValue('K'.$start, $d['kode_p']);
		// 	$sheet->setCellValue('L'.$start, $d['urai_p']);
		// 	$sheet->setCellValue('M'.$start, $d['kode_c']);
		// 	$sheet->setCellValue('N'.$start, $d['urai_c']);
		// 	$sheet->setCellValue('O'.$start, $d['target_c']);
		// 	$sheet->setCellValue('P'.$start, $d['satuan_c']);
		// 	$sheet->setCellValue('Q'.$start, $d['kode_spm_c']);
		// 	$sheet->setCellValue('R'.$start, $d['kode_k']);
		// 	$sheet->setCellValue('S'.$start, $d['urai_k']);
		// 	$sheet->setCellValue('T'.$start, $d['anggaran_k']);
		// 	$sheet->setCellValue('U'.$start, $d['jenis_k']);
		// 	$sheet->setCellValue('V'.$start, $d['id_sub_urusan_k']);
		// 	$sheet->setCellValue('W'.$start, $d['kode_lintas_urusan_k']);			
		// 	$sheet->setCellValue('X'.$start, $d['kode_i']);
		// 	$sheet->setCellValue('Y'.$start, $d['urai_i']);
		// 	$sheet->setCellValue('Z'.$start, $d['target_i']);
		// 	$sheet->setCellValue('AA'.$start, $d['satuan_i']);
		// 	$sheet->setCellValue('AB'.$start, $d['kode_spm_i']);

		// 	$jenis='';

		// 	$start++;

		// 	// dd($d);


		// }

		$writer = new Xlsx($spreadsheet);

		 $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="RKPD-'.$kodepemda.'-'.$tahun.'.xlsx"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;

		// $writer->save('hello world.xlsx');




	}

	public function upload(){


	}

}
