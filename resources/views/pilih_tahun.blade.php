<!DOCTYPE html>
<html>
<head>
	<title></title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
</head>
<body style="background: linear-gradient(120deg, rgb(0, 0, 36) 1%, rgb(9, 72, 121) 33%, rgb(20, 151, 218) 86%, rgba(10, 228, 236, 0.682) 98%); min-height: 100vh;">

	<div class="col-md-4 col-md-offset-4 text-center animated bounceInUp" style="color:#fff; margin-top: 5%">
    	<img src="{{url('logo.png')}}" class="" style="max-width: 20%">
    	<h5><b>BANGDA KEMENDAGRI</b></h4>
    	<h6><b>SUPD II</b></h6>
    	<hr>
    	<p><b>NUWASP - {{date('Y')}}</b></p>
    </div>
	<div class="panel col-md-6 col-md-offset-3" >
		<form action="{{route('pilih_tahun.store')}}" method="post">
			@csrf
			<div class="panel-header">
			<h5 class="text-center"><b>PIDAH TAHUN AKSES</b></h5>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<select class="form-control" name="tahun_akses">
					<?php $tahun_start=date('Y')-1; ?>
					<?php for ($i=$tahun_start; $i < ($tahun_start+3) ; $i++) { 
					?>
						<option value="{{$i}}" {{$i==$tahun_akses_present?'selected':''}}>{{$i}}</option>
					<?php	# code...
					}
					?>
				</select>

			</div>
		</div>
		<div class="panel-footer text-center">
				<button type="submit" class="btn btn-primary btn-sm">PIDAHKAN</button>
			
		</div>
		</form>
	</div>

</body>
</html>