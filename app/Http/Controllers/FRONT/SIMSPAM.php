<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
class SIMSPAM extends Controller
{
    //

    public function index($id){

        $tahun=Hp::fokus_tahun();
         $pdam=DB::table('pdam as d')
                ->leftJoin('daerah_nuwas as n',function($q) use ($tahun){
                return $q->on('n.kode_daerah','=','d.kode_daerah')
                ->on("n.tahun",'=',DB::raw($tahun));
                    
             })
            ->where('d.kode_daerah',$id)
            ->select(
                'd.*',
                DB::raw("(select concat(c.nama,
                (case when length(c.id)>3 then (select concat(' / ',d5.nama) from master_daerah as d5 where d5.id = left(c.id,2) ) end  )) from master_daerah as c where c.id=d.kode_daerah) as nama_daerah"),
                DB::raw("(case when n.id is not null then true else false end) as daerah_nuwas"),
                DB::raw("REPLACE(n.jenis_bantuan,'@','') as  jenis_bantuan")
            )
               
            ->first();

            $prof_pdam=[];
            

            if(($pdam)and(isset($pdam->id_laporan_terahir_2))){
                $old_pdam=DB::table('audit_sat')
                ->where('id',$pdam->id_laporan_terahir_2)
                ->first();
                 $last_pdam=DB::table('audit_sat')
                ->where('id',$pdam->id_laporan_terahir)
                ->first();

                $prof_pdam['kategori_pdam_past']=$old_pdam->kategori_pdam;
                $prof_pdam['kategori_pdam_present']=$last_pdam->kategori_pdam;
                $prof_pdam['kategori_pdam_trf']=HP::banil($old_pdam->kategori_pdam_kode,$last_pdam->kategori_pdam_kode);

                $prof_pdam['periode_laporan']=$old_pdam->periode_laporan;
                $prof_pdam['updated_input_at']=$old_pdam->updated_input_at;
                $prof_pdam['kinerja_trf']=HP::banil($old_pdam->sat_nilai_kinerja_ttl_dr_bppspam_nilai,$last_pdam->sat_nilai_kinerja_ttl_dr_bppspam_nilai);
                $prof_pdam['keuangan_trf']=HP::banil($old_pdam->sat_nilai_aspek_keuangan_dr_bppspam_nilai,$last_pdam->sat_nilai_aspek_keuangan_dr_bppspam_nilai);
                $prof_pdam['pelayanan_trf']=HP::banil($old_pdam->sat_nilai_aspek_pel_dr_bppspam_nilai,$last_pdam->sat_nilai_aspek_pel_dr_bppspam_nilai);
                $prof_pdam['oprasional_trf']=HP::banil($old_pdam->sat_nilai_aspek_operasional_dr_bppspam_nilai,$last_pdam->sat_nilai_aspek_operasional_dr_bppspam_nilai);
                $prof_pdam['sdm_trf']=HP::banil($old_pdam->sat_nilai_aspek_sdm_dr_bppspam_nilai,$last_pdam->sat_nilai_aspek_sdm_dr_bppspam_nilai);
                $prof_pdam['pelangan_past']=$last_pdam->sat_jumlah_pelanggan_ttl_nilai;
                $prof_pdam['pelangan_present']=$old_pdam->sat_jumlah_pelanggan_ttl_nilai;
                $prof_pdam['sr_past']=$last_pdam->sat_jumlah_sam_rumah_tangga_nilai;
                $prof_pdam['sr_present']=$old_pdam->sat_jumlah_sam_rumah_tangga_nilai;

                $prof_pdam['pertumbuhan_pelangan']=((($last_pdam->sat_jumlah_pelanggan_ttl_nilai-$old_pdam->sat_jumlah_pelanggan_ttl_nilai)/$last_pdam->sat_jumlah_pelanggan_ttl_nilai)*100);
                $prof_pdam['pertumbuhan_sambungan_rumah']=((($last_pdam->sat_jumlah_sam_rumah_tangga_nilai-$old_pdam->sat_jumlah_sam_rumah_tangga_nilai)/$last_pdam->sat_jumlah_sam_rumah_tangga_nilai)*100);

            }else if($pdam){
                 $last_pdam=DB::table('audit_sat')
                ->where('id',$pdam->id_laporan_terahir)
                ->first();
                $prof_pdam['kinerja_trf']=0;
                $prof_pdam['kategori_pdam_past']=$last_pdam->kategori_pdam;
                $prof_pdam['kategori_pdam_present']=$last_pdam->kategori_pdam;
                $prof_pdam['kategori_pdam_trf']=0;

                $prof_pdam['keuangan_trf']=0;
                $prof_pdam['pelayanan_trf']=0;
                $prof_pdam['oprasional_trf']=0;
                $prof_pdam['sdm_trf']=0;
                $prof_pdam['pelangan_past']=$last_pdam->sat_jumlah_pelanggan_ttl_nilai;
                $prof_pdam['pelangan_present']=$last_pdam->sat_jumlah_pelanggan_ttl_nilai;
                $prof_pdam['sr_past']=$last_pdam->sat_jumlah_sam_rumah_tangga_nilai;
                $prof_pdam['sr_present']=$last_pdam->sat_jumlah_sam_rumah_tangga_nilai;
                $prof_pdam['pertumbuhan_pelangan']=0;
                $prof_pdam['pertumbuhan_sambungan_rumah']=0;
                $prof_pdam['periode_laporan']=null;
                $prof_pdam['updated_input_at']=null;


        }

    	
    	$data=DB::connection('simspam')->table('perpipaan')
    	->where('kode_daerah',$id)
    	->orderBy('updated_at')
    	->get();

        $riwayat_sr=[];

        if($data){
            foreach($data as $d){
                if($riwayat_sr==[]){
                    $riwayat_sr=json_decode($d->riwayat_sr,true);
                    foreach ($riwayat_sr as $key => $value) {
                        $riwayat_sr[$key]=(int)$value;
                    }
                }else{
                    foreach (json_decode($d->riwayat_sr,true) as $key => $sr) {
                       $riwayat_sr[$key]+=(int)$sr;

                    }
                }
            }

        }

        $riwayat_sr=[
            'kategori'=>array_keys($riwayat_sr),
            'data'=>array_values($riwayat_sr)
        ];


    	return view('front.pdam.simspam.index')->with(

    		[   'data'=>$data,
        		'pdam'=>$pdam,
                'trafik'=>$prof_pdam,
                'riwayat_sr'=>$riwayat_sr
    		]

    	);
    }
}
