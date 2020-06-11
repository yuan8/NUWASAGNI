@extends('adminlte::dash')


@section('content_header')
    <h1>UPDATE {{$jenis!='LAIN_LAIN'?$jenis:'DOKUMEN LAINYA'}}  BERLAKU TAHUN {{HP::fokus_tahun()}} </h1>
@stop

@section('content')
<hr>
<?php
					

		$dx=['xlsx','doc','docx','csv','xls'];
		if(in_array($data->extension, $dx)){
			$link='http://view.officeapps.live.com/op/view.aspx?src=';
		}else{
			$link='';
		}

 ?>
<p><b class="text-uppercase">diupload oleh {{$data->nama_user}}</b> <a href="{{$link.url($data->path)}}" class="btn btn-success btn-xs">View</a></p>
<br>
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
					<h5><b>{{$data->nama_daerah}}</b></h5>
					<input type="hidden" name="kode_daerah" value="{{$data->kode_daerah}}">
				
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>UPDATE FILE</label>
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