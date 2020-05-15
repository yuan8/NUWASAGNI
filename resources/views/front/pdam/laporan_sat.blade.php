@extends('adminlte::page')


@section('content_header')
	@if($pdam->daerah_nuwas)
	<div class="row  text-center">
		<div class="col-md-12 bg bg-yellow">
			<b class="text-dark">MERUPAKAN DAERAH NUWAS {{HP::fokus_tahun()}} DENGAN TIPE BANTUAN ({{$pdam->jenis_bantuan}})</b>
		</div>
	</div>
	@endif
    <div class="text-center text-uppercase">
    	<h3 class="text-center">KONDISI {{strtoupper($pdam->nama_pdam)}} ({{strtoupper($pdam->kategori_pdam)}})</h3>
    <small class="text-white text-center">PERIODE {{Carbon\Carbon::parse($pdam->periode_laporan)->format('F Y')}}</small>
    </div>
@stop

@section('content')
<style type="text/css">
	table thead th{
		vertical-align: top!important;
	}
	table tr{
		font-size: 10px;
	}
	.nav-sub li a,.nav-sub a{
		color: #222!important;
		font-size: 10px;
		font-weight: bold;
	}
	.nav-sub{
		border-radius: 0px;
	}
</style>
<?php
$open_hours=[];
if($pdam->open_hours!=null){
		$open_hours=json_decode($pdam->open_hours,true);
}

?>

@if($trafik)
<div class="row no-gutter text-dark">
	<div class="col-md-12">
		<div class="box box-primary" style="margin-bottom: 0px;">
			<div class="box-header with-border">
				<p class="text-center"><b>TRAFIK PDAM DARI LAPORAN SEBELUMNYA</b></p>
			</div>
			<div class="box-body table-responsive" >
				<table class="table-bordered table">
			 	<thead>
			 	<tr>
			 		<th colspan="2" class="text-center">LAPORAN SEBELUMNYA</th>
			 		<th colspan="8" class="text-center">DATA TERAHIR</th>
			 	</tr>
				<tr>
					<th>
						PERIODE LAP SEBELUMYA
					</th>
					<th>
						TANGGAL UPDATE LAP SEBELUNYA
					</th>
					<th>
						KETEGORI PDAM
					</th>
					<th>
						PENILAIAN TOTAL KINERJA
					</th>
					<th>
						PENILAIAN KEUANGAN
					</th>
					<th>
						PENILAIAN OPRASIOANAL
					</th>
					<th>
						PENILAIAN PELAYAAN
					</th>
					<th>
						PENILAIAN SDM
					</th>
					<th>
						PERTUMBUHAN PELANGGAN
					</th>
					<th>
						PERTUMBUHAN SR
					</th>
				</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							@if($trafik['periode_laporan'])
								{{Carbon\Carbon::parse($trafik['periode_laporan'])->format('F Y')}}
							@endif
						</td>
						<td>
							@if($trafik['updated_input_at'])
							{{Carbon\Carbon::parse($trafik['updated_input_at'])->format('d F Y')}}
							@endif
							
						</td>
						<td>
							@if($trafik['kategori_pdam_trf']==1)
								<span class="text-success"><i class="fa fa-arrow-up"></i> MENINGKAT</span>

							@elseif($trafik['kategori_pdam_trf']==0)
								<span class="text-warning"><i class="fa fa-minus"></i> STABIL</span>

							@else
								<span class="text-danger"><i class="fa fa-arrow-down"></i> MENURUN</span>

							@endif

							<p>{{$trafik['kategori_pdam_past']}} <i class=" text-success fa fa-arrow-right"></i> {{$trafik['kategori_pdam_present']}}</p>
						</td>
						<td>
							@if($trafik['kinerja_trf']==1)
								<span class="text-success"><i class="fa fa-arrow-up"></i> MENINGKAT</span>

							@elseif($trafik['kinerja_trf']==0)
								<span class="text-warning"><i class="fa fa-minus"></i> STABIL</span>

							@else
								<span class="text-danger"><i class="fa fa-arrow-down"></i> MENURUN</span>

							@endif
						</td>
						<td>
							@if($trafik['keuangan_trf']==1)
								<span class="text-success"><i class="fa fa-arrow-up"></i> MENINGKAT</span>

							@elseif($trafik['keuangan_trf']==0)
								<span class="text-warning"><i class="fa fa-minus"></i> STABIL</span>

							@else
								<span class="text-danger"><i class="fa fa-arrow-down"></i> MENURUN</span>

							@endif
						</td>
						<td>
							@if($trafik['oprasional_trf']==1)
								<span class="text-success"><i class="fa fa-arrow-up"></i> MENINGKAT</span>

							@elseif($trafik['oprasional_trf']==0)
								<span class="text-warning"><i class="fa fa-minus"></i> STABIL</span>

							@else
								<span class="text-danger"><i class="fa fa-arrow-down"></i> MENURUN</span>

							@endif
						</td>
						<td>
							@if($trafik['pelayanan_trf']==1)
								<span class="text-success"><i class="fa fa-arrow-up"></i> MENINGKAT</span>

							@elseif($trafik['pelayanan_trf']==0)
								<span class="text-warning"><i class="fa fa-minus"></i> STABIL</span>

							@else
								<span class="text-danger"><i class="fa fa-arrow-down"></i> MENURUN</span>

							@endif
						</td>
						<td>
							@if($trafik['sdm_trf']==1)
								<span class="text-success"><i class="fa fa-arrow-up"></i> MENINGKAT</span>

							@elseif($trafik['sdm_trf']==0)
								<span class="text-warning"><i class="fa fa-minus"></i> STABIL</span>

							@else
								<span class="text-danger"><i class="fa fa-arrow-down"></i> MENURUN</span>

							@endif
						</td>
						<td>
							<b>{{number_format($trafik['pertumbuhan_pelangan'],2,'.',',')}}%</b>
							<p>{{number_format($trafik['pelangan_past'],0,'.',',') }} <i class=" text-success fa fa-arrow-right"></i> {{number_format($trafik['pelangan_present'],0,'.','.')}}</p>

						</td>
						<td>
							<b>{{number_format($trafik['pertumbuhan_sambungan_rumah'],2,'.',',')}}%</b>
							<p>{{number_format($trafik['sr_past'],0,'.',',') }} <i class=" text-success fa fa-arrow-right"></i> {{number_format($trafik['sr_present'],0,'.','.')}}</p>
						</td>
					</tr>
				</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-md-12">
		<nav class="navbar navbar-default bg-yellow nav-sub">
			  <div class="container-fluid">
			    <div class="navbar-header">
			      <a class="navbar-brand" href="#">{{strtoupper($pdam->nama_daerah)}}</a>
			    </div>
			    <ul class="nav navbar-nav">
			      <li class="active" ><a href="{{route('p.laporan_sat',['íd'=>$pdam->id_laporan_terahir])}}">PROFILE PDAM</a></li>
			      <li class=""><a href="{{route('p.simspam.perpipaan',['id'=>$pdam->kode_daerah])}}">JARINGAN PERPIPAAN</a></li>
			      <li><a href="#">JARINGAN NON PERPIPAAN</a></li>
		
			    </ul>
			  </div>
	</nav>
	</div>
</div>

@endif
	@if($pdam->id_laporan_terahir==$data->id)
	<div class="row bg bg-primary text-center" >
		<b>DOKUMEN INI DIGUNAKAN SEBAGAI LAPORAN TERAHIR</b>
	</div>
	@endif
	<div class="row text-dark">
		@foreach($open_hours as $d)
		<div style="width:calc(100%/7)!important;" class=" col-md-12 {{HP::hari_ini($d['key'])?'bg-yellow text-dark':'bg-default'}}" style="border-right:1px solid #222">
			<b>{{$d['key']}} : {{$d['value']}}</b>
		</div>
		@endforeach
	</div>

	<div class="row no-gutter bg-default-g text-dark">
		<div class="col-md-3">
			<div class="box box-primary">
				<div class="box-body">
					@if($pdam->url_image)
					<img src="{{$pdam->url_image}}" class="img-responsive">
					@endif
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Alamat</th>
								<td>{{$pdam->alamat}}
									@if($pdam->url_direct)
									<br>

									<a href="{{$pdam->url_direct}}" ><i class="fa fa-map"></i> Direct</a>
									@endif
								</td>

								
							</tr>
							<tr>
								<th>No Telp</th>
								<td>{{$pdam->no_telpon}}</td>
							</tr>
							<tr>
								<th>Website</th>
								<td>
									@if($pdam->website)
										<a href="{{$pdam->website}}" target="_blank">{{$pdam->website}}</a>
									@else
										-
									@endif

								</td>
							</tr>
						</thead>
					</table>

					@if(count($pdam_else)>0)
						<h5 class="text-center"><b>PDAM LAINYA DI DAERAH INI</b></h5>
						<hr>
						@foreach($pdam_else as $e)
						<a href="{{route('p.laporan_sat',['id'=>$e->id_laporan_terahir])}}" class="btn btn-warning col-md-12">{{strtoupper($e->nama_pdam)}} <small>({{$e->kategori_pdam}})</small>
							<br>{{Carbon\Carbon::parse($e->periode_laporan)->format('F Y')}}
							<br>
							<small>INPUT - {{Carbon\Carbon::parse($e->updated_input_at)->format('d F Y')}}</small>
						</a>
						@endforeach	
						<h5 class="text-center">----</h5>
						<hr>
					@endif


					<h5 class="text-center"><b>LAPORAN LAINYA PDAM INI</b></h5>
					<hr>
					@foreach($else as $e)
					<a href="{{route('p.laporan_sat',['id'=>$e->id])}}" class="btn btn-primary col-md-12">PERIODE LAPORAN {{Carbon\Carbon::parse($e->periode_laporan)->format('F Y')}}
						<br>
						<small>INPUT - {{Carbon\Carbon::parse($e->updated_input_at)->format('d F Y')}}</small>
					</a>
					@endforeach					
				</div>
			</div>

		</div>
		<div class="col-md-9">
			<div class="box text-dark box-primary">
				<div class="box-body">
					<table class="table table-bordered table-feedback">
					    <thead>
					    	<tr class="bg-warning">
					    		<th colspan="5">
					    			<b>HASIL PENILAIAN DOKUMEN KAT PDAM  : {{strtoupper($data->kategori_pdam)}}</b>
					    		</th>
					    	</tr>
					        <tr>
					            <th style="width:20%">Hal</th>
					            <th style="width:5%">Tahun</th>
					            <th style="width:5%">Data</th>
					            <th style="width:5%">Satuan</th>
					            <th style="width:50%">Komentar Dari Pemakai</th>
					        </tr>
					    </thead>
					    <tbody>
					        <tr>
					            <th colspan="2">A. NAMA PDAM</th>
					            <th colspan="4">{{strtoupper($pdam->nama_pdam)}}</th>

					        </tr>
					        <tr>
					            <th colspan="2">B. Nama Kota / Kabupaten</th>
					            <td colspan="4">{{$data->nama_daerah.', '.$data->nama_provinsi}}</td>
					        </tr>
					        <tr>
					            <th colspan="2">C. Tanggal masukan</th>
					            <td colspan="4">{{Carbon\Carbon::parse($data->updated_input_at)->format('d F Y')}}</td>
					        </tr>
					        <tr>
					            <th colspan="2">D. Periode laporan yang digunakan</th>
					            <td colspan="1">{{Carbon\Carbon::parse($data->periode_laporan)->format('Y')}}</td>
					            <td colspan="1">
					                {{Carbon\Carbon::parse($data->periode_laporan)->format('F')}} </td>
					            <td colspan="2"></td>
					        </tr>
					        <tr>
					            <th colspan="2">D. Keterangan Umum</th>
					            <td colspan="4">{{$data->keterangan}}</td>
					        </tr>
					        <tr>
					            <td colspan="6"></td>
					        </tr>

					        <tr>
					            <th colspan="2">1. KATEGORI PDAM</th>
					            <th colspan="4">Gunakan data dari hasil evaluasi kinerja BPPSPAM</th>
					        </tr>
					        <tr>
    
							    <td>sat_nilai_kinerja_ttl_dr_bppspam_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_nilai_kinerja_ttl_dr_bppspam_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_nilai_kinerja_ttl_dr_bppspam_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_nilai_kinerja_ttl_dr_bppspam_satuan)}}</td>
							    
							    <td>{{ ($data->sat_nilai_kinerja_ttl_dr_bppspam_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_nilai_aspek_keuangan_dr_bppspam_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_nilai_aspek_keuangan_dr_bppspam_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_nilai_aspek_keuangan_dr_bppspam_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_nilai_aspek_keuangan_dr_bppspam_satuan)}}</td>
							    
							    <td>{{ ($data->sat_nilai_aspek_keuangan_dr_bppspam_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_nilai_aspek_pel_dr_bppspam_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_nilai_aspek_pel_dr_bppspam_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_nilai_aspek_pel_dr_bppspam_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_nilai_aspek_pel_dr_bppspam_satuan)}}</td>
							    
							    <td>{{ ($data->sat_nilai_aspek_pel_dr_bppspam_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_nilai_aspek_operasional_dr_bppspam_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_nilai_aspek_operasional_dr_bppspam_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_nilai_aspek_operasional_dr_bppspam_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_nilai_aspek_operasional_dr_bppspam_satuan)}}</td>
							    
							    <td>{{ ($data->sat_nilai_aspek_operasional_dr_bppspam_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_nilai_aspek_sdm_dr_bppspam_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_nilai_aspek_sdm_dr_bppspam_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_nilai_aspek_sdm_dr_bppspam_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_nilai_aspek_sdm_dr_bppspam_satuan)}}</td>
							    
							    <td>{{ ($data->sat_nilai_aspek_sdm_dr_bppspam_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_status_kinerja_pdam_tahun_sbl_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_status_kinerja_pdam_tahun_sbl_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_status_kinerja_pdam_tahun_sbl_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_status_kinerja_pdam_tahun_sbl_satuan)}}</td>
							    
							    <td>{{ ($data->sat_status_kinerja_pdam_tahun_sbl_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_status_kinerja_pdam_2_tahun_sbl_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_status_kinerja_pdam_2_tahun_sbl_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_status_kinerja_pdam_2_tahun_sbl_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_status_kinerja_pdam_2_tahun_sbl_satuan)}}</td>
							    
							    <td>{{ ($data->sat_status_kinerja_pdam_2_tahun_sbl_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_status_kinerja_pdam_3_tahun_sbl_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_status_kinerja_pdam_3_tahun_sbl_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_status_kinerja_pdam_3_tahun_sbl_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_status_kinerja_pdam_3_tahun_sbl_satuan)}}</td>
							    
							    <td>{{ ($data->sat_status_kinerja_pdam_3_tahun_sbl_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_jumlah_pd_di_wilayah_administratif_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_jumlah_pd_di_wilayah_administratif_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_jumlah_pd_di_wilayah_administratif_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_jumlah_pd_di_wilayah_administratif_satuan)}}</td>
							    
							    <td>{{ ($data->sat_jumlah_pd_di_wilayah_administratif_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_jumlah_pd_di_wilayah_pel_teknis_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_jumlah_pd_di_wilayah_pel_teknis_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_jumlah_pd_di_wilayah_pel_teknis_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_jumlah_pd_di_wilayah_pel_teknis_satuan)}}</td>
							    
							    <td>{{ ($data->sat_jumlah_pd_di_wilayah_pel_teknis_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_jumlah_pd_trl_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_jumlah_pd_trl_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_jumlah_pd_trl_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_jumlah_pd_trl_satuan)}}</td>
							    
							    <td>{{ ($data->sat_jumlah_pd_trl_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_jumlah_jiwa_per_keluarga_data_bps_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_jumlah_jiwa_per_keluarga_data_bps_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_jumlah_jiwa_per_keluarga_data_bps_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_jumlah_jiwa_per_keluarga_data_bps_satuan)}}</td>
							    
							    <td>{{ ($data->sat_jumlah_jiwa_per_keluarga_data_bps_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_jumlah_pelanggan_ttl_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_jumlah_pelanggan_ttl_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_jumlah_pelanggan_ttl_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_jumlah_pelanggan_ttl_satuan)}}</td>
							    
							    <td>{{ ($data->sat_jumlah_pelanggan_ttl_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_jumlah_sam_baru_kum_slm_per_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_jumlah_sam_baru_kum_slm_per_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_jumlah_sam_baru_kum_slm_per_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_jumlah_sam_baru_kum_slm_per_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_jumlah_sam_baru_kum_slm_per_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_jumlah_sam_rumah_tangga_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_jumlah_sam_rumah_tangga_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_jumlah_sam_rumah_tangga_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_jumlah_sam_rumah_tangga_satuan)}}</td>
							    
							    <td>{{ ($data->sat_jumlah_sam_rumah_tangga_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_jumlah_sistem_yg_beroperasi_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_jumlah_sistem_yg_beroperasi_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_jumlah_sistem_yg_beroperasi_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_jumlah_sistem_yg_beroperasi_satuan)}}</td>
							    
							    <td>{{ ($data->sat_jumlah_sistem_yg_beroperasi_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_jumlah_ikk_atau_cabang_yg_diop_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_jumlah_ikk_atau_cabang_yg_diop_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_jumlah_ikk_atau_cabang_yg_diop_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_jumlah_ikk_atau_cabang_yg_diop_satuan)}}</td>
							    
							    <td>{{ ($data->sat_jumlah_ikk_atau_cabang_yg_diop_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_volume_air_yg_diproduksi_kum_slm_per_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_volume_air_yg_diproduksi_kum_slm_per_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_volume_air_yg_diproduksi_kum_slm_per_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_volume_air_yg_diproduksi_kum_slm_per_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_volume_air_yg_diproduksi_kum_slm_per_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_apakah_tersedia_meter_induk_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_apakah_tersedia_meter_induk_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_apakah_tersedia_meter_induk_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_apakah_tersedia_meter_induk_satuan)}}</td>
							    
							    <td>{{ ($data->sat_apakah_tersedia_meter_induk_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_volume_air_yg_dds_kum_slm_per_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_volume_air_yg_dds_kum_slm_per_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_volume_air_yg_dds_kum_slm_per_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_volume_air_yg_dds_kum_slm_per_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_volume_air_yg_dds_kum_slm_per_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_volume_air_yg_terjual_kum_slm_per_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_volume_air_yg_terjual_kum_slm_per_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_volume_air_yg_terjual_kum_slm_per_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_volume_air_yg_terjual_kum_slm_per_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_volume_air_yg_terjual_kum_slm_per_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_kapasitas_pengambilan_air_baku_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_kapasitas_pengambilan_air_baku_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_kapasitas_pengambilan_air_baku_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_kapasitas_pengambilan_air_baku_satuan)}}</td>
							    
							    <td>{{ ($data->sat_kapasitas_pengambilan_air_baku_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_kapasitas_produksi_air_yg_terpasang_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_kapasitas_produksi_air_yg_terpasang_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_kapasitas_produksi_air_yg_terpasang_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_kapasitas_produksi_air_yg_terpasang_satuan)}}</td>
							    
							    <td>{{ ($data->sat_kapasitas_produksi_air_yg_terpasang_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_jam_operasional_pel_ratarata_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_jam_operasional_pel_ratarata_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_jam_operasional_pel_ratarata_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_jam_operasional_pel_ratarata_satuan)}}</td>
							    
							    <td>{{ ($data->sat_jam_operasional_pel_ratarata_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_ttl_pemakaian_lstrk_kum_slm_per_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_ttl_pemakaian_lstrk_kum_slm_per_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_ttl_pemakaian_lstrk_kum_slm_per_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_ttl_pemakaian_lstrk_kum_slm_per_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_ttl_pemakaian_lstrk_kum_slm_per_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_ttl_pemakaian_bbm_kum_slm_per_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_ttl_pemakaian_bbm_kum_slm_per_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_ttl_pemakaian_bbm_kum_slm_per_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_ttl_pemakaian_bbm_kum_slm_per_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_ttl_pemakaian_bbm_kum_slm_per_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_ttl_by_lstrk_kum_slm_per_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_ttl_by_lstrk_kum_slm_per_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_ttl_by_lstrk_kum_slm_per_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_ttl_by_lstrk_kum_slm_per_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_ttl_by_lstrk_kum_slm_per_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_tarif_lstrk_pln_u_pdam_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_tarif_lstrk_pln_u_pdam_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_tarif_lstrk_pln_u_pdam_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_tarif_lstrk_pln_u_pdam_satuan)}}</td>
							    
							    <td>{{ ($data->sat_tarif_lstrk_pln_u_pdam_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_ttl_by_bbm_kum_slm_per_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_ttl_by_bbm_kum_slm_per_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_ttl_by_bbm_kum_slm_per_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_ttl_by_bbm_kum_slm_per_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_ttl_by_bbm_kum_slm_per_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_hg_bbm_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_hg_bbm_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_hg_bbm_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_hg_bbm_satuan)}}</td>
							    
							    <td>{{ ($data->sat_hg_bbm_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_ttl_by_usaha_tanpa_pensut_kum_slm_per_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_ttl_by_usaha_tanpa_pensut_kum_slm_per_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_ttl_by_usaha_tanpa_pensut_kum_slm_per_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_ttl_by_usaha_tanpa_pensut_kum_slm_per_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_ttl_by_usaha_tanpa_pensut_kum_slm_per_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_ttl_by_usaha_dg_pensut_kum_slm_per_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_ttl_by_usaha_dg_pensut_kum_slm_per_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_ttl_by_usaha_dg_pensut_kum_slm_per_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_ttl_by_usaha_dg_pensut_kum_slm_per_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_ttl_by_usaha_dg_pensut_kum_slm_per_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_tarif_air_ratarata_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_tarif_air_ratarata_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_tarif_air_ratarata_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_tarif_air_ratarata_satuan)}}</td>
							    
							    <td>{{ ($data->sat_tarif_air_ratarata_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_pdpt_air_data_rek_ditagih_kum_slm_prd_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_pdpt_air_data_rek_ditagih_kum_slm_prd_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_pdpt_air_data_rek_ditagih_kum_slm_prd_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_pdpt_air_data_rek_ditagih_kum_slm_prd_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_pdpt_air_data_rek_ditagih_kum_slm_prd_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_penerimaan_dr_pjl_air_kum_slm_per_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_penerimaan_dr_pjl_air_kum_slm_per_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_penerimaan_dr_pjl_air_kum_slm_per_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_penerimaan_dr_pjl_air_kum_slm_per_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_penerimaan_dr_pjl_air_kum_slm_per_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_pdpt_yg_ln_kum_slm_per_lap_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_pdpt_yg_ln_kum_slm_per_lap_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_pdpt_yg_ln_kum_slm_per_lap_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_pdpt_yg_ln_kum_slm_per_lap_satuan)}}</td>
							    
							    <td>{{ ($data->sat_pdpt_yg_ln_kum_slm_per_lap_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_apakah_pemda_mempunyai_rispam_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_apakah_pemda_mempunyai_rispam_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_apakah_pemda_mempunyai_rispam_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_apakah_pemda_mempunyai_rispam_satuan)}}</td>
							    
							    <td>{{ ($data->sat_apakah_pemda_mempunyai_rispam_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_tahun_rispam_diterbitkan_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_tahun_rispam_diterbitkan_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_tahun_rispam_diterbitkan_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_tahun_rispam_diterbitkan_satuan)}}</td>
							    
							    <td>{{ ($data->sat_tahun_rispam_diterbitkan_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_lamanya_masa_berlaku_rispam_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_lamanya_masa_berlaku_rispam_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_lamanya_masa_berlaku_rispam_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_lamanya_masa_berlaku_rispam_satuan)}}</td>
							    
							    <td>{{ ($data->sat_lamanya_masa_berlaku_rispam_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_apakah_pemda_sedang_menyiapkan_rispam_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_apakah_pemda_sedang_menyiapkan_rispam_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_apakah_pemda_sedang_menyiapkan_rispam_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_apakah_pemda_sedang_menyiapkan_rispam_satuan)}}</td>
							    
							    <td>{{ ($data->sat_apakah_pemda_sedang_menyiapkan_rispam_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_tahun_rispam_akan_diterbitkan_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_tahun_rispam_akan_diterbitkan_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_tahun_rispam_akan_diterbitkan_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_tahun_rispam_akan_diterbitkan_satuan)}}</td>
							    
							    <td>{{ ($data->sat_tahun_rispam_akan_diterbitkan_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_jumlah_pd_proyeksi_di_tahun_tg_dlm_rispam_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_jumlah_pd_proyeksi_di_tahun_tg_dlm_rispam_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_jumlah_pd_proyeksi_di_tahun_tg_dlm_rispam_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_jumlah_pd_proyeksi_di_tahun_tg_dlm_rispam_satuan)}}</td>
							    
							    <td>{{ ($data->sat_jumlah_pd_proyeksi_di_tahun_tg_dlm_rispam_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_tg_pop_yg_akan_trl_dg_jar_ppp_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_tg_pop_yg_akan_trl_dg_jar_ppp_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_tg_pop_yg_akan_trl_dg_jar_ppp_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_tg_pop_yg_akan_trl_dg_jar_ppp_satuan)}}</td>
							    
							    <td>{{ ($data->sat_tg_pop_yg_akan_trl_dg_jar_ppp_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_tg_pop_yg_akan_trl_dg_jar_bkn_ppp_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_tg_pop_yg_akan_trl_dg_jar_bkn_ppp_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_tg_pop_yg_akan_trl_dg_jar_bkn_ppp_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_tg_pop_yg_akan_trl_dg_jar_bkn_ppp_satuan)}}</td>
							    
							    <td>{{ ($data->sat_tg_pop_yg_akan_trl_dg_jar_bkn_ppp_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_tg_cakupan_pel_jar_ppp_di_tahun_tg_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_tg_cakupan_pel_jar_ppp_di_tahun_tg_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_tg_cakupan_pel_jar_ppp_di_tahun_tg_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_tg_cakupan_pel_jar_ppp_di_tahun_tg_satuan)}}</td>
							    
							    <td>{{ ($data->sat_tg_cakupan_pel_jar_ppp_di_tahun_tg_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_tg_cakupan_pel_jar_bkn_ppp_di_tahun_tg_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_tg_cakupan_pel_jar_bkn_ppp_di_tahun_tg_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_tg_cakupan_pel_jar_bkn_ppp_di_tahun_tg_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_tg_cakupan_pel_jar_bkn_ppp_di_tahun_tg_satuan)}}</td>
							    
							    <td>{{ ($data->sat_tg_cakupan_pel_jar_bkn_ppp_di_tahun_tg_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_alk_apbd_u_penyertaan_modal_pdam_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_alk_apbd_u_penyertaan_modal_pdam_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_alk_apbd_u_penyertaan_modal_pdam_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_alk_apbd_u_penyertaan_modal_pdam_satuan)}}</td>
							    
							    <td>{{ ($data->sat_alk_apbd_u_penyertaan_modal_pdam_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_alk_dak_u_pny_air_minum_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_alk_dak_u_pny_air_minum_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_alk_dak_u_pny_air_minum_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_alk_dak_u_pny_air_minum_satuan)}}</td>
							    
							    <td>{{ ($data->sat_alk_dak_u_pny_air_minum_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_alk_apbd_lainnya_u_pny_air_minum_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_alk_apbd_lainnya_u_pny_air_minum_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_alk_apbd_lainnya_u_pny_air_minum_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_alk_apbd_lainnya_u_pny_air_minum_satuan)}}</td>
							    
							    <td>{{ ($data->sat_alk_apbd_lainnya_u_pny_air_minum_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_jumlah_ttl_apbd_pemda_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_jumlah_ttl_apbd_pemda_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_jumlah_ttl_apbd_pemda_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_jumlah_ttl_apbd_pemda_satuan)}}</td>
							    
							    <td>{{ ($data->sat_jumlah_ttl_apbd_pemda_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_kapasitas_fkl_pemda_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_kapasitas_fkl_pemda_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_kapasitas_fkl_pemda_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_kapasitas_fkl_pemda_satuan)}}</td>
							    
							    <td>{{ ($data->sat_kapasitas_fkl_pemda_ket)}}</td>
							    
							</tr>
							<tr>
							    
							    <td>sat_dana_investasi_non_pemerintah_nilai</td>
							    
							    <td class="text-center">{{ $data->sat_dana_investasi_non_pemerintah_tahun }}</td>
							    
							    <td class="text-right"> {{ number_format($data->sat_dana_investasi_non_pemerintah_nilai,2,'.',',')}}</td>
							    
							    <td>{{ ($data->sat_dana_investasi_non_pemerintah_satuan)}}</td>
							    
							    <td>{{ ($data->sat_dana_investasi_non_pemerintah_ket)}}</td>
							    
							</tr>

					       

					    </tbody>
					</table>
				</div>
			</div>

		</div>
	</div>

@stop