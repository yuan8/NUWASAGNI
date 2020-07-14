<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ViewPdamSat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if(!Schema::hasTable('view_audit_pdam')){ 
        //

            DB::statement("CREATE OR REPLACE VIEW public.view_audit_pdam
                AS SELECT max(l1.kode_daerah::text) AS kode_daerah,
                    max(l1.id::text) AS id_1,
                    max(l1.periode_laporan) AS periode_laporan_1,
                    max(l2.periode_laporan) AS periode_laporan_2,
                    max(l1.sat_nilai_kinerja_ttl_dr_bppspam_nilai) AS kinerja_1,
                    max(l1.sat_nilai_aspek_keuangan_dr_bppspam_nilai) AS keuangan_1,
                    max(l1.sat_nilai_aspek_operasional_dr_bppspam_nilai) AS oprasional_1,
                    max(l1.sat_nilai_aspek_pel_dr_bppspam_nilai) AS pelayanan_1,
                    max(l1.sat_jumlah_pelanggan_ttl_nilai) AS jum_pelanggan_1,
                    max(l1.sat_jumlah_sam_rumah_tangga_nilai) AS sr_1,
                    max(l1.kategori_pdam_kode) AS kategori_pdam_kode_1,
                    max(l1.kategori_pdam::text) AS kategori_pdam_1,
                    max(l2.id::text) AS id_2,
                    max(l2.sat_nilai_kinerja_ttl_dr_bppspam_nilai) AS kinerja_2,
                    max(l2.sat_nilai_aspek_keuangan_dr_bppspam_nilai) AS keuangan_2,
                    max(l2.sat_nilai_aspek_operasional_dr_bppspam_nilai) AS oprasional_2,
                    max(l2.sat_nilai_aspek_pel_dr_bppspam_nilai) AS pelayanan_2,
                    max(l2.sat_jumlah_pelanggan_ttl_nilai) AS jum_pelanggan_2,
                    max(l2.sat_jumlah_sam_rumah_tangga_nilai) AS sr_2,
                    max(l2.kategori_pdam_kode) AS kategori_pdam_kode_2
                   FROM audit_sat l1
                     LEFT JOIN ( SELECT audit_sat.* FROM audit_sat
                          ORDER BY audit_sat.updated_input_at DESC) l2 ON l2.kode_daerah::text = l1.kode_daerah::text AND l2.periode_laporan < l1.periode_laporan
                  GROUP BY l1.kode_daerah
                  ORDER BY (max(l1.periode_laporan)) DESC, (max(l1.updated_input_at)) DESC");

            }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //

        Schema::dropIfExists('view_audit_pdam');

    }
}
