@extends('adminlte::dash')


@section('content_header')
	<h1>UPLOAD HASIL PEMETAAN PROGRAM KEGIATAN DAERAH TAHUN {{HP::fokus_tahun()}}</h1>
@stop

@section('content')
			<form action="{{route('d.master.prokeg.upload')}}" method="post" enctype="multipart/form-data">

	<div class="box box-primary text-center">
		<div class="box-body">
			<p class="text-yellow"><span class="badge badge-warning "><i class="fa fa-info"></i></span> <b>PASTIKAN DOKUMEN ANDA SESUAI TAHUN BERLAKU ({{HP::fokus_tahun()}})</b></p>
					@csrf
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<input type="file" class="form-control" required="" name="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
					</div>
				</div>
				<br>
				
		</div>	
		<div class="box-footer">
			<button class="btn btn-primary ">UPLOAD</button>
		</div>
	</div>
	</form>



@stop