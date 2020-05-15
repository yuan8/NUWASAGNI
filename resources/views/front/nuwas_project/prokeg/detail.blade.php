

@extends('adminlte::page')


@section('content_header')
<h5 class="text-center">PROGRAM KEGIATAN {{$daerah->nama_daerah}} TAHUN {{HP::fokus_tahun()}}</h5>
@stop


@section('content')
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
							<th colspan="2">INDIKATOR DAERAH</th>
							<th colspan="2">INDIKATOR PUSAT</th>
							<th rowspan="2">RPJMN</th>

						</tr>
						<tr>
							<th>NAMA</th>
							<th>TARGET</th>
							<th>NAMA</th>
							<th>TARGET</th>

						</tr>
					</thead>
					<tbody>

						<?php 
							$id_k=0;
							$id_p=0;
							$id_ind_p=0;
							$id_ind_k=0;

							$id_indp_ps=0;

						?>

						@foreach($data as $d)
							@if((!empty($d->id_p))AND($id_p!=$d->id_p))
							<tr>
								<td class="text-right"><b>P.{{$d->kode_p}}</b></td>
								<td>{{$d->nama_p}}</td>
								<td colspan="7"></td>
							</tr>
							<?php $id_p=$d->id_p; ?>
							@endif
							@if((!empty($d->id_ind_p))AND($id_ind_p!=$d->id_ind_p))
							<tr>
								<td colspan="4" class="text-right"><b>IP.{{$d->id_ind_p}}</b></td>
								<td><b>(IP)</b> {{$d->ind_p_nama}}</td>
								<td>{{$d->ind_p_target_1}} {{$d->ind_p_satuan}} {{!empty($d->ind_p_target_2)?' - '.$d->$d->ind_p_target_2.' '.$d->ind_p_satuan:''}}</td>
								<td colspan="3"></td>
							</tr>
							<?php $id_ind_p=$d->id_ind_p; ?>
							@endif

							@if((!empty($d->id_k))AND($id_k!=$d->id_k))
							<tr>
								<td colspan="2" class="text-right"><b>K.{{$d->kode_k}}</b></td>
								<td>{{$d->nama_k}}</td>
								<td>{{number_format($d->anggaran_k,0,'.',',')}}</td>
								<td colspan="5"></td>
							</tr>
							<?php $id_k=$d->id_k; ?>
							@endif
						
							@if((!empty($d->id_ind_k))AND($id_ind_k!=$d->id_ind_k))
							<tr>
								<td colspan="4" class="text-right"><b>IK.{{$d->id_ind_k}}</b></td>
								<td><b>(IK)</b> {{$d->ind_k_nama}}</td>
								<td>{{$d->ind_k_target_1}} {{$d->ind_k_satuan}} {{!empty($d->ind_k_target_2)?' - '.$d->$d->ind_k_target_2.' '.$d->ind_k_satuan:''}}</td>
								
								<td colspan="3"></td>
							</tr>
							<?php $id_ind_k=$d->id_ind_k; ?>
							@endif
							
							@if((!empty($d->id_indp_ps))AND($id_indp_ps!=$d->id_indp_ps))
							<tr>
								<td colspan="6" class="text-right"><b>IPS.{{$d->id_indp_ps}}</b></td>
								<td><b>(I{{$d->ind_ps_jenis}})</b> {{$d->ind_ps_nama}}</td>
								<td>{{$d->ind_ps_target_1}} {{$d->ind_ps_satuan}} {{!empty($d->ind_ps_target_2)?' - '.$d->$d->ind_ps_target_2.' '.$d->ind_ps_satuan:''}}</td>
								<td colspan="">
									<p>{{$d->nama_pn}}</p>
									<p>{{$d->nama_pp}}</p>
									<p>{{$d->nama_kp}}</p>
									<p>{{$d->nama_propn}}</p>
									<p>{{$d->nama_pronas}}</p>




								</td>
							</tr>
							<?php $id_indp_ps=$d->id_indp_ps; ?>
							@endif

							@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


@stop