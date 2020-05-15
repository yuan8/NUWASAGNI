
@extends('adminlte::page')

@section('content_header')
     <div class="row">
    	<div class="col-md-12">
    		<h3 class="text-uppercase text-center"> {{$program->uraian}}</h3>
    	</div>
    </div>
   
    <?php
?>
@stop

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box-warning box">
				<div class="box-body">
					<table class="table table-bordered">
							<thead>
								<tr>
									<th>No.</th>
									<th>Indikator</th>
								</tr>
							</thead>
						<tbody>
							@foreach($data as $key=> $d)
								<tr>
									<td>{{$key+1}}</td>
									<td>{{$d->indikator}}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


@stop