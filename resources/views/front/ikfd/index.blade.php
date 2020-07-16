
@extends('adminlte::page')

@section('content_header')
    <div style="width: 100%; float: left;">
    	<div class=" text-center header-page">
    		<p class="text-uppercase">DATA IKFD TAHUN {{$tahun}} - {{$tahun-2}}</p>
    	</div>
    </div>
   

@stop

@section('content')
	<h1 class="text-center"><b>IKFD TAHUN {{$tahun}} - {{$tahun-2}}</b></h1>
	<div class="row no-gutter">
		<div class="col-md-12 col-no-padding">
			<div class="box-orange box">
				<div class="box-header with-border">
					<div class="col-md-4">
						<form action="" method="get" id="select_tahun">
							<label>TAHUN</label>
							<select class="form-control" name="tahun" onchange="$('#select_tahun').submit()">
									<option value="{{$tahun}}" >-</option>

									<?php for($i=HP::fokus_tahun()-1;$i>(HP::fokus_tahun()-3);$i--){
										?>
										<option value="{{$i}}" {{$i==$tahun?'selected':''}}>{{$i}} - {{($i-2)}}</option>
									<?php
								} ?>
							</select>
						</form>
					</div>
				</div>
				<div class="box-body table-responsive">
					<table class="table table-bordered " id="table_ikfd">
						<thead  >
							<tr> 
								<th>KODE PEMDA</th>
								<th>NAMA DAERAH</th>
								<th>NAMA PROVINSI</th>
								@foreach($tahun_data as $t)
									<th>NILAI {{$t}}</th>
									<th>KATEGORI {{$t}}</th>
								@endforeach
								


							</tr>
						</thead>
						<tbody>
					
							@foreach($data as $d)

								<tr>


								<td>{{$d['kode_daerah']}}</td>
								<td>{{$d['nama_daerah']}}</td>
								<td>{{$d['nama_provinsi']}}</td>
								@foreach($d['tahun'] as $dt)
									<td class="bg-info">{{($dt['nilai'])}}</td>
									<td class="bg-success">{{($dt['kategori'])}}</td>
								@endforeach
								
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

		var table = $('#table_ikfd').DataTable();
		 
		


	@php 
		if(isset($_GET['q'])){
			@endphp
			setTimeout(function(){
				table.search("{{$_GET['q']}}").draw();
			},1000);

			@php
		}
	@endphp

	</script>


@stop