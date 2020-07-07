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

	static $con='myfinal';

    //

	public function index(){


	}

	public function download($kodepemda,Request $request){
		$tahun=HP::fokus_tahun();

		$data=DB::connection(static::$con)->table('master_'.$tahun.'_program as p')

		->leftJoin('master_'.$tahun.'_program_capaian as c','p.id','=','c.id_program')
		->leftJoin('master_'.$tahun.'_kegiatan as k','p.id','=','k.id_program')
		->leftJoin('master_'.$tahun.'_kegiatan_indikator as i','k.id','=','i.id_kegiatan')
		->where([
			['p.kodepemda','=',$kodepemda],
			['p.status','=',5],
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

			DB::raw("'' as id_sub_urusan_k"),
			DB::raw("'' as kode_lintas_urusan_k"),
			DB::raw('k.pagu as anggaran_k'),
			DB::raw('i.pagu as anggaran_i')

		)
		->orderBy('p.id_urusan','desc')
		->orderBy('p.id','asc')
		->orderBy('k.id','asc')
		->orderBy('c.id','asc')
		->orderBy('i.id','asc');


		if($request->urusan){
			$data=$data->where('p.id_urusan',$request->urusan);
		}
		
		$shname=['PROGRAM','KEGIATAN'];

		$data=$data->get()->toArray();

		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('template_export/RKPD.xlsx'));
				// $spreadsheet->createSheet();
		$sheet = $spreadsheet->getSheetByName('PROGRAM');
		// $sheet->


		$start=3;


		$sheet->setCellValue('A'.$start,"KODEPEMDA");
		$sheet->setCellValue('B'.$start,"KODESKPD");
		$sheet->setCellValue('C'.$start,"URAI SKPD");
		$sheet->setCellValue('D'.$start,"KODE BIDANG");
		$sheet->setCellValue('E'.$start,"URAI BIDANG");
		$sheet->setCellValue('F'.$start,"ID URUSAN");
		$sheet->setCellValue('G'.$start,"ID PROGRAM");
		$sheet->setCellValue('H'.$start,"KODE PROGRAM");
		$sheet->setCellValue('I'.$start,"URAI PROGRAM");
		$sheet->setCellValue('J'.$start,"ID INDIKATOR / CAPAIAN");
		$sheet->setCellValue('K'.$start,"KODE INDIKATOR / CAPAIAN");
		$sheet->setCellValue('L'.$start,"URAI INDIKATOR / CAPAIAN");
		$sheet->setCellValue('M'.$start,"TARGET INDIKATOR / CAPAIAN");
		$sheet->setCellValue('N'.$start,"SATUAN INDIKATOR / CAPAIAN");
		$sheet->setCellValue('O'.$start,"TAGING SPM INDIKATOR / CAPAIAN");
		
		$sheet->getStyle('A'.$start.':O'.$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('6587C0');

		$start++;
		$sheet->freezePane('O'.$start);
		$filter_start=$start-1;


		$id_p='';
		$id_c='';
		$id_k='';
		$id_i='';
		$pindex=0;
		$cindex=0;

		foreach ($data as $key => $d) {
			$d=(array)$d;
			$d['kode_spm_c']='';
			$d['kode_spm_i']='';
			$d['jenis_k']='';

			if($id_p!=$d['id_p']){
				$pindex++;
				$sheet->setCellValue('A'.$start, $d['kodepemda']);
				$sheet->setCellValue('B'.$start, $d['kodeskpd']);
				$sheet->setCellValue('C'.$start, $d['uraiskpd']);
				$sheet->setCellValue('D'.$start, $d['kodebidang']);
				$sheet->setCellValue('E'.$start, $d['uraibidang']);
				$sheet->setCellValue('F'.$start, $d['id_urusan']);
				$sheet->setCellValue('G'.$start, $d['id_p']);
				$sheet->setCellValue('H'.$start, $d['kode_p']);
				$sheet->setCellValue('I'.$start, $d['urai_p']);
				$id_p=$d['id_p'];

				if($pindex%2){
					$sheet->getStyle('A'.$start.':O'.$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('6fc1e4');
				}else{
					$sheet->getStyle('A'.$start.':O'.$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('a7c6da');
				}

				$start++;

			}

			if($id_c!=$d['id_c']){
				$cindex++;


				$sheet->setCellValue('A'.$start, $d['kodepemda']);
				$sheet->setCellValue('B'.$start, $d['kodeskpd']);
				$sheet->setCellValue('C'.$start, $d['uraiskpd']);
				$sheet->setCellValue('D'.$start, $d['kodebidang']);
				$sheet->setCellValue('E'.$start, $d['uraibidang']);
				$sheet->setCellValue('F'.$start, $d['id_urusan']);
				$sheet->setCellValue('G'.$start, $d['id_p']);
				$sheet->setCellValue('H'.$start, $d['kode_p']);
				$sheet->setCellValue('I'.$start, $d['urai_p']);
				$sheet->setCellValue('J'.$start, $d['id_c']);
				$sheet->setCellValue('K'.$start, $d['kode_c']);
				$sheet->setCellValue('L'.$start, $d['urai_c']);
				$sheet->setCellValue('M'.$start, $d['target_c']);
				$sheet->setCellValue('N'.$start, $d['satuan_c']);
				$sheet->setCellValue('O'.$start, $d['kode_spm_c']);

				if($cindex%2){
					$sheet->getStyle('A'.$start.':O'.$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('aadfba');
				}else{
					$sheet->getStyle('A'.$start.':O'.$start)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d7e8d7');
				}

				
				$id_c=$d['id_c'];
				$start++;

			}


		

		}
		$sheet->setAutoFilter('A'.$filter_start.':O'.($start-1));

		$sheet = $spreadsheet->getSheetByName('KEGIATAN');
		// $sheet->

		$start=3;

		foreach ($data as $key => $d) {
			$d=(array)$d;
			$d['kode_spm_c']='';
			$d['kode_spm_i']='';
			$d['jenis_k']='';

			if($id_k!=$d['id_k']){
				$sheet->setCellValue('A'.$start, $d['kodepemda']);
				$sheet->setCellValue('B'.$start, $d['kodeskpd']);
				$sheet->setCellValue('C'.$start, $d['uraiskpd']);
				$sheet->setCellValue('D'.$start, $d['kodebidang']);
				$sheet->setCellValue('E'.$start, $d['uraibidang']);
				$sheet->setCellValue('F'.$start, $d['id_urusan']);
				$sheet->setCellValue('G'.$start, $d['id_k']);
				$sheet->setCellValue('H'.$start, $d['kode_k']);
				$sheet->setCellValue('I'.$start, $d['urai_k']);
				$id_k=$d['id_k'];
				$start++;

			}

			if($id_i!=$d['id_i']){

				$sheet->setCellValue('A'.$start, $d['kodepemda']);
				$sheet->setCellValue('B'.$start, $d['kodeskpd']);
				$sheet->setCellValue('C'.$start, $d['uraiskpd']);
				$sheet->setCellValue('D'.$start, $d['kodebidang']);
				$sheet->setCellValue('E'.$start, $d['uraibidang']);
				$sheet->setCellValue('F'.$start, $d['id_urusan']);
				$sheet->setCellValue('G'.$start, $d['id_k']);
				$sheet->setCellValue('H'.$start, $d['kode_k']);
				$sheet->setCellValue('I'.$start, $d['urai_k']);
				$sheet->setCellValue('J'.$start, $d['id_i']);
				$sheet->setCellValue('K'.$start, $d['kode_i']);
				$sheet->setCellValue('L'.$start, $d['urai_i']);
				$sheet->setCellValue('M'.$start, $d['target_i']);
				$sheet->setCellValue('N'.$start, $d['satuan_i']);
				$sheet->setCellValue('O'.$start, $d['kode_spm_i']);
				
				$id_i=$d['id_i'];
				$start++;

			}


		

		}





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
