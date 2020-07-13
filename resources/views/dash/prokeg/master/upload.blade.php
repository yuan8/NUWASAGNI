@extends('adminlte::dash')


@section('content_header')
	<h1>UPLOAD HASIL PEMETAAN PROGRAM KEGIATAN DAERAH TAHUN {{HP::fokus_tahun()}}</h1>
@stop

@section('content')
			<form action="{{route('d.master.prokeg.upload')}}" method="post" enctype="multipart/form-data">

	<div class="box box-primary text-center">
		<div class="box-body">
			<p class="text-yellow"><span class="badge badge-warning "><i class="fa fa-info"></i></span> <b>PASTIKAN DOKUMEN ANDA SESUAI TAHUN BERLAKU ({{HP::fokus_tahun()}})</b></p>
					@csrf
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<input type="file" class="form-control" required="" name="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
					</div>
				</div>
				<br>
				
		</div>	
		<div class="box-footer">
			<button class="btn btn-primary ">UPLOAD</button>
		</div>
	</div>
	</form>

	<?php
		if(isset($meta_upload_rekap)){
	?>

	<div class="row" id="tb_rekap">
		<div class="col-md-12">
			<div class="box box-primary">
				<p class="text-center">REKAP DATA HASIL UPLOAD {{$meta_upload_rekap['nama_pemda']}}  - <span id="tb_rekap_c"></span> Detik</p>
				<div class="box-body">
					<table class="table-condensed table">
						<thead>
							<tr>
								<th>
									NAMA PEMDA
								</th>
								<th>
									TAHUN DATA
								</th>
								<th>
									JUMLAH PROGRAM
								</th>
								<th>
									JUMLAH PROGRAM  CAPAIAN
								</th>
								<th>
									JUMLAH KEGIATAN 
								</th>
								<th>
									JUMLAH KEGIATAN INDIKATOR 
								</th>
								<th>
									JUMLAH KEGIATAN SUMBERDANA 
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>{{$meta_upload_rekap['nama_pemda']}}</td>
								<td>{{number_format($meta_upload_rekap['tahun'],2)}}</td>
								<td>{{number_format($meta_upload_rekap['jumlah_program'],2)}}</td>
								<td>{{number_format($meta_upload_rekap['jumlah_program_capaian'],2)}}</td>
								<td>{{number_format($meta_upload_rekap['jumlah_kegiatan'],2)}}</td>
								<td>{{number_format($meta_upload_rekap['jumlah_kegiatan_indikator'],2)}}</td>
								<td>{{number_format($meta_upload_rekap['jumlah_kegiatan_sumberdana'],2)}}</td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


	<?php
		}
	 ?>



@stop


@section('js')
	
	<script type="text/javascript">
		var vwi=7;
		function vw(){
			setTimeout(function(){
				if($('#tb_rekap').html()!=undefined){
				vwi--;	
				$('#tb_rekap_c').html(vwi);

					if(vwi==0){
						$('#tb_rekap').remove();
					}
					vw();
				}
			},1000);
		}

		vw();

	</script>
	

@stop