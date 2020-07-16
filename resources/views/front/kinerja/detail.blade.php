@extends('adminlte::page')


@section('content_header')
<div class="text-center">
	  <h4 class=" ">PROGRAM KEGIATAN  {{$daerah->nama_daerah}} / {{$daerah->nama_daerah}} {{HP::fokus_tahun()}}</h4>
  		<p>
  			@php
  				switch($tipe){
  					case 1:
  					$jenis_i='DOKUMEN KEBIJAKAN';
  					break;	
  					case 2:
  					$jenis_i='PEMBANGUNAN SR';
  					break;	
  					case 3:
  					$jenis_i='PEMETAAN LOKASI';
  					break;	
  				}

  			@endphp

  		INDIKATOR TERKAIT {{$jenis_i}}</p>
</div>
@stop

@section('content')
<style type="text/css">
	table tr th,table tr td{
		font-size: 10px;
	}
</style>
<div class="row no-gutter">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body table-responsive">
				<table class="table-condensed table table-bordered">
					<thead>
						<tr>
							<th rowspan="2">KODE</th>
							<th rowspan="2">NAMA PROGRAM</th>
							<th  rowspan="2">NAMA KEGIATAN</th>
							<th  rowspan="2">ANGGARAN</th>
							<th colspan="3">INDIKATOR DAERAH</th>

						</tr>
						<tr>
							<th>NAMA</th>
							<th>TARGET</th>
							
						</tr>
					</thead>
					<tbody>
						<?php 
							$id_k=0;
							$id_p=0;
							$id_i=0;
							

						?>
						<?php foreach ($data as $key => $d): ?>
							@if((!empty($d->id_p))AND($id_p!=$d->id_p))
							<tr>
								<td class="text-right"><b>{{$d->kode_p}}</b></td>
								<td>{{$d->urai_p}}</td>
								<td colspan="8"></td>
							</tr>
							<?php $id_p=$d->id_p; ?>
							@endif
						
							@if((!empty($d->id_k))AND($id_k!=$d->id_k))
							<tr>
								<td colspan="2" class="text-right"><b>{{$d->kode_k}}</b></td>
								<td>{{$d->urai_k}}</td>
								<td>{{number_format($d->anggaran_k,0,'.',',')}}</td>
								<td colspan="6"></td>
							</tr>
							<?php $id_k=$d->id_k; ?>
							@endif
						
							@if((!empty($d->id_i))AND($id_i!=$d->id_i))
							<tr>
								<td colspan="4" class="text-right"><b>IK.{{$d->kode_i}}</b></td>
								<td><b>(IK)</b> {{$d->urai_i}}</td>
								<td>{{$d->target_i}} {{$d->satuan_i}}</td>
								
							</tr>
							<?php $id_i=$d->id_i; ?>
							@endif
							
						

							
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@stop