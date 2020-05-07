@extends('adminlte::dash')


@section('content_header')
    <h1>OUTPUT BERUPA MAP UNTUK NUWSP TAHUN {{HP::fokus_tahun()}}</h1>
@stop

@section('content')
    <div class="btn-group" style="margin-bottom: 10px;">
         <a  href="{{url('/storage/output/template_map_data.xlsm')}}"  class="btn btn-xs btn-primary" download="">DOWNLOAD TEMPLATE OUTPUT TEMA MAP</a>
         <a  href="{{route('d.out.map.upload')}}"  class="btn btn-xs btn-success">UPLOAD OUTPUT TEMA MAP</a>
    </div>
    <div class="box box-primary">
    	<div class="box-body">
    		<table class="table-bordered table">
    			<thead>
    				<tr>
    					<th>
    						NAMA
    					</th>
    					<th>
    						UPDATED AT
    					</th>
    					<th>
    						ACTION
    					</th>
    				</tr>
    			</thead>
    			<tbody>
    				@foreach($data as $d)
    					<tr>
    						<td>
    							{{$d->title}}
    						</td>
    						<td>
    							{{Carbon\Carbon::parse($d->updated_at)->format('d F Y')}}
    							<br>
    							<b>{{$d->nama_user}}</b>
    						</td>
    						<td>
    							<div class="btn-group">
    								<a href="" class="btn btn-xs btn-primary">Detail</a>
    							<a href="{{url($d->file_path)}}" class="btn btn-xs btn-warning"target="_blank">View</a>
    							</div>
    						</td>
    					</tr>
    				@endforeach
    			</tbody>
    				
    		</table>
            {{$data->links()}}
    	</div>
    </div>
@stop