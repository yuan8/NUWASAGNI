@extends('adminlte::page')


@section('content_header')
<div class="row  text-center">
		<div class="col-md-12 bg ">
			<h1><b class="">NOMENKLATUR BERLAKU {{HP::fokus_tahun()}}</b></h1>
			<h4>AIR MINUM</h4>
		</div>
	</div>
    
@stop

@section('content')
	<div class="box box-warning">
		<div class="box-body table-responsive">
			<table class=" table table-condensed table-striped table-hover table-bordered">
				<thead>
					<th>
						KODE
					</th>
					<TH>BIDANG</TH>
					<TH>SUB BIDANG</TH>
					<TH>PROGRAM</TH>
					<TH>KEGIATAN</TH>
					<TH>SUBKEGIATAN</TH>



				</thead>

				<tbody>
					@php
						$urusan=null;
						$bidang_urusan=null;
						$program=null;
						$kegiatan=null;
						$sub_kegiatan=null;

					@endphp
					@foreach($data as $d)
						@if($urusan!=$d->urusan)
							<tr>
							<td>{{$d->urusan}}</td>
							<td>{{$d->nama_urusan}}</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>

							</tr>
							@php
								$urusan=$d->urusan;
							@endphp
						@endif

						@if($bidang_urusan!=$d->bidang_urusan)
							<tr>
							<td>{{$d->urusan.'.'.$d->bidang_urusan}}</td>
							<td></td>
							<td>{{$d->nama_urusan}}</td>
							<td></td>
							<td></td>
							<td></td>

							</tr>
							@php
								$bidang_urusan=$d->bidang_urusan;
							@endphp
						@endif

						@if($program!=$d->program)
							<tr>
							<td>{{$d->kode}}</td>
							<td></td>
							<td></td>

							<td>{{$d->nomenklatur}}</td>
							<td></td>
							<td></td>

							</tr>
							@php
								$program=$d->program;
							@endphp
						@endif

						@if($kegiatan!=$d->kegiatan)
							<tr>
							<td>{{$d->kode}}</td>
							<td></td>
							<td></td>
							<td></td>


							<td>{{$d->nomenklatur}}</td>
							<td></td>

							</tr>
							@php
								$kegiatan=$d->kegiatan;
							@endphp
						@endif
						@if($sub_kegiatan!=$d->sub_kegiatan)
							<tr>
							<td>{{$d->kode}}</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>


							<td>{{$d->nomenklatur}}</td>
							</tr>
							@php
								$sub_kegiatan=$d->sub_kegiatan;
							@endphp
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop