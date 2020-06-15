<div class="row">
	<div class="col-md-4">
		<h5><i class="fa fa-tint"></i> PDAM</h5>
		<a href="{{route('p.laporan_sat',['id'=>$data['kode_daerah']])}}" class="btn btn-primary col-md-12" {{$data['pdam']?'':'disabled'}}>{{$data['pdam']?$data['pdam']:'Tidak Terdapat Data PDAM'}}</a>
	</div>
	<div class="col-md-4">
		<h5><i class="fa fa-file"></i> RKPD FINAL</h5>
		<a href="{{route('pr.data',['id'=>$data['kode_daerah']])}}" class="btn btn-primary col-md-12" {{$data['jumlah_kegiatan']?'':'disabled'}}>{{$data['jumlah_kegiatan']?'RKPD FINAL':'Tidak Terdapat Data RKPD'}}</a>
	</div>
	<div class="col-md-4">
		<h5><i class="fa fa-file"></i> TARGET NUWAS</h5>
		<?php 
			$target_nuwas=explode('->', $data['target_nuwas']);
			$do=explode('@', $data['file_kebijakan']!=null?$data['file_kebijakan']:'');
		?>
		@if(($target_nuwas[0]!=1)AND($target_nuwas[0]!=NULL))
		<a href="javascript:void(0)" class="btn btn-primary col-md-12" {{$target_nuwas[0]?'':'disabled'}}>{{$target_nuwas[0]?$target_nuwas[0].' - '.$target_nuwas[2]:''}}</a>
		@else
			<h5><b>MASIH BERUPA USULAN</b></h5>

		@endif
	</div>



</div>
<hr>
<h5 class="text-center">DOKUMEN PENDUKUNG </h5>
<hr>
<div class="row">
	@foreach($do as $d)
		@if($d!='')
			<div class="col-md-4">
			<h5><i class="fa fa-file"></i> DOKUMEN {{$d}}</h5>
			<a href="{{route('d.doc.list',['kode_daerah'=>$data['kode_daerah'],'jenis'=>$d])}}" class="btn btn-success">LIHAT DOKUMEN {{$d}}</a>
			</div>

		@endif
	@endforeach
</div>