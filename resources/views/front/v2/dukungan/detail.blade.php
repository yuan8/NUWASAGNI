@extends('adminlte::page')


@section('content_header')
<div class="row bg-navy">
	<div class="col-md-12">
		<h5 class="text-center">PROGRAM KEGIATAN {{$daerah->nama .' / '.$daerah->nama_provinsi}} TAHUN {{$tahun}}  - <small class="text-white">AIR MINUM</small></h5>
	</div>
</div>

@stop

@section('content')
	<div class="row no-gutter">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body table-responsive">
				<table class="table-condensed table table-bordered" id="data_table">
					<thead>
						<tr>
							<th rowspan="2">URUSAN</th>
							<th rowspan="2">SUB URUSAN</th>
							<th rowspan="2">KODE SKPD</th>
							<th rowspan="2">NAMA SKPD</th>
							<th rowspan="2">KODE (PROGRAM/KEGIATAN)</th>
							<th rowspan="2">NAMA PROGRAM</th>
							<th rowspan="2">NAMA KEGIATAN</th>
							<th rowspan="2">ANGGARAN</th>
							<th colspan="2">INDIKATOR DAERAH</th>
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
							$id_c=0;
							$id_i=0;
							$id_cndp_ps=0;

						?>
						<?php foreach ($data as $key => $d): ?>
							@if((!empty($d->id_p))AND($id_p!=$d->id_p))
							<tr>
								<td>{{$d->urai_u}}</td>
								<td>{{$d->urai_s}}</td>
								<td>{{$d->kodeskpd}}</td>
								<td>{{$d->uraiskpd}}</td>
								<td class="text-right"><b>P.{{$d->kode_p}}</b></td>
								<td>{{$d->urai_p}}</td>
								<td colspan="7"></td>
							</tr>
							<?php $id_p=$d->id_p; ?>
							@endif
							@if((!empty($d->id_c))AND($id_c!=$d->id_c))
							<tr>
								<td colspan="8" class="text-right"><b>IP.{{$d->id_c}}</b></td>
								<td><b>(IP)</b> {{$d->urai_c}}</td>
								<td>{{$d->target_c}} {{$d->satuan_c}}</td>
								<td colspan="3"></td>
							</tr>
							<?php $id_c=$d->id_c; ?>
							@endif

							@if((!empty($d->id_k))AND($id_k!=$d->id_k))
							<tr>
								<td colspan="6" class="text-right"><b>K.{{$d->kode_k}}</b></td>
								<td>{{$d->urai_k}}</td>
								<td>{{number_format($d->anggaran_k,0)}}</td>
								<td colspan="5"></td>
							</tr>
							<?php $id_k=$d->id_k; ?>
							@endif
						
							@if((!empty($d->id_i))AND($id_i!=$d->id_i))
							<tr>
								<td colspan="8" class="text-right"><b>IK.{{$d->id_i}}</b></td>
								<td><b>(IK)</b> {{$d->urai_i}}</td>
								<td>{{$d->target_i}} {{$d->satuan_i}}</td>
								<td colspan="3"></td>
								
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


