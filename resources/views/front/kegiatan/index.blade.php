@extends('adminlte::page')


@section('content_header')
    <div class="row bg-yellow">
        <div class="col-md-12">
        	<h5 class="text-center text-dark"><b><i class="fa fa-users"></i> KEGIATAN TEAM NUWSP</b></h5>
    	</div>
    </div>
@stop


@section('content')

  @foreach($data as $d)
    <div class="col-md-6">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
          {{--       <img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Image"> --}}
                <span class="description">{{Carbon\Carbon::parse($d->created_at)->format('d F Y h AM')}}</span>
              </div>
              <!-- /.user-block -->
             
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <a href="{{route('k.show',['id'=>$d->id])}}">
              <h5><b>{{$d->title}}</b></h5>
                <img class="img-responsive pad" src="{{asset($d->path)}}" alt="Photo">
             </a>

              <p>{{$d->meta_content}}</p>
          
            </div>
           
          </div>
          <!-- /.box -->
        </div>


  @endforeach
@stop