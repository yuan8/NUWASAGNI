@extends('adminlte::page')


@section('content_header')
     <div class="row">
    	<div class="col-md-12">
    		<h3 class="text-uppercase text-center">RAKORTEK {{$tahun}}</h3>
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
					<table class="table table-condensed table-striped table-hover table-init-datatable">
				<thead>
					<tr>
						<th>KODE PEMDA</th>
						<th>NAMA DAERAH</th>
						<th>NAMA PROVINSI</th>
						<th>JUMLAH INDIKATOR TERKAIT AIR MINUM</th>
						<th>ACTION</th>
					</tr>
				</thead>
				<tbody>
				@foreach($data as $d)
					<tr>
						<td>{{$d->kodepemda}}</td>
						<td>{{$d->nama_daerah}}</td>
						<td>{{$d->nama_provinsi}}</td>
						<td>{{number_format($d->jumlah_indikator)}}</td>
						<td>
							<a href="{{route('rakortek.detail',['kodepemda'=>$d->kodepemda,'tahun'=>$tahun])}}" class="btn btn-primary btn-xs">DETAIL</a>
						</td>

					</tr>

				@endforeach
				</tbody>
			</table>
				</div>
			</div>
		</div>
	</div>


@stop
