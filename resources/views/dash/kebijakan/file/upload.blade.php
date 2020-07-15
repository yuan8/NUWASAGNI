@extends('adminlte::dash')


@section('content_header')
    <h1>UPLOAD {{$jenis}} BERLAKU TAHUN {{HP::fokus_tahun()}} </h1>
@stop

@section('content')
<div class="box-primary box">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>FILE</label>
					<input type="file" class="form-control" name="file" required="">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>TAHUN MULAI BERLAKU</label>
					<select class="form-control" required="" name="tahun_mulai">
						<?php for ($i=(int)HP::fokus_tahun(); $i>(HP::fokus_tahun()-5); $i--) { ?> 

							<option value="{{$i}}">{{$i}}</option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
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

@stop