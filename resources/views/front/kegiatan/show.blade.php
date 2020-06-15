@extends('adminlte::page')


@section('content_header')
    <div class="row bg-yellow">
        <div class="col-md-12">
        	<h5 class="text-center text-dark"><b><i class="fa fa-users"></i> KEGIATAN TEAM NUWSP</b></h5>
    	</div>
    </div>
@stop

@section('content')
	<div class="row">
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-12">
		            <h4 class="text-center text-uppercase ">{{$data->title}}</h4>
		        </div>
				<div class="col-md-12">
					<div class="box">
						<div class="box-header with-border">
							<img src="{{asset($data->path)}}" class="img-responsive">
						</div>
						<div class="box-body">
							{!!$data->content!!}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
		     <h4 class="text-center text-uppercase ">KEGIATAN LAIN</h4>
			<div class="row">
				<div class="col-md-12">
					<div class="box">
					<div class="box-body">
						<ul class="products-list product-list-in-box"  id="info_bangda">
			               	@foreach($data_lain as $l)
			               		 <li class="item">
			                  <div class="product-img">
			                    <img src="{{asset($l->path)}}" alt="Product Image">
			                  </div>
			                  <div class="product-info">
			                    <a href="{{route('k.show',['id'=>$l->id])}}" class="product-title">{{$l->title}}
			                    <span class=" pull-right">{{Carbon\Carbon::parse($l->created_at)->format('d F Y H:i A')}}</span></a>
			                    <span class="product-description">
			                       {!!$l->content!!}
			                     </span>
			                  </div>
			                </li>

			               	@endforeach

			              </ul>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>

@stop