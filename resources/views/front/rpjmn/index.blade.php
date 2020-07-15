

@extends('adminlte::page')

@section('content_header')
    <div style="width: 100%; float: left;">
    	<div class=" text-center header-page">
    		<p class="text-uppercase">RPJMN BERLAKU TAHUN {{$rpjmn_nama}}</p>
    	</div>
    </div>
   

@stop

@section('content')

<div class="table-responsive t">
	<table class="table table-condensed">
		<thead>
			<tr>
				<th rowspan="2">PN</th>
				<th rowspan="2">PP</th>
				<th rowspan="2">KP</th>
				<th rowspan="2">PROPN</th>
				<th rowspan="2">PRONAS</th>
				<th rowspan="2">INDIKATOR</th>
				<th colspan="6">TARGET</th>
			</tr>
			<tr>
				<th>ANGGRAAN</th>
				<th>TARGET TAHUN</th>
				<th>TARGET TAHUN</th>
				<th>TARGET TAHUN</th>
				<th>TARGET TAHUN</th>
				<th>TARGET TAHUN</th>


			</tr>
		</thead>
		<tbody>
			@php
				$id_pn=null;
				$id_pp=null;
				$id_kp=null;
				$id_propn=null;
				$id_pronas=null;
				$id_ind=null;

			@endphp
			@foreach($data as $d)
				@if($id_pn!=$d->id_pn)
					<tr>
						<td>{{$d->urai_pn}}</td>
					</tr>
					@php
						$id_pn=$d->id_pn;
					@endphp
				@endif
				@if($id_pp!=$d->id_pp)
					<tr>
						<td></td>
						<td>{{$d->urai_pp}}</td>
					</tr>
					@php
						$id_pp=$d->id_pp;
					@endphp
				@endif
				@if($id_kp!=$d->id_kp)
					<tr>
						<td colspan="2"></td>
						<td>{{$d->urai_kp}}</td>
					</tr>
					@php
						$id_kp=$d->id_kp;
					@endphp
				@endif
				@if($id_propn!=$d->id_propn)
					<tr>
						<td colspan="3"></td>
						<td>{{$d->urai_propn}}</td>
					</tr>
					@php
						$id_propn=$d->id_propn;
					@endphp
				@endif
				@if($id_pronas!=$d->id_pronas)
					<tr>
						<td colspan="4"></td>
						<td>{{$d->urai_pronas}}</td>
					</tr>
					@php
						$id_pronas=$d->id_pid_pronasropn;
					@endphp
				@endif

			@endforeach
		</tbody>
	</table>
</div>


@stop