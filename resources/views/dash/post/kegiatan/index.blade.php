@extends('adminlte::dash')


@section('content_header')

<script src="{{url('js/app.js')}}"></script>
	<h1>KEGIATAN TAHUN {{HP::fokus_tahun()}}</h1>

@stop

@section('content')

	<div class="box box-primary">
		<div class="box-header">
		     <form action="{{url()->current()}}" method="get">

			<div class="form-group">
			<div class="input-group">
		     	 <input type="text" class="form-control" name="q" placeholder="Search for..." value="{{$q}}">
		      	<span class="input-group-btn">
		        	<button type="submit" class="btn btn-default" type="button">Go!</button>
		      	</span>
		    </div><!-- /input-group -->
		     </form>

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
					
					<th style="min-width: 150px;">ACTION</th>

				</tr>
			</thead>
			<tbody>
				@foreach($data as $d)
					<tr>
						<td>
							<input type="checkbox" name="" {{$d->sticky?"checked":''}} onchange="sticky({{$d->id}},event)">
						</td>
						<td style="max-width:50px;">
							@if($d->path)
								<img src="{{asset($d->path)}}" class="img-responsive" style="max-width: 50px;">
							@endif
						</td>
						<td>
							{{$d->title}}
						</td>
						<td>
							{!!substr(strip_tags($d->meta_content),0,300).'...'!!}
						</td>
						<td class="btn-group">
							<a href="{{route('d.post.kegiatan.show',['id'=>$d->id])}}" class="btn btn-xs btn-warning">Update</a>
							<a href="{{route('k.show',['id'=>$d->id])}}" target="_blank" class="btn btn-xs btn-info">View</a>
							<button class="btn btn-xs btn-danger">Delete</button>

						</td>

					</tr>
				@endforeach	
			</tbody>
			</table>

			{{$data->links()}}
		</div>
	</div>



@stop

@section('js')

<script type="text/javascript">
	function sticky(id,event){
		 event.preventDefault();
		API_CON.post('{{route('d.post.kegiatan.sticky')}}',{'id':id}).then(function(res){

			if(res.data.status){
				$(this).attr('checked');
			}else{
				$(this).removeAttr('checked');
			}
		});
	}

</script>	

@stop