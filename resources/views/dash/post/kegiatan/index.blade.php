@extends('adminlte::dash')


@section('content_header')

<script src="{{url('js/app.js')}}"></script>
	<h1>KEGIATAN TAHUN {{HP::fokus_tahun()}}</h1>

@stop

@section('content')

	<div class="box box-primary">
		<div class="box-header">
			<div class="form-group">
				<div class="input-group">
				<input type="text" name="q" class="form-control">

				</div>
			</div>
		</div>
		<div class="box-body">
			<table class="table table-bordered">
			<thead>
					<tr>
					<th>STICKY</th>
					<th>
						THUMBNAIL
					</th>
					<th>
						JUDUL
					</th>
					<th>
						CONTENT
					</th>
					<th>
						FILE
					</th>
					<th>ACTION</th>

				</tr>
			</thead>
			<tbody>
				@foreach($data as $d)
					<tr>
						<td></td>
						<td style="max-width:50px;">
							@if($d->path)
								<img src="{{asset($d->path)}}" class="img-responsive" style="max-width: 50px;">
							@endif
						</td>
						<td>
							{{$d->title}}
						</td>
						<td>
							{!!substr(strip_tags($d->content),0,300).'...'!!}
						</td>
						<td>
							
						</td>
						<td></td>

					</tr>
				@endforeach	
			</tbody>
			</table>
		</div>
	</div>

@stop