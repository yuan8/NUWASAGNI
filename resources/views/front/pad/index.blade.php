
@extends('adminlte::page')

@section('content_header')
     <div class="row">
    	<div class="col-md-12">
    		<h3 class="text-uppercase text-center">DATA PAD TAHUN {{$tahun}} - {{$tahun-2}}</h3>
    	</div>
    </div>
   

@stop

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box-warning box">
				<div class="box-header with-border">
					<div class="col-md-4">
						<form action="" method="get" id="select_tahun">
							<label>TAHUN</label>
							<select class="form-control" name="tahun" onchange="$('#select_tahun').submit()">
								<?php for($i=HP::fokus_tahun();$i>(HP::fokus_tahun()-3);$i--){
									?>
									<option value="{{$i}}" {{$i==$tahun?'selected':''}}>{{$i}} - {{($i-2)}}</option>
								<?php
								} ?>
							</select>
						</form>
					</div>
				</div>
				<div class="box-body table-responsive">
					<table class="table table-bordered table-init-datatable">
						<thead>
							<tr> 
								<th>KODE PEMDA</th>
								<th>NAMA DAERAH</th>
								<th>NAMA PROVINSI</th>
								
								<?php
									for ($i=$tahun; $i>($tahun-3) ;$i--) { 
									?>
							    		<th >ANGGARAN {{$i}}</th>
										<th >REALISASI {{$i}}</th>
										<th >PERSENTASE REALISASI ANGGARAN {{$i}}</th>
							    <?php } ?>

								<th>ACTION</th>


							</tr>
						</thead>
						<tbody>
					
							@foreach($data as $d)

								<tr>


								<td>{{$d['kode_daerah']}}</td>
								<td>{{$d['nama_daerah']}}</td>
								<td>{{$d['nama_provinsi']}}</td>
								@foreach($d['tahun'] as $dt)
									<td class="bg-info">{{number_format($dt['anggaran'],2)}}</td>
									<td class="bg-success">{{number_format($dt['realisasi'],2)}}</td>
									<td class="bg-warning">{{number_format($dt['realisasi_persentase'],2)}}%</td>
								@endforeach
								<td>
										<a href="{{route('pad.detail',['kodepemda'=>$d['kode_daerah'],'tahun'=>$tahun])}}" class="btn btn-info btn-xs">DETAIL</a>
									</td>
							@endforeach


							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


@stop

@section('js')
	<script type="text/javascript">
		$('table').dataTable();
	</script>

@stop