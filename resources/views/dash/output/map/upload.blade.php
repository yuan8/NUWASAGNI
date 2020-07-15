@extends('adminlte::dash')


@section('content_header')
    <h1>OUTPUT BERUPA MAP UNTUK NUWSP TAHUN {{HP::fokus_tahun()}}</h1>
@stop

@section('content')
<div class="box box-primary">
	<form action="{{route('d.out.map.store')}}" method="post" enctype='multipart/form-data'>
		@csrf
		<div class="box-body">
			<div class="form-group">
				<label>JUDUL</label>
				<input type="text" class="form-control" name="title" required="">
			</div>
			<div class="form-group">
				<label>FILE XLSM</label>
				<input type="file" class="form-control" accept=".xlsm" name="file" required="">
			</div>
		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-primary btn-sm">Upload</button>
		</div>
	</form>
</div>

@stop