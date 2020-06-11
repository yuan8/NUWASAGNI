@extends('adminlte::dash')


@section('content_header')
    <h1>UPDATE {{$jenis}} BERLAKU TAHUN {{HP::fokus_tahun()}} </h1>
@stop

@section('content')
<hr>
<form action="{{route('d.kb.f.update',['jenis'=>$jenis,'id'=>$data->id])}}" method="post" enctype='multipart/form-data'>
	@csrf
	<div class="box-primary box">
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<label>Nama File</label>
				<input type="text" name="nama" value="{{$data->nama}}" class="form-control" required="">
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Daerah</label>
					<select class="form-control" required="" name="kode_daerah">
						@foreach($daerah as $d)
							<option value="{{$d->id}}" {{$data->kode_daerah==$d->id?'selected':''}}>{{$d->nama_daerah}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>FILE</label>
					<input type="file" class="form-control" name="file" >
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label>TAHUN MULAI BERLAKU</label>
					<select class="form-control" required="" name="tahun_mulai">
						<?php for ($i=(int)HP::fokus_tahun(); $i>(HP::fokus_tahun()-20); $i--) { ?> 
							<option value="{{$i}}" {{$data->tahun==$i?'selected':''}}>{{$i}}</option>
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

							<option value="{{$i}}" {{$data->tahun_selesai==$i?'selected':''}}>{{$i}}</option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<button type="submit" class="btn btn-primary">UPDATE</button>
		
	</div>
</div>
</form>


<hr>

@stop