<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
class BPPSPAM extends Controller
{
    //


    public function index(Request $request){

    	if($request->tahun){
    		$tahun=$request->tahun;
    	}else{
    		$tahun=HP::fokus_tahun();
    	}

        $data_kategori=[
            '0'=>[
                'jumlah_pdam'=>0
            ],
            '1'=>[
                'jumlah_pdam'=>0
            ],
            '2'=>[
                'jumlah_pdam'=>0
            ],
            '3'=>[
                'jumlah_pdam'=>0
            ],
            '4'=>[
                'jumlah_pdam'=>0
            ],
            '5'=>[
                'jumlah_pdam'=>0
            ],
            
        ];

    	$data=DB::table('daerah_nuwas as d')
        ->select(
            'k.kode_daerah',
            DB::raw("(select nama from public.master_daerah as dn where dn.id=max(d.kode_daerah)) as nama_daerah"),
            DB::raw("(select nama from public.master_daerah as dn where dn.id=left(max(d.kode_daerah),2)) as nama_provinsi"),
            DB::raw("max(pdam.nama_pdam) as nama_pdam"),
            DB::raw("max(pdam.id) as id"),
            DB::raw("max(k.id_penilaian) as id_penilaian"),
            DB::raw("max(d.tahun) as target_nuwas"),
            DB::raw("max(d.jenis_bantuan) as jenis_bantuan"),
            DB::raw("max(k.tahun) as periode_laporan"),
            DB::raw("max(k.kategori_pdam) as kategori_pdam_kode"),
            DB::raw("max(k.kategori_bppspam) as kategori_pdam_bppspam"),
            DB::raw("max(k.keterangan) as keterangan"),
            DB::raw("max(k.pemenuhan_fcr) as nilai_fcr")
        )
    	->leftJoin('bppspam.view_bppspam_penilaian_kategori as k','k.kode_daerah','d.kode_daerah')
        ->leftJoin('public.pdam as pdam','pdam.kode_daerah','=','k.kode_daerah')
        ->groupby('k.kode_daerah')
        ->orderBy('k.tahun','desc')
        ->where('k.tahun','<',($tahun-1))->get();


        foreach ($data as $key => $d) {

            $data_kategori[($d->kategori_pdam_kode?$d->kategori_pdam_kode:0)]['jumlah_pdam']+=1;

        }

        return view('front.bppspam.index')->with([
            'tahun'=>$tahun,
            'data'=>$data,
            'pdam_rekap'=>$data_kategori
        ]);


    }


    public function detail($kode_daerah,Request $request){
        $pdam=DB::table('pdam')->where('kode_daerah',$kode_daerah)->first();
        if($request->tahun){
            $tahun=$request->tahun;
        }else{
            $tahun=HP::fokus_tahun();
        }

        $data_profile=DB::table('daerah_nuwas as d')
        ->select(
            'k.kode_daerah',
            DB::raw("(select nama from public.master_daerah as dn where dn.id=max(d.kode_daerah)) as nama_daerah"),
            DB::raw("(select nama from public.master_daerah as dn where dn.id=left(max(d.kode_daerah),2)) as nama_provinsi"),
            DB::raw("max(pdam.nama_pdam) as nama_pdam"),
            DB::raw("max(pdam.id) as id"),
            DB::raw("max(k.id_penilaian) as id_penilaian"),
            DB::raw("max(d.tahun) as target_nuwas"),
            DB::raw("max(d.jenis_bantuan) as jenis_bantuan"),
            DB::raw("max(k.tahun) as periode_laporan"),
            DB::raw("max(k.kategori_pdam) as kategori_pdam_kode"),
            DB::raw("max(k.kategori_bppspam) as kategori_pdam_bppspam"),
            DB::raw("max(k.keterangan) as keterangan"),
            DB::raw("max(k.pemenuhan_fcr) as nilai_fcr")
        )
        ->leftJoin('bppspam.view_bppspam_penilaian_kategori as k','k.kode_daerah','d.kode_daerah')
        ->leftJoin('public.pdam as pdam','pdam.kode_daerah','=','k.kode_daerah')
        ->groupby('k.kode_daerah')
        ->groupby('k.tahun')
        ->orderBy('k.tahun','desc')
        ->where('d.kode_daerah',$kode_daerah)
        ->where('k.tahun','<',($tahun-1))->first();

        $pdam_else=[];
        if($data_profile){

            $data_penilaian=DB::table('bppspam.bppspam_data_penilaian')
            ->where('kode_daerah',$kode_daerah)
            ->where('tahun',$data_profile->periode_laporan)
            ->first();

            $data_nilai=DB::table('bppspam.bppspam_data_nilai')
            ->where('kode_daerah',$kode_daerah)
            ->where('tahun',$data_profile->periode_laporan)
            ->first();

            $data_keterangan=DB::table('bppspam.bppspam_data_keterangan')
            ->where('kode_daerah',$kode_daerah)
            ->where('tahun',$data_profile->periode_laporan)
            ->first();

            $data_return=[];

            if($data_penilaian){
                $data_return['kalkulasi']=$data_penilaian;
            }

            if($data_nilai){
                $data_return['nilai']=$data_nilai;
            }

            if($data_keterangan){
                $data_return['detail']=$data_keterangan;
            }


            return view('front.bppspam.detail')->with([
                'profile'=>$data_profile,
                'tahun'=>$tahun,
                'pdam'=>$pdam,
                'data'=>$data_return,
                'pdam_else'=>$pdam_else
        
             ]);

        }


    }
}
