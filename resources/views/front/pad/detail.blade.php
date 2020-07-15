
@extends('adminlte::page')

@section('content_header')
     <div class="row">
    	<div class="col-md-12">
    		<h3 class="text-uppercase text-center">DATA PAD TAHUN {{$tahun}} - {{$tahun - 2}} {{$daerah->nama_daerah}} / {{$daerah->nama_provinsi}} {{$tahun}}</h3>
    	</div>
    </div>
    <style type="text/css">
    	table td, table th{
    		font-size: 10px;
    	}
    </style>
   
    <?php
?>
@stop

@section('content')
	<div class="row">
		<div class="col-md-12">
			
			<div class="box-warning box">
				<div class="box-body table-responsive">
					<table class="table table-bordered" id="table_data">
							<thead>
								<tr>
									<th colspan="3">AKUN</th>
									<?php 
									for ($i=$tahun; $i>($tahun-3) ;$i--) { 
									?>
							    		<th colspan="3" >TAHUN {{$i}}</th>
							    	<?php } ?>
									
								</tr>
								<tr>
									<th>BIDANG</th>
									<th>SUB BIDANG</th>
									<th>SUB SUB BIDANG</th>

									<?php
									for ($i=$tahun; $i>($tahun-3) ;$i--) { 
									?>
							    		<th >ANGGARAN {{$i}}</th>
										<th >REALISASI {{$i}}</th>
										<th >PERSENTASE REALISASI ANGGARAN {{$i}}</th>
							    	<?php } ?>

								</tr>
							</thead>
						<tbody>

							@foreach($data as $d)

							<tr>

								@if(($d['kat_akun']=='')OR($d['kat_akun']==null))

								<td></td>
								<td></td>
								<td>{{$d['nama']}}</td>
								@elseif($d['kat_akun']=='SUB_BIDANG')

								<td></td>
								<td class="bg-yellow">{{$d['nama']}}</td>

								<td></td>

								@else

								<td class="bg-primary">{{$d['nama']}}</td>
								<td></td>
								<td></td>

								@endif
								@foreach($d['tahun'] as $dt)
								<td class="bg-info">{{number_format($dt['anggaran'],2)}}</td>
								<td class="bg-success">{{number_format($dt['realisasi'],2)}}</td>
								<td class="bg-warning">{{number_format($dt['realisasi_persentase'],2)}}%</td>



								@endforeach

							</tr>

							@endforeach

							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


@stop

@section('js')
	<script type="text/javascript">
		$('table').dataTable({
			sort:false,
		});
	</script>

@stop