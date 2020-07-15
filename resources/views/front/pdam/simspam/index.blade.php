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
		<div class="box-footer" style="padding-bottom: 0px;">
			
	<div class="row text-dark" style="border-top:1px solid #222">
	@foreach($open_hours as $d)
	<div style="width:calc(100%/7)!important;" class=" col-md-12 {{HP::hari_ini($d['key'])?'bg-yellow text-dark':'bg-default'}}" style="border-right:1px solid #222">
		<b>{{$d['key']}} : {{$d['value']}}</b>
	</div>
	@endforeach
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
			      <li class="" ><a href="{{route('p.laporan_sat',['íd'=>$pdam->id_laporan_terahir])}}">PROFILE PDAM</a></li>
			      <li class="active"><a href="{{route('p.simspam.perpipaan',['id'=>$pdam->kode_daerah])}}">JARINGAN PERPIPAAN</a></li>
			      <li><a href="#">JARINGAN NON PERPIPAAN</a></li>
		
			    </ul>
			  </div>
	</nav>
	</div>
</div>

@endif

    <h5 class="text-center">KONDISI JARINGAN PERPIPAAN  {{strtoupper($pdam->nama_daerah)}}  {{HP::fokus_tahun()}}</h5>

<div class="row" id="chart_riwayat_sr">
	
</div>
<div class="row no-gutter bg-default-g text-dark">
		<div class="col-md-12">
			
			<div class="box box-primary">
				<div class="box-body table-responsive">
					<ul class="nav nav-tabs">
					  <li class="active"><a data-toggle="tab" href="#home">REKAPITULASI</a></li>
					  <li><a data-toggle="tab" href="#menu1">DATA UMUM</a></li>
					  <li><a data-toggle="tab" href="#menu2">DATA PELAYANAN</a></li>
					  <li><a data-toggle="tab" href="#menu3">DATA TEKNIS</a></li>
					  <li><a data-toggle="tab" href="#menu4">TARGET PELAYANAN</a></li>
					  <li><a data-toggle="tab" href="#menu5">RENCANA PENGEMBANGAN</a></li>
					  <li><a data-toggle="tab" href="#menu6">CATATAN</a></li>

					</ul>

					<div class="tab-content">
					  <div id="home" class="tab-pane fade in active">
					   <table id="rekap" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>Unit</th>
								<th>Kapasitas Terpasang (Liter/Detik)</th>
								<th>Kapasitas Produksi (Liter/Detik)</th>
								<th>Kapasitas Distribusi (Liter/Detik)</th>
								<th>Kapasitas Air Terjual (Liter/Detik)</th>
								<th>Kapasitas Belum Terpakai (Liter/Detik)</th>
								<th>Kehilangan Air (%)</th>
								<th>Sambungan Rumah (Unit)</th>
								<th>Tanggal Update</th>

							</tr>
						</thead>
						<tbody>
							
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
							
						</tfoot>
					</table>

					  </div>
					  <div id="menu1" class="tab-pane fade">
					   <table id="data_umum" class="table table-striped table-bordered table-hover">
					<thead>

						<tr>
							<th rowspan="1" align="center"><b>Nama Unit</b></th>
							<th colspan="1" align="center"><b>Kategori Pelayanan
							</b></th>
							<th colspan="1" align="center"><b>Pengelola SPAM
							</b></th>
							<th rowspan="1">Tanggal Update</th>
							
						</tr>

					</thead>
					<tbody>
							
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								
							</tr>
							
						</tfoot>
					</table>
					  </div>
					  <div id="menu2" class="tab-pane fade">
					    <table style="width: 100%" id="data_pelayanan" class="table table-striped table-bordered table-hover">
							<thead>
																<tr>
																	<th rowspan="2" align="center"><font size="1"><b>Nama Unit</b></font></th>
																	<th colspan="5" align="center"><font size="1"><b>Data Pelayanan
																	</b></font></th>
																</tr>
																<tr>
																	<!--<th align=center><font size=1><b>Sambungan Rumah 2016 (Unit)</th>-->
																	<th align="center"><font size="1"><b>Hidran Umum (Unit)
																	</b></font></th><th align="center"><font size="1"><b>Sambungan Komersial Non Domestik
																	</b></font></th><th align="center"><font size="1"><b>Penduduk Terlayani (Jiwa)
																	</b></font></th><th align="center"><font size="1"><b>Persentase Pelayanan (%)
																	</b></font></th>
																	<th align="center"><font size="1"><b>Tanggal Update
																	</b></font></th>
																</tr>
															</thead>
						<tbody>
							
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								
							</tr>
							
						</tfoot>
					</table>

					  </div>
					  	  <div id="menu3" class="tab-pane fade">
					    <table id="data_teknis" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th><font size="1">Nama Unit</font></th>
								<th><font size="1">Kapasitas Terpasang (Liter/Detik)</font></th>
								<th><font size="1">Kapasitas Produksi (Liter/Detik)</font></th>
								<th><font size="1">Kapasitas Distribusi (Liter/Detik)</font></th>
								<th><font size="1">Kapasitas Air Terjual (Liter/Detik)</font></th>
								<th><font size="1">Kapasitas Belum Terpakai (Liter/Detik)</font></th>
								<th><font size="1">Kehilangan Air (%)</font></th>
								<th><font size="1">Jam Operasional Unit Produksi (Jam/Hari)</font></th>
								<th>Tanggal Update</th>


							</tr>
						</thead>
						<tbody>
							
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>


								
							</tr>
							
						</tfoot>
					</table>

					  </div>
					  	  <div id="menu4" class="tab-pane fade">
					    <table id="target_pelayanan" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th><font size="1">Nama Unit</font></th>
									<th><font size="1">Target Sambungan Rumah (Unit)</font></th>
									<th><font size="1">Target Penduduk Terlayani (Jiwa)</font></th>
									<th><font size="1">Target Cakupan Layanan (%)</font></th>
									<th>Tanggal Update</th>
								</tr>
							</thead>
						<tbody>
							
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								
							</tr>
							
						</tfoot>
					</table>

					  </div>
					  	  <div id="menu5" class="tab-pane fade">
					    <table id="renacana_pengembangan" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th rowspan="2" align="center"><font size="1"><b>Unit</b></font></th>
								<th colspan="5" align="center"><font size="1"><b>Rencana Pengembangan
								</b></font></th><th colspan="7" align="center"><font size="1"><b>Air Baku
							</b></font></th></tr>
							<tr>
								<th align="center"><font size="1"><b>Kapasitas Idle Capacity yang akan dimanfaatkan (liter/detik)
								</b></font></th><th align="center"><font size="1"><b>Rencana Tambahan Kapasitas Uprating (liter/detik)
								</b></font></th><th align="center"><font size="1"><b>Rencana Tambahan Kapasitas Pembangunan Unit Baru (liter/detik)
								</b></font></th><th align="center"><font size="1"><b>Rencana Kebutuhan Alokasi Air Baku (liter/detik)
								</b></font></th><th align="center"><font size="1"><b>Rencana Kebutuhan Kapasitas Intake (liter/detik)

								</b></font></th><th align="center"><font size="1"><b>Kapasitas Sumber Air Baku (liter/detik)
								</b></font></th><th align="center"><font size="1"><b>Alokasi Kapasitas Air Baku sesuai SIPA (liter/detik)
								</b></font></th><th align="center"><font size="1"><b>Kapasitas Intake Air Baku (liter/detik)	

						</b></font></th>
							<th>Tanggal Update</th>

					</tr></thead>
						<tbody>
							
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>


								
							</tr>
							
						</tfoot>
					</table>

					  </div>
					  	  <div id="menu6" class="tab-pane fade">
					    <table id="catatan" class="table table-striped table-bordered table-hover">
						<thead>
																<tr>
																	<th><font size="1">Unit</font></th>
																	<th><font size="1">Catatan</font></th>
																<th>Tanggal Update</th>



																</tr>
															</thead>
						<tbody>
							
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								
							</tr>
							
						</tfoot>
					</table>

					  </div>
					</div>
					
															
			</div>
		</div>
	</div>
</div>

@stop

@section('js')
<script type="text/javascript">
	var data_sumber=<?php echo json_encode($data) ?>;

	

	var rekap=$('#rekap').DataTable({
		sort:false,
		 drawCallback: function () {
	      var api = this.api();

		    
		     $( api.table().column(0).footer() ).html('TOTAL');
		     
		     <?php for($i=1;$i<8;$i++){ ?>
		     	@if($i!=6)
		     	$(api.table().column({{$i}}).footer()).html(
		     		api.column({{$i}} ).data().sum().toFixed(2)
		     	);
		     	@endif
		     <?php } ?>

		     
		  },
		columns:[
			{
				data:'nama',
				type:'string'
			},
			{
				data:'kapasitas_terpasang_l_per_detik',
				type:'string'
			},
			{
				data:'kapasitas_produksi_l_per_detik',
				type:'string'
			},
			{
				data:'kapasitas_distribusi_l_per_detik',
				type:'string'
			},
			{
				data:'kapasitas_air_terjual_l_per_detik',
				type:'string'
			},
			{
				data:'kapasitas_belum_terpakai_l_per_detik',
				type:'string'
			},
			{
				data:'kehilangan_air_persen',
				type:'string'
			},
			{
				data:'sambungan_rumah_unit',
				type:'string'
			},
			{
				data:'updated_at',
				type:'string'
			}
		]
	});

	rekap.rows.add(data_sumber).draw();

	var data_umum=$('#data_umum').DataTable({
		sort:false,
		width:'auto',
		 drawCallback: function () {
	      var api = this.api();

		    
		    
	 
		  },
		columns:[
			{
				data:'nama',
				type:'string'
			},
			{
				data:'kat_pelayanan',
				type:'string'
			},
			{
				data:'kat_pengelolaan',
				type:'string'
			},
			
			{
				data:'updated_at',
				type:'string'
			}
			
		]
	});

	data_umum.rows.add(data_sumber).draw();


	var data_pelayanan=$('#data_pelayanan').DataTable({
		sort:false,
		width:'auto',
		 drawCallback: function () {
	      var api = this.api();

		    
		     $( api.table().column(0).footer() ).html('TOTAL');
		     
		     <?php for($i=1;$i<6;$i++){ ?>
		     	@if($i!=5 and $i!=4)
		     	$(api.table().column({{$i}}).footer()).html(
		     		api.column({{$i}} ).data().sum().toFixed(2)
		     	);
		     	@endif
		     <?php } ?>

		     
		  },
		columns:[
			{
				data:'nama',
				type:'string'
			},
			{
				data:'hidran_umum_unit',
				type:'string'
			},
			{
				data:'sambungan_komersial_non_domestik',
				type:'string'
			},
			{
				data:'penduduk_terlayani_jiwa',
				type:'string'
			},
			{
				data:'persentase_pelayanan_persen',
				type:'string'
			},
			{
				data:'updated_at',
				type:'string'
			}
			
		]
	});

	data_pelayanan.rows.add(data_sumber).draw();

	var data_teknis=$('#data_teknis').DataTable({
		sort:false,
		width:'auto',
		 drawCallback: function () {
	      var api = this.api();

		    
		     $( api.table().column(0).footer() ).html('TOTAL');
		     
		     <?php for($i=1;$i<6;$i++){ ?>
		     	$(api.table().column({{$i}}).footer()).html(
		     		api.column({{$i}} ).data().sum().toFixed(2)
		     	);
		     <?php } ?>

		     
		  },
		columns:[
			{
				data:'nama',
				type:'string'
			},
			{
				data:'kapasitas_terpasang_l_per_detik',
				type:'string'
			},
			{
				data:'kapasitas_produksi_l_per_detik',
				type:'string'
			},
			{
				data:'kapasitas_distribusi_l_per_detik',
				type:'string'
			},
			{
				data:'kapasitas_air_terjual_l_per_detik',
				type:'string'
			},
			{
				data:'kapasitas_belum_terpakai_l_per_detik',
				type:'string'
			},
			{
				data:'kehilangan_air_persen',
				type:'string'
			},
			{
				data:'total_jam_oprasional_perhari',
				type:'string'
			},
			{
				data:'updated_at',
				type:'string'
			}
			
		]
	});

	data_teknis.rows.add(data_sumber).draw();

	var target_pelayanan=$('#target_pelayanan').DataTable({
		sort:false,
		width:'auto',
		 drawCallback: function () {
	      var api = this.api();

		    
		     $( api.table().column(0).footer() ).html('TOTAL');
		     
		     <?php for($i=1;$i<4;$i++){ ?>
		     	$(api.table().column({{$i}}).footer()).html(
		     		api.column({{$i}} ).data().sum().toFixed(2)
		     	);
		     <?php } ?>

		     
		  },
		columns:[
			{
				data:'nama',
				type:'string'
			},
			{
				data:'target_sambungan_rumah_unit',
				type:'string'
			},
			{
				data:'target_penduduk_terlayani_jiwa',
				type:'string'
			},
			{
				data:'target_cakupan_layanan_persen',
				type:'string'
			},
			
			{
				data:'updated_at',
				type:'string'
			}
			
		]
	});

	target_pelayanan.rows.add(data_sumber).draw();

		var catatan=$('#catatan').DataTable({
		sort:false,
		width:'auto',
		 drawCallback: function () {
	      var api = this.api();

		    
		    
		     
		     

		     
		  },
		columns:[
			{
				data:'nama',
				type:'string'
			},
			{
				data:'catatan',
				type:'string'
			},
		
			
			{
				data:'updated_at',
				type:'string'
			}
			
		]
	});

	catatan.rows.add(data_sumber).draw();

	var renacana_pengembangan=$('#renacana_pengembangan').DataTable({
		sort:false,
		width:'auto',
		 drawCallback: function () {
	      var api = this.api();

		    
		     $( api.table().column(0).footer() ).html('TOTAL');
		     
		     <?php for($i=1;$i<9;$i++){ ?>
		     	$(api.table().column({{$i}}).footer()).html(
		     		api.column({{$i}} ).data().sum().toFixed(2)
		     	);
		     <?php } ?>

		     
		  },
		columns:[
			{
				data:'nama',
				type:'string'
			},
			{
				data:'idle_capacity_yang_dimanfaatkan_l_per_detik',
				type:'string'
			},
			{
				data:'rencana_penambahan_capacity_uprating_l_per_detaik',
				type:'string'
			},
			{
				data:'rencana_penambahan_capacity_pembangunan_unit_baru_l_per_detaik',
				type:'string'
			},
			{
				data:'rencana_kebutuhan_capacity_air_baku_l_per_detik',
				type:'string'
			},
			{
				data:'rencana_kebutuhan_capacity_intek_l_per_detik',
				type:'string'
			},
			{
				data:'kapasita_sumber_air_baku_l_per_detik',
				type:'string'
			},
			{
				data:'alokasi_kapasitas_air_baku_sesuai_sipa_l_per_detaik',
				type:'string'
			},
			{
				data:'kapasitas_intake_air_baku_l_per_detaik',
				type:'string'
			},
			
			{
				data:'updated_at',
				type:'string'
			}
			
		]
	});

	renacana_pengembangan.rows.add(data_sumber).draw();


	Highcharts.chart('chart_riwayat_sr', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'RIWAYAT SR PADA DATA PERPIPAAN {{strtoupper($pdam->nama_daerah)}}  {{HP::fokus_tahun()}}'
    },
    subtitle: {
        text: 'SUBER DATA : SIMSPAM'
    },
    xAxis: {
        categories: <?php  echo json_encode($riwayat_sr['kategori'])?>,
    },
    yAxis: {
        title: {
            text: 'JUMLAH SR'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'SR',
        data: <?php  echo json_encode($riwayat_sr['data'])?>,
    }]
});

</script>

@stop