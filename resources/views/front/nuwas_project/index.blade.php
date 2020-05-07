@extends('adminlte::page')


@section('content_header')
<div class="row  text-center">
		<div class="col-md-12 bg bg-yellow">
			<b class="text-dark">NUWAS PROJECT {{HP::fokus_tahun()}}</b>
		</div>
	</div>
    
@stop

@section('content')
<style type="text/css">
.swiper-container {
    height: 500px;
    margin-left: -15px!important;
    margin-right: -15px!important;
    margin-top: -15px!important;
    background: #f1f1f1;
}

.swiper-container .swiper-slide img{
	position: absolute;
	top:-50%;
	bottom:-50%;

}
.swiper-container-backpoint{
	overflow: hidden;
}

.panel{
	border-radius: 0px;
}
.box-content-img {
	height: 100%;
	overflow: hidden!important;
	text-align: center;
	background-repeat: no-repeat;
	background-position: center;
	background-size: auto 100%;
}
.box-content-img img:after{

} 
.box-content-img .back-op{
	height: 100%!important;
	width: auto!important;
	left:0;
	right: 0;
	top:0;
	bottom: 0;
	margin: auto;
	position: absolute;
	overflow: hidden;
	z-index: 1;
  background: #474461c2;

}

.box-content-img .back-op:hover{
  background: #252433c2;

}

.box-content-img .content-dom{
  position: absolute;
  bottom: 15px;
  width: 100%;
  z-index: 2;
  color:#fff!important;
  font-weight: bold;
  vertical-align: bottom;
  padding: 10px;

}
.box-content-img .content-dom a,.box-content-img .content-dom small{
  color:#fff;
  width:100%;
}

</style>

<div class="swiper-container row ">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide">
        	<img src="{{asset('storage/dokumentasi_foto/1.jpg')}}" class="img-responsive">
        </div>
        <div class="swiper-slide">
        	<img src="{{asset('storage/dokumentasi_foto/1.jpg')}}" class="img-responsive">
        	
        </div>
        <div class="swiper-slide">Slide 3</div>
    </div>
    <!-- If we need pagination -->
    <div class="swiper-pagination"></div>

    <!-- If we need navigation buttons -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

    <!-- If we need scrollbar -->
    <div class="swiper-scrollbar"></div>
</div>

<div class="row no-gutter text-dark">
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body text-center no-padding pt-15 bg bg-aqua">
                <i class="fa fa-tint"></i>
                <h5><b>TARGET DAERAH NUWAS</b></h5>
                <small>{{$target_nuwas}} DAERAH</small>
                <a href="" class="full-w btn btn-info btn-xs">Detail</a>
            </div>
        </div>
    </div>
      <div class="col-md-3">
        <div class="box box-warning ">
            <div class="box-body text-center no-padding pt-15 bg-yellow bg">
                <i class="fa fa-file"></i>
                <h5><b>MELAPORKAN DOK RKPD</b></h5>
                <small>0 DAERAH</small>

                <a href="" class="full-w btn btn-warning btn-xs">Detail</a>
            </div>
        </div>
    </div>
     <div class="col-md-3">
        <div class="box box-success ">
            <div class="box-body text-center no-padding pt-15 bg bg-green">
                <i class="fa fa-arrow-up"></i>
                <h5><b>PENINGKATAN KUALITAS PDAM </b></h5>
                <small>0 PDAM </small>

                <a href="" class="full-w btn btn-success btn-xs">Detail</a>
            </div>
        </div>
    </div>
     <div class="col-md-3">
        <div class="box box-danger">
            <div class="box-body text-center no-padding pt-15 bg bg-red">
                <i class="fa fa-wave-square"></i>
                <h5><b>SR</b></h5>
                <small>0%/5000</small>

                <a href="" class="full-w btn btn-danger btn-xs">Detail</a>
            </div>
        </div>
    </div>

 


</div>

<div class="row no-gutter" >
	<div class="col-md-4 panel text-dark" id="panel-main">
	<div class="panel-heading text-center">
		<b class="text-uppercase ">Publikasi World Bank Terkait Air Bersih</b>
	</div>
	<div class="panel-body " style="max-height: 250px; overflow-y: scroll;">
		

		<?php foreach ($public_world_bank as $key => $f): ?>

			
				<a href="{{url($f['url'])}}" target="_blank" class="text-left">{{$f['nama']}}</a>
				<hr>
			
			
		<?php endforeach ?>
		
	</div>
	<div class="panel-footer">
		<div class="pull-right">
			<div class="btn-group">
				<button class="btn btn-warning swiper-button-prev-backpoint" ><</button>
				<button class="btn btn-warning swiper-button-next-backpoint">></button>

			</div>
		</div>
	</div>
</div>
<div class=" panel col-md-8" style="background-image: url({{asset('backw1.gif')}}); background-size: 100% 100%;">
	<div class="panel-body">
		<div class="swiper-container-backpoint" >
		    <div class="swiper-wrapper">
		      	@foreach($output as $out)
              <div class="swiper-slide">

                  <div class="box-content-img " style="background-image: url({{$out->type==1?url('out_back_map.png'):''}})">
                  <div class="back-op">
                  <div class="content-dom">
                    <a href="{{url($out->file_path)}}" target="_blank">{{$out->title}}</a>
                    <br>
                    <small>{{Carbon\Carbon::now()->format('d M Y')}}</small>
                  </div>

                  </div>
                  
                </div>
              </div>
            @endforeach
            
		      
		    
		    </div>
		    <!-- Add Pagination -->
		    <div class="swiper-pagination"></div>
		  </div>
			</div>
		</div>

</div>
@stop


@section('js')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/swiper/cs.css')}}">
<script src="{{asset('vendor/swiper/js.js')}}"></script>
 <script>
 $('.swiper-container-backpoint').height($('#panel-main').height()-30);
  var mySwiper = new Swiper ('.swiper-container', {
    // Optional parameters
    direction: 'horizontal',
    loop: true,
     speed: 400,
     autoplay: {
    	delay: 2000,
  	},
	  cubeEffect: {
	    slideShadows: true,
	  },

    pagination: {
      el: '.swiper-pagination',
    },

    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },

    scrollbar: {
      el: '.swiper-scrollbar',
    },
  })
  </script>
    <script>
    setTimeout(function(){
    	var swiper = new Swiper('.swiper-container-backpoint', {
      slidesPerView: 1,
      spaceBetween: 10,
      // init: false,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,

      },
    loop: true,
   //   autoplay: {
   //  	delay: 2000,
  	// },


       navigation: {
	      nextEl: '.swiper-button-next-backpoint',
	      prevEl: '.swiper-button-prev-backpoint',
	    },

      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 10,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 10,
        },
        1024: {
          slidesPerView: 4,
          spaceBetween: 10,
        },
      }
    });
    },500);
  </script>
@stop