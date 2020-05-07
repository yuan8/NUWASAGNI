@extends('adminlte::page')


@section('content_header')
     <div class="row">
    	<div class="col-md-12">
    		<h3 class="text-uppercase text-center">PROGRAM KEGIATAN {{$daerah->nama}}</h3>
    	</div>
    </div>

    <?php
?>
@stop

@section('content')
	<div class="row">
		@if($catatan)
		<div class="col-md-12">
			<div class="panel">

				<div class="panel-body">
					<div class="text-center">
						<b>{{strtoupper($catatan->name)}} - {{\Carbon\Carbon::parse($catatan->updated_at)->format('d F Y')}}</b>
						<hr>
					</div>
						<b>Note</b>
					<p>{{$catatan->catatan}}</p>
				</div>
			</div>
		</div>
		<hr>

		@endif
		<div class="col-md-12">

			<div class="form-group">
				<label>FILTER URUSAN</label>
				<form action="{{url()->current()}}" method="get">
					<select class="form-control use-select-2" name="urusan[]"  onchange="$(this).parent().submit()" multiple="">
					<option value="">-SEMUA-</option>
					<?php foreach ($urusan as $key => $u): ?>
						<option value="{{$u->id}}" {{isset($_GET['urusan'])?in_array($u->id,$_GET['urusan'])?'selected':'':''}}>{{$u->nama}}</option>
					<?php endforeach ?>
				</select>
				</form>
			</div>
			<div class="box box-primary">
				<div class="box-body table-responsive ">
					<table class="table table-bordered table-fixed">
						<thead>
							<tr>
								<th>URUSAN</th>
								<th>SUB URUSAN</th>
								<th>PROGRAM</th>
								<th>KEGIATAN</th>
								<th>ANGGARAN</th>

								<th>INDIKATOR</th>
								<th>TARGET</th>

							</tr>
						</thead>
						<tbody >
							<?php
							$idb=0;
							$idsb=0;
							$idp=0;
							$idinp=0;
							$idk=0;
							$idink=0;

							?>
							@foreach($data as $d)
								@if(($idb!=$d->id_urusan) && ($d->id_urusan!=null))
								<tr class="bg  bg-primary cls linke-link" data-show="true" trgr=".u{{$d->id_urusan}}" onclick="collapsing(this)">
									<td colspan="7" class="cur">{{$d->nama_urusan}}</td>
								</tr>

								<?php  $idb=$d->id_urusan;?>
								<?php  $idsb=null?>

								@endif

								@if(($idsb!=$d->id_sub_urusan) && ($d->id_sub_urusan))
								<tr class="bg bg-success linke-link cls u{{$idb}} coll" data-show="true" trgr=".su{{$d->id_sub_urusan}}" onclick="collapsing(this)">
									<td></td>
									<td colspan="6" class="cur">{{$d->nama_sub_urusan}}</td>
								</tr>

								<?php  $idsb=$d->id_sub_urusan;?>
								@endif

								@if(($idp!=$d->id_program) && ($d->id_program!=null))
								<tr class="bg bg-warning cls u{{$idb}} su{{$idsb}}">
									<td colspan="2"></td>
									<td colspan="5">{{$d->nama_program}}</td>
								</tr>

								<?php  $idp=$d->id_program;?>
								@endif

								@if(($idinp!=$d->id_ind_p) && ($d->id_ind_p!=null))
								<tr class="cls u{{$idb}} su{{$idsb}}">
									<td colspan="5"></td>
									<td><b>(IP)</b> {{$d->nama_ind_p}}</td>
									<td>{{$d->target_ind_p}} {{$d->satuan_ind_p}}</td>
								</tr>

								<?php  $idinp=$d->id_ind_p;?>
								@endif

								@if($idk!=$d->id_kegiatan && ($d->id_kegiatan!=null))
								<tr class="cls u{{$idb}} su{{$idsb}}">
									<td colspan="3"></td>
									<td>{{$d->nama_kegiatan}}</td>
									<td>{{$d->anggaran}}</td>
								</tr>

								<?php  $idk=$d->id_kegiatan;?>
								@endif


								@if(($idink!=$d->id_ind_k)&&($d->id_ind_k!=null))
								<tr class="cls u{{$idb}} su{{$idsb}}">
									<td colspan="5"></td>
									<td><b>(IK)</b> {{$d->nama_ind_k}}</td>
									<td>{{$d->target_ind_k}} {{$d->satuan_ind_k}}</td>

								</tr>

								<?php  $idink=$d->id_ind_k;?>
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>




@stop

@section('js')
<style type="text/css">
.linke-link:hover {
	 cursor:pointer;
}
</style>
<script type="text/javascript">
	$('.use-select-2').select2();



	function collapsing(dom){

		if($(dom).attr('data-show')=='true'){
			var idd=$(dom).attr('trgr');
			$(idd).css('visibility','collapse');
			$(dom).find('td.cur').append('<span class="caret"></span>');

			$(dom).attr('data-show','false');

		}else{
			var idd=$(dom).attr('trgr');
			$(idd).css('visibility','visible');
			$(dom).find('td span.caret').remove();
			$(idd+' td span.caret').remove();

			$(dom).attr('data-show','true');
		}

	}

  setTimeout(function(){
		$('.coll').click();
	},500);
</script>

@stop


@section('right-sidebar')


@stop
