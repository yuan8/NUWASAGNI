@extends('adminlte::page')


@section('content_header')
     <div class="row">
    	<div class="col-md-12">
    		<h3 class="text-uppercase text-center">RAKORTEK {{$daerah->nama_daerah}}/ {{$daerah->nama_provinsi}} -  {{$tahun}}</h3>
    	</div>
    </div>
   
    <?php
?>
@stop

@section('content')
	<div class="row">
		<div class="col-md-12 table-responsive">
			<div class="box box-warning">
				<div class="box-body">
					<table class="table table-bordered table-condensed table-striped table-hover table-init-datatable">
				<thead>
					<tr>
						<th rowspan="2">KODE INDIKATOR</th>
						<th rowspan="2">URAIAN INDIKATOR</th>
						<th colspan="2">TARGET NASIONAL</th>
						<th colspan="2">KOMITMEN TARGET DAERAH</th>
						<th rowspan="2">CATATAN</th>
					</tr>
					<tr>
						<th>TARGET</th>
						<th>SATUAN</th>
						<th>TARGET</th>
						<th>SATUAN</th>

					</tr>
				</thead>
				<tbody>
				@foreach($data as $d)
				<tr>
					
					<td>{{$d->kodeindikator}}</td>
					<td>{{$d->namaindikator}}</td>
					<td>{{$d->target_nasional}}</td>
					<td>{{$d->nama_satuan}}</td>
					<td>{{$d->komitmen_target}}</td>
					<td>{{$d->nama_satuan}}</td>
					<td>{{$d->catatan_daerah}}</td>
				</tr>
					

				@endforeach
				</tbody>
			</table>
				</div>
			</div>
		</div>
	</div>


@stop
