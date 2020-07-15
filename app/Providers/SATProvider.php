<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SATProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function getdata($kode_daerah=null,$tahun=null){
        $bindding='';
        $limit='';

        if($kode_daerah){
            if($bindding!=''){
                $bindding.=' and ';
            }else{
                $bindding.=' ';
            }
            $bindding=" li.kode_daerah = '".$kode_daerah."'";
        }

        if($tahun){

            if($bindding!=''){
                $bindding.=' and ';
            }else{
                $bindding.=' ';
            }

            $bindding=" li.periode_laporan <= '".Carbon::parse('1-12-'$tahun)->endOfMoth()."'";

        }

        if($kode_daerah){
            $limit=' limit 1';
        }


        $sql="
            select 
            max(l1.kode_daerah) as kode_daerah,
            max(m.nama_pdam) as nama_pdam ,
            max(m.alamat) as alamat,
            max(m.open_hours) as open_hours,
            max(m.no_telpon) as no_telpon,
            max(m.website ) as website,
            max(m.url_direct) as url_direct,
            max(m.url_image) as url_image,
            max(l1.id) as id_1,
            max(l1.periode_laporan) as periode_laporan_1,
            max(l2.periode_laporan) as periode_laporan_2,
            max(l1.sat_nilai_kinerja_ttl_dr_bppspam_nilai) kinerja_1,
            max(l1.sat_nilai_aspek_keuangan_dr_bppspam_nilai) keuangan_1,
            max(l1.sat_nilai_aspek_operasional_dr_bppspam_nilai) as oprasional_1, 
            max(l1.sat_nilai_aspek_pel_dr_bppspam_nilai) as pelayanan_1,
            max(l1.sat_jumlah_pelanggan_ttl_nilai ) as jum_pelanggan_1,
            max(l1.sat_jumlah_sam_rumah_tangga_nilai ) as sr_1 ,
            max(l1.kategori_pdam_kode ) as kategori_pdam_kode_1,
            max(l1.kategori_pdam ) as kategori_pdam_1,
            max(l2.id) as id_2,
            max(l2.sat_nilai_kinerja_ttl_dr_bppspam_nilai) kinerja_2,
            max(l2.sat_nilai_aspek_keuangan_dr_bppspam_nilai) keuangan_2,
            max(l2.sat_nilai_aspek_operasional_dr_bppspam_nilai) as oprasional_2, 
            max(l2.sat_nilai_aspek_pel_dr_bppspam_nilai) as pelayanan_2,
            max(l2.sat_jumlah_pelanggan_ttl_nilai ) as jum_pelanggan_2,
            max(l2.sat_jumlah_sam_rumah_tangga_nilai ) as sr_2 ,
            max(l2.kategori_pdam_kode ) as kategori_pdam_kode_2
            from public.audit_sat as l1
            left join (select * from public.audit_sat order by updated_input_at desc) as l2 on l2.kode_daerah = l1.kode_daerah and l2.periode_laporan < l1.periode_laporan 
            left join pdam as m on m.kode_daerah = l1.kode_daerah 
            ".
                $bindding
            ."
            group by l1.kode_daerah 
            order by max(l1.periode_laporan) desc, 
            max(l1.updated_input_at) desc  ".$limit;

            


    }
}
