
@extends('adminlte::page')

@section('content_header')
    
@stop

@section('content')
<style type="text/css">
.swiper-container {
    height: 400px;
   /* margin-left: -15px!important;
    margin-right: -15px!important;*/
    /*margin-top: -15px!important;*/
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
.swiper-title-slide{
  position: absolute;
  background: rgba(255,255,255,0.3);
  width: 100%;
  text-align: center;
  font-weight: bold;
  padding: 15px;

}
.swiper-content-slide{
  position: absolute;
  background: rgba(255,255,255,0.3);
  width: 100%;
  text-align: center;
  font-weight: bold;
  bottom: 0px;
  padding: 15px;
}

.box-content-img .content-dom a,.box-content-img .content-dom small{
  color:#fff;
  width:100%;
}

</style>
<div class="row bg-yellow" style="margin-top: -15px;">
    <div class="col-md-12">
        <h5 class="text-center text-dark"><b><i class="fa fa-home"></i> BERANDA</b></h5>
    </div>
</div>
<div class="row no-gutter bg-default">
    <div class="col-md-8 bg-navy" id="team_nuswp">
        <h5 class="text-center  "><b>KEGIATAN TEAM NUWSP</b></h5>
        <hr style="margin-bottom: 0px;">
        <div class="swiper-container " >
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper text-dark">
                <!-- Slides -->
               @if($album!=[])
                @foreach($album as $a)

                 <div class="swiper-slide">
                 <a href="">
                        <img src="{{url($a->path)}}" class="img-responsive">
                       <div class="swiper-title-slide">
                        <h5 class="text-dark">{{$a->title}}</h5>
                      </div>
                      <div class="swiper-content-slide">
                        <h5 class="text-dark">{{$a->content}}</h5>
                      </div>
                 </a>
                </div>
                @endforeach
               @else
                 <div class="swiper-slide">
                  <img src="{{asset('storage/dokumentasi_foto/1.jpg')}}" class="img-responsive">
                 
                </div>

               @endif
               

            </div>
            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>

            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

            <!-- If we need scrollbar -->
            <div class="swiper-scrollbar"></div>
            </div>
        
    </div>

<div class="col-md-4 text-dark bg-default" >
    <h5 class="text-center"><b>INFO SEPUTAR BANGDA</b></h5>
    <hr>
    <div style="overflow-y:scroll;">
            <ul class="products-list product-list-in-box " style="margin-left: 10px; width:calc(100% - 20px)" id="info_bangda">
                <li class="item">
                  <div class="product-img">
                    <img src="{{asset('storage/dokumentasi_foto/1.jpg')}}" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Lorem ipsum  
                      <span class=" pull-right">11/12/2019</span></a>
                    <span class="product-description">
                         Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                         tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                         quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                         consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                         cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                         proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </span>
                  </div>
                </li>
              
              </ul>

        <div style="position: absolute; bottom:0; width:100%; " class="bg-navy text-center">
            <a href="" class="btn btn-warning btn-xs text-dark"><b>TAMPILKAN INFO LEBIH BANYAK</b></a>
        </div>
    </div>
    
</div>

</div>
<div class="row ">
    <div style="border-top:3px solid #222;">
    <ul  class="nav nav-tabs bg-yellow ">
        <li class="active"><a data-toggle="tab" href="#home" class="text-dark" aria-expanded="true"><b>SEMUA</b></a></li>
        <li class=""><a data-toggle="tab" href="#menu1" class="text-dark" aria-expanded="false"><b>TAHUN {{HP::fokus_tahun()}}</b></a></li>
        <li class=""><a data-toggle="tab" href="#menu2" class="text-dark" aria-expanded="false"><b>TAHUN {{HP::fokus_tahun()+1}}</b></a></li>
    </ul>

    <div class="tab-content">
    <div id="home" class="tab-pane fade active in">
        <div id="map_index" >
        
        </div>  
    </div>
    <div id="menu1" class="tab-pane fade">
       <div id="map_index_1" >
        
        </div>  
    </div>
    <div id="menu2" class="tab-pane fade">
        <div id="map_index_2" >
        
        </div>  
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


<hr>



@stop

@section('js')
<script type="text/javascript" src="{{asset('L_MAP/ind/ind.js')}}"></script>
<script type="text/javascript" src="{{asset('L_MAP/ind/kota.js')}}"></script>

<script type="text/javascript">
    $(function(){
        var team_nuswp=$('#team_nuswp').height();
        $('#info_bangda').css('height',(team_nuswp-81));


    });

    $(function(){
        API_CON.post('{{route('web_api.nuwas.daerah.target.2')}}').then(function(res){
        var data_map_source=res.data;

        var tahun_semua=[{
                  
                        name: 'Poligon',
                        type:'map',
                        mapData:Highcharts.maps['ind_kota'],
                        data:[],
                        
                },
                {
                        name: 'Stimultan',
                        type:'map',
                        color:'green',
                        joinBy:['id','kode_daerah'],
                         allAreas: false,
                        mapData:Highcharts.maps['ind_kota'],
                        data:data_map_source.all.stimultan,
                        
                },
                {
                        name: 'Pendamping',
                        type:'map',
                        color:'yellow',
                        joinBy:['id','kode_daerah'],
                         allAreas: false,
                        mapData:Highcharts.maps['ind_kota'],
                        data:data_map_source.all.pendamping,
                        
                },

        ];

        var tahun_{{HP::fokus_tahun()}}=[
                  {
                        name: 'Poligon',
                        type:'map',
                        mapData:Highcharts.maps['ind_kota'],
                        data: [],
                        
                },
                 {
                        name: 'Stimultan',
                        type:'map',
                        color:'green',
                        joinBy:['id','kode_daerah'],
                         allAreas: false,
                        mapData:Highcharts.maps['ind_kota'],
                        data:data_map_source.t{{HP::fokus_tahun()}}.stimultan,
                        
                },
                {
                        name: 'Pendamping',
                        type:'map',
                        color:'yellow',
                        joinBy:['id','kode_daerah'],
                         allAreas: false,
                        mapData:Highcharts.maps['ind_kota'],
                        data:data_map_source.t{{HP::fokus_tahun()}}.pendamping,
                        
                },

        ];

        var tahun_{{1+HP::fokus_tahun()}}=[
                  {
                        name: 'Poligon',
                        type:'map',
                        mapData:Highcharts.maps['ind_kota'],
                        data: [],
                        
                },
                 {
                        name: 'Stimultan',
                        type:'map',
                        color:'green',
                        joinBy:['id','kode_daerah'],
                         allAreas: false,
                        mapData:Highcharts.maps['ind_kota'],
                        data:data_map_source.t{{1+HP::fokus_tahun()}}.stimultan,
                        
                },
                {
                        name: 'Pendamping',
                        type:'map',
                        color:'yellow',
                        joinBy:['id','kode_daerah'],
                         allAreas: false,
                        mapData:Highcharts.maps['ind_kota'],
                        data:data_map_source.t{{1+HP::fokus_tahun()}}.pendamping,
                        
                },

        ];

        // for(var i in data_map_source.t{{HP::fokus_tahun()}}){
           
        //     tahun_{{HP::fokus_tahun()}}.push({
        //         name:data_map_source.t{{HP::fokus_tahun()}}.i ,
        //         type:'map',
        //         mapData:Highcharts.maps['ind_kota'],
        //         data: [],
        //     });


        // }


        Highcharts.mapChart('map_index', {
                chart: {
                    height:500,
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                },
                credits: {
                    enabled: false
                },
                legend: {
                    title: {
                        text: 'JENIS DUKUNGAN',
                        align: 'center',
                    },
                    borderWidth: 1,
                    backgroundColor: 'white'
                },
                mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'bottom',
                        horizontalAlign: 'right'
                    }
                },
                title: {
                    text: 'NATIONAL URBAN WATER SUPPLY PROGRAM',
                    style:{
                        color:'#fff'
                    }
                },
                subtitle: {
                    text: 'PETA DAERAH NUWSP {{HP::fokus_tahun()}} - {{HP::fokus_tahun()+1}}',
                     style:{
                        color:'#fff'
                    }
                },
                series: tahun_semua
                });
        
    
            Highcharts.mapChart('map_index_1', {
                chart: {
                    height:500,
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                },
                credits: {
                    enabled: false
                },
                legend: {
                    title: {
                        text: 'JENIS DUKUNGAN',
                        align: 'center',
                    },
                    borderWidth: 1,
                    backgroundColor: 'white'
                },
                mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'bottom',
                        horizontalAlign: 'right'
                    }
                },
                title: {
                    text: 'NATIONAL URBAN WATER SUPPLY PROGRAM',
                    style:{
                        color:'#fff'
                    }
                },
                subtitle: {
                    text: 'PETA DAERAH NUWSP {{HP::fokus_tahun()}} ',
                     style:{
                        color:'#fff'
                    }
                },
                series: tahun_{{HP::fokus_tahun()}}
                });
        
            Highcharts.mapChart('map_index_2', {
                chart: {
                    height:500,
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                },
                credits: {
                    enabled: false
                },
                legend: {
                    title: {
                        text: 'JENIS DUKUNGAN',
                        align: 'center',
                    },
                    borderWidth: 1,
                    backgroundColor: 'white'
                },
                mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'bottom',
                        horizontalAlign: 'right'
                    }
                },
                title: {
                    text: 'NATIONAL URBAN WATER SUPPLY PROGRAM',
                    style:{
                        color:'#fff'
                    }
                },
                subtitle: {
                    text: 'PETA DAERAH NUWSP {{HP::fokus_tahun()+1}}',
                     style:{
                        color:'#fff'
                    }
                },
                series: tahun_{{HP::fokus_tahun()+1}}
            });
        
        });
    });




   // Instantiate the map





</script>

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
         autoplay: {
         delay: 2000,
        },


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

