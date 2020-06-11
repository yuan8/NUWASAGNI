@extends('adminlte::dash')


@section('content_header')
    <h1>{{$jenis}} BERLAKU TAHUN {{HP::fokus_tahun()}} </h1>
@stop

@section('content')
<hr>
<form action="{{route('d.kb.f.upload',['jenis'=>$jenis])}}" method="post" enctype='multipart/form-data' >
	@csrf
	<div class="box-primary box">
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<label>Nama File</label>
				<input type="text" name="nama" class="form-control" required="">
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Daerah</label>
					<select class="form-control" required="" name="kode_daerah">
						@foreach($daerah as $d)
							<option value="{{$d->id}}">{{$d->nama_daerah}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>FILE</label>
					<input type="file" class="form-control" name="file" required="">
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label>TAHUN MULAI BERLAKU</label>
					<select class="form-control" required="" name="tahun_mulai">
						<?php for ($i=(int)HP::fokus_tahun(); $i>(HP::fokus_tahun()-20); $i--) { ?> 

							<option value="{{$i}}">{{$i}}</option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label>TAHUN SELESAI BERLAKU</label>
						<select class="form-control" required="" name="tahun_selesai">
						<?php for ($i=(int)HP::fokus_tahun(); $i<(HP::fokus_tahun()+20); $i++) { ?> 

							<option value="{{$i}}">{{$i}}</option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<button type="submit" class="btn btn-primary">UPLOAD</button>
		
	</div>
</div>
</form>


<hr>

<div class="row">
	<div class="col-md-12">
		
			<div class="box box-primary">
			<div class="box-header with-border">
				
				
			</div>
			<div class="box-body">
				<table class="table table-bordered table-striped" id="table_data">
					<thead>
						<tr>
							<th rowspan="2">
								NAMA DAERAH
							</th>
							<th rowspan="2">
								NAMA File
							</th>
							<th colspan="2">
								BERLAKU
							</th>
							<th rowspan="2">ACTION</th>

						</tr>
						<tr>
							<th>TAHUN AWAL</th>
							<th>TAHUN AHIR</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


@stop


@section('js')
<script type="text/javascript">
	var table_data=$('#table_data').DataTable({
		sort:false,
		columns:[
			{
				data:'nama_daerah',
			},
			{
				data:'nama',
			},
			{
				data:'tahun'
			},
			{
				data:'tahun_selesai',
			},
			{
				render:function(data,type,dataRaw){
					var baseUrl='{{url('').'/'}}';
					return '<button class="btn btn-danger btn-xs">DELETE</button><a class="btn btn-success btn-xs" href="{{route('d.kb.f.view',['jenis'=>$jenis])}}/'+dataRaw.id+'" >UPDATE</a><a class="btn btn-info btn-xs" href="'+dataRaw.path_file+'" >VIEW</a>';

				}
			}

		]
	});

	var data_source=<?php echo json_encode($data)?>;

	$(function(){

		table_data.rows.add(data_source).draw();

	});

</script>

@stop