@extends('adminlte::dash')


@section('content_header')
    @if($jenis==1)
    <h1>OUTPUT BERUPA MAP UNTUK NUWSP TAHUN {{HP::fokus_tahun()}}</h1>
    @else
    <h1>OUTPUT BERUPA ARTIKEL /DOKUMEN UNTUK NUWSP TAHUN {{HP::fokus_tahun()}}</h1>

    @endif

@stop

@section('content')
    <div class="btn-group" style="margin-bottom: 10px;">
    @if($jenis==1)
         <a  href="{{url('/storage/output/template_map_data.xlsm')}}"  class="btn btn-xs btn-primary" download="">DOWNLOAD TEMPLATE OUTPUT TEMA MAP</a>
         <a  href="{{route('d.out.map.upload')}}"  class="btn btn-xs btn-success">UPLOAD OUTPUT TEMA MAP</a>
    @else
        <a  href="{{route('d.out.post.create')}}"  class="btn btn-xs btn-primary" >TAMBAH OUTPUT  ARTIKEL</a>
        <a  href="{{route('d.out.dokumen.create')}}"  class="btn btn-xs btn-success">TAMBAH OUTPUT DOKUMEN</a>

    @endif


    </div>

    <div class="box box-primary">
    	<div class="box-body">
    		<table class="table-bordered table">
    			<thead>
    				<tr>
    					<th>
    						JUDUL
    					</th>
                        @if($jenis==2)
                            <th>CONTENT</th>     
                        @endif
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
                             @if($jenis==2)
                                <td>{{$d->meta_content}}</td>     
                            @endif

    						<td>
    							{{Carbon\Carbon::parse($d->updated_at)->format('d F Y')}}
    							<br>
    							<b>{{$d->nama_user}}</b>
    						</td>
    						<td>
    							<div class="btn-group">
        							<a href="" class="btn btn-xs btn-primary">Detail</a>
        							<a href="{{url($d->file_path)}}" class="btn btn-xs btn-warning"target="_blank">View</a>
                                    <button class="btn btn-xs btn-danger">Hapus</button>
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