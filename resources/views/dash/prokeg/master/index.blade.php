@extends('adminlte::dash')


@section('content_header')
	<h1>MASTER PROGRAM KEGIATAN DAERAH TAHUN {{HP::fokus_tahun()}}</h1>
@stop

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-body">
					<table class="table table-bordered" id="table_data">
						<thead>
							<tr>
								<th>KODEPEMDA</th>
								<th>NAMA PEMDA</th>
								<th>NAMA PROVINSI</th>
								<th>PAGU</th>
								<th>JUMLAH PROGRAM</th>
								<th>JUMLAH KEGIATAN</th>
								<th style="min-width: 150px;">ACTION</th>

							</tr>
						</thead>
						<tbody>
							@foreach($data as $d)
								<tr>
									<td>{{$d->kodepemda}}</td>
									<td>{{$d->nama_daerah}}</td>
									<td>{{$d->nama_daerah}}</td>
									<td>{{$d->pagu}}</td>
									<td>{{$d->jumlah_program}}</td>
									<td>{{$d->jumlah_kegiatan}}</td>
									<td>
										<div class="btn-group">
											<a href="{{route('d.master.prokeg.download',['kodepemda'=>$d->kodepemda])}}"  class="btn btn-primary btn-xs">DOWNLOAD</a>
											<a href="" class="btn btn-info btn-xs">VIEW </a>

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
	$('#table_data').dataTable();

</script>

@stop