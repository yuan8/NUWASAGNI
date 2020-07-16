@extends('adminlte::page')


@section('content_header')
    <h1 class="text-center "><b></i> CAPAIAN SPM TAHUN {{HP::fokus_tahun()}}</b> <small style="color:#fff;">DATA FGD</small> </h1>
@stop


@section('content')
<div class="box box-warning">
	<div class="boc-body table-responsive">
		<table class="table  table-bordered table-condensed table-init-datatable table-striped ">
			<thead>
				<tr>
					<TH rowspan="2">KODE PEMDA</TH>
					<TH rowspan="2">NAMA DAERAH</TH>
					<TH rowspan="2">NAMA PROVINSI</TH>
					<TH rowspan="2">KONDISI PDAM FCR</TH>
					<TH rowspan="2" >TARIF RATA- RATA</TH>
					<TH rowspan="2" >HPP</TH>
					<TH rowspan="2">TARGET FCR</TH>
					<th colspan="{{HP::fokus_tahun()-2018}}">CAPAIAN SPM</th>

				</tr>
				<tr>
					@php
					for($i=HP::fokus_tahun();$i>=2019;$i--){
						@endphp

						<th>TAHUN {{$i}}</th>

					@php

					}


					@endphp
				</tr>
			</thead>
			<tbody>
				@foreach($data as $d)
					<tr>
						<td>{{$d->kodepemda}}</td>
						<td>{{$d->nama_daerah}}</td>
						<td>{{$d->nama_provinsi}}</td>
						<td class="{{trim($d->status)=='FCR'?'bg-green':'bg-yellow'}}">{{$d->status}}</td>
						<td>{{$d->tarif_rata2}}</td>
						<td>{{$d->hpp}}</td>
						<td>{{$d->target_FCR}}</td>

						@php
						for($i=HP::fokus_tahun();$i>=2019;$i--){
							@endphp

							<td>{{$d->$i}}</td>

						@php

						}


						@endphp

					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@stop