@extends('adminlte::dash')


@section('content_header')

	<h1>DAERAH NUWSP {{$tahun .' - '. ($tahun+1)}}</h1>

@stop

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-body">
					<table class="table table-bordered table-condensed" id="datatable">
						<thead>
							<tr>
								<th rowspan="2">KODEPEMDA</th>
								<th  rowspan="2">NAMA DAERAH</th>
								<th  rowspan="2">NAMA PROVINSI</th>
								<th colspan="3" >PRIORITAS</th>
								<th style="min-width: 100px;">ACTION</th>
							</tr>
							<tr>
								<th>JENIS PRIORITAS HIBAH</th>
								<th>TAHUN PRIORITAS HINBAH</th>
								<th>NILAI ANGGRAN HIBAH</th>
							</tr>
						</thead>
						<tbody>
							@foreach($data as $d)
							<tr>
								<td>{{$d->kode_daerah}}</td>
								<td>{{$d->nama_daerah}}</td>
								<td>{{$d->nama_provinsi}}</td>
								<td>{{$d->tahun!=1?str_replace('@',' ',$d->jenis_bantuan):''}}</td>
								<td>{{$d->tahun!=1?$d->tahun:''}}</td>
								<td>{{$d->tahun!=1?'Rp.'.number_format($d->nilai_bantuan,3,'.',','):''}}</td>
								<td>
									<div class="btn-group">
										<a href="" class="btn btn-xs btn-warning">Update</a>
										<a href="" class="btn btn-xs btn-danger">Delete</a>

									</div>
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

@section('js')
<script type="text/javascript">
	$(function(){
		$('#datatable').dataTable({
			sort:false
		});
	});
</script>

@stop