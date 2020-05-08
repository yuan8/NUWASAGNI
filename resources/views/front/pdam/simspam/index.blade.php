@extends('adminlte::page')


@section('content_header')

    <h1 class="text-center">KONDISI JARINGAN PERPIPAAN  {{strtoupper($pdam->nama_pdam)}}  {{HP::fokus_tahun()}}</h1>
@stop

@section('content')

<div class="row no-gutter bg-default-g text-dark">
		<div class="col-md-3">
			<div class="box box-primary">
				<div class="box-body">
					</div>
				</div>
			</div>
		</div>

@stop