@extends('adminlte::page')


@section('content_header')
<div class="row  text-center">
		<div class="col-md-12 bg ">
			<h1><b class="">KINERJA AIR MINUM {{HP::fokus_tahun()}}</b></h1>
		</div>
	</div>
    
@stop


@section('content')
	<div class="box box-warning">
		<div class="box-body">
			<table class="table-condensed table table-bordered ">
				<thead>
					<tr>
						<th rowspan="2">KODE PEMDA</th>
						<th rowspan="2">NAMA DAERAH</th>
						<th rowspan="2">NAMA PROVINSI</th>
						<th rowspan="2">PENGANGGARAN</th>
						<th colspan="3">INDIKATOR</th>



					</tr>
					<tr>
						<th>INDIKATOR PEMBUATAN DOKUMEN KEBIJAKAN</th>
						<th>INDIKATOR SR</th>
						<th>INDIKATOR PENETAPAN LOKASI</th>

					</tr>

				</thead>
				<tbody>
					@foreach($data as $d)
						<tr>
							<td rowspan="2">{{$d->kodepemda}}</td>
							<td rowspan="2">{{$d->nama_daerah}}</td>
							<td rowspan="2">{{$d->nama_provinsi}}</td>
							<td rowspan="2">{{number_format($d->anggaran_i,0)}}</td>
							<td>{{number_format($d->indikator_dokumen,0)}} Indikator</td>
							<td>{{number_format($d->indikator_sr,0)}} Indikator</td>
							<td>{{number_format($d->indikator_lokasi,0)}} Indikator</td>
							
						</tr>
						<tr>
							<td>
								<a href="{{route('kinerja-rkpd.detail',['kodepemda'=>$d->kodepemda,'tipe'=>1])}}" class="btn btn-primary btn-xs">DETAIL</a>
							</td>
							<td>
								<a href="{{route('kinerja-rkpd.detail',['kodepemda'=>$d->kodepemda,'tipe'=>2])}}" class="btn btn-primary btn-xs">DETAIL</a>
							</td>
							<td>
								<a href="{{route('kinerja-rkpd.detail',['kodepemda'=>$d->kodepemda,'tipe'=>3])}}" class="btn btn-primary btn-xs">DETAIL</a>
							</td>
						</tr>
					@endforeach	
				</tbody>
			</table>
		</div>
	</div>

@stop