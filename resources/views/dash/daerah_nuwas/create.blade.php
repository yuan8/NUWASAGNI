@extends('adminlte::dash')


@section('content_header')
	<h1>TAMBAH TARGET DAERAH NUWSP </h1>
@stop

@section('content')
	<div class="box box-primary">
		<form action="{{route('d.daerah.strore')}}" method="post">
			@csrf
			<div class="box-body">
			<div class="row">
				<div class="col-md-3">
					<label>Daerah*</label>
					<select class="form-control" name="kode_daerah">
							<option>- PILIH DAERAH -</option>

						@foreach($daerah as $d)
							<option value="{{$d->id}}">{{$d->nama_daerah}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-3">
					<label>PRIORITAS TAHUN HIBAH</label>
					<select class="form-control" id="tahun_bantuan" name="tahun">
							<option value="">- PILIH TAHUN  -</option>
						<?php for($i=HP::fokus_tahun();$i<(HP::fokus_tahun()+5);$i++ ){ ?>
							<option value="{{$i}}">{{$i}}</option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-6" id="jenis_bantuan" style="display: none;">
					<div class="row">
						<div class="col-md-6">
							<label>JENIS HIBAH</label>
							<select class="form-control" multiple="" name="jenis_bantuan[]" style="width: 100%;">
									<option value="PENDAMPINGAN">PENDAMPINGAN</option>
									<option value="STIMULAN">STIMULAN</option>
							</select>
						</div>
						<div class="col-md-6">
							<label>NILAI BANTUAN</label>
							<div class="input-group" name="nilai_bantuan">
								<span class="input-group-addon">Rp.</span>
								<input type="number" name="" class="form-control">
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Tambah</button>
		</div>
		</form>
	</div>

@stop


@section('js')
<script type="text/javascript">
	$('select').select2();

	$('#tahun_bantuan').on('change',function(){
		console.log(this.value);
		if(this.value!=''){
			$('#jenis_bantuan').css('display','block');
		}else{
			$('#jenis_bantuan').css('display','none');
		}
	});

	$('#tahun_bantuan').trigger('change');


</script>




@stop