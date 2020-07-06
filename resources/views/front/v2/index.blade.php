
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
  background: rgba(255,255,255,0.8);
  width: 100%;
  text-align: center;
  font-weight: bold;
  padding: 15px;

}
.swiper-content-slide{
  position: absolute;
  background: rgba(255,255,255,0.8);
  width: 100%;
  text-align: center;
  font-weight: bold;
  bottom: 0px;
  padding: 15px;
 
}

.swiper-content-slide h5{
     line-height: 1.5em;
    height: 3em;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    width: 100%;
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
                 <a href="{{route('k.show',['id'=>$a->id])}}" target="_blank">
                        <img src="{{url($a->path)}}" class="img-responsive">
                       <div class="swiper-title-slide">
                        <h5 class="text-dark">{{$a->title}}</h5>
                      </div>
                      <div class="swiper-content-slide">
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
               @foreach($berita as $b)
                 <li class="item">
                  <div class="product-img">
                    <img src="{{asset($b->thumbnail_path)}}" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="{{$b->link}}" class="product-title">{{$b->title}}
                      <span class=" pull-right"></span></a>
                   
                    <span class="product-description">
                        {{Carbon\Carbon::parse($b->created_at)->format('d F Y')}}
                        </span>
                  </div>
                </li>
               @endforeach

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
        <li class=""><a data-toggle="tab" href="#menu1" class="text-dark" aria-expanded="false"><b>PRIORITAS TAHUN {{HP::fokus_tahun()}}</b></a></li>
        <li class=""><a data-toggle="tab" href="#menu2" class="text-dark" aria-expanded="false"><b>PRIORITAS  TAHUN {{HP::fokus_tahun()+1}}</b></a></li>
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

<div class="col-md-12">
  <div class="box box-warning">
    <div class="box-header with-border">
      <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label>REGIONAL</label>
                <select id="regional_filter" class="filter form-control">
                  <option value="">SEMUA</option>
                 

                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>KAT</label>
                <select id="tahun_bantuan_filter" class="filter form-control">
                  <option value="xxx">SEMUA</option>
                  <option value="{{HP::fokus_tahun()}}">{{HP::fokus_tahun()}}</option>
                  <option value="{{HP::fokus_tahun()+1}}">{{HP::fokus_tahun()+1}}</option>
                </select>
              </div>
            </div>
      </div>
    </div>
    <div class="box-body table-responsive">
      <table class="table-condensed table table-bordered" id="map_table_index" style="width: 100%; font-size:11px;">
        <thead>
          <tr>
            <th>REGIONAL</th>
            <th>KODE</th>
            <th>NAMA DAERAH</th>
            <th>TIPOLOGI PEMDA</th>
            <th>LUWAS WILAYAH</th>
            <th>JUMLAH PENDUDUK ADM</th>
            <th>IKFD TAHUN {{HP::fokus_tahun()}}</th>
            <th>KONDISI PDAM</th>
            <th>TARGET NUWSP PRIORITAS</th>
            <th>DOKUMEN KEBIJAKAN</th>
            <th>DATA RKPD</th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
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

                          <div class="box-content-img " style="background-image: url({{$out->type==1?url('out_back_map.png'):($out->type==2?asset($out->file_path):'')}})">
                          <div class="back-op">
                          <div class="content-dom">
                            <a href="{{$out->type==2?route('own.out.post.show',['id'=>$out->id,'slug'=>HP::slugify($out->title)]):url($out->file_path)}}" target="_blank">{{$out->title}}</a>
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
var c={};
    $(function(){
        var team_nuswp=$('#team_nuswp').height();
        $('#info_bangda').css('height',(team_nuswp-70));


    });

  var series_map={

  };
  function build_data(rows){
    var index=1;
      for(var i in rows){
          if((typeof series_map[rows[i].regional.toLowerCase().replace(/[' ','&']/g,'')]) === 'undefined'){

            var dom='<option value="'+rows[i].regional+'">'+rows[i].regional+'</option>';

            $('#regional_filter').append(dom);

              series_map[(rows[i].regional.toLowerCase().replace(/[' ','&']/g,'') )]={
                  data_region_target:{
                  name:'Target NUWSP '+rows[i].regional,
                  index:99999,
                  type:'mapbubble',

                    minSize: 1,
                    maxSize: '3%',
                  
                    opacity:1,
                  color:'#e91e63',
                  states: {
                      hover: {
                          borderWidth: 1
                      }
                  },
                  allAreas:false,

                  mapData:Highcharts.maps['ind_kota'],
                  joinBy:['id','kode_daerah'],
                  linkedTo:(rows[i].regional.toLowerCase().replace(/[' ','&']/g,'')),
                  // showInLegend:true,
                  data:[],
                  id:(rows[i].regional.toLowerCase().replace(/[' ','&']/g,''))+'_target',
                },
                data_region:{
                  type:'map',
                  name:rows[i].regional,
                  allAreas:false,
                  color:rows[i].color,
                  index:index+1,
                  mapData:Highcharts.maps['ind_kota'],
                  joinBy:['id','kode_daerah'],
                  id:(rows[i].regional.toLowerCase().replace(/[' ','&']/g,'')),
                  data:[]
                },
              
              }

          }

          delete rows[i].color;
          series_map[rows[i].regional.toLowerCase().replace(/[' ','&']/g,'')].data_region.data.push(rows[i]);

      }

    }

    var map_index='';

    function build_data_map(rows){
      var series_chace={};

        rows.each(function(row){
            inxx=i;
           
          if((typeof series_chace[row.regional.toLowerCase().replace(/[' ','&']/g,'')]) === 'undefined'){
            series_chace[row.regional.toLowerCase().replace(/[' ','&']/g,'')]={
              data:[]
            };

          }

          if(row.target){
            var dt=JSON.stringify(row);
             dt=JSON.parse(dt);

            dt['z']=0.1;
            dt['id']=i;

            delete dt['color'];
            
            series_chace[row.regional.toLowerCase().replace(/[' ','&']/g,'')].data.push(dt);
          }
         
        });


        for(var i in series_map){
          
          if(typeof (series_chace[i])==='undefined'){
              series_map[i].data_region_target.data=[];
          }else{
              series_map[i].data_region_target.data=series_chace[i].data;

          }

        }

        var series_real=[
        // {

        //                 name: 'Poligon',
        //                 showInLegend: false,
        //                 type:'map',
              
        //                 mapData:Highcharts.maps['ind_kota'],
        //                 data:[],
        //         }
        ];

        for(var i in series_chace){
          for(var k in series_map[i]){
            series_real.push(series_map[i][k]);
          }
        }


        if(map_index!=''){
          map_index.destroy();
        }


        map_index=Highcharts.mapChart('map_index', {
                chart: {
                    height:500,
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                },
                credits: {
                    enabled: false
                },
                legend: {
                    title: {
                        text: 'REGIONAL',
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
                    },
                    enableTouchZoom:true,
                },
                // findNearestPointBy:'xy',
                tooltip: {
                  headerFormat: '',
                    formatter: function() {
                        var jenis_bantuan=this.point.jenis_bantuan!=null?this.point.jenis_bantuan.split(',@'):[];
                        for(var i in jenis_bantuan){
                            jenis_bantuan[i]=jenis_bantuan[i].replace('@','');
                        }

                        jenis_bantuan='<b>'+jenis_bantuan.join(', ')+'</b>';
                       return "<h5><b>"+this.point.nama_daerah+"</b></h5><br>"+
                       (this.point.tahun!=null?'<h5><b>'+(this.point.tahun!=1?'TAHUN PROYEK : '+this.point.tahun+' ('+jenis_bantuan+')</b> </h5>':'Target NUSWP'):'')+'<br>'+
                    (this.point.tahun!=null?(this.point.pdam!=null?'<b>'+this.point.pdam+'</b>':''):'')+'<br>'+
                       (this.point.tahun!=null?'<h5 style="text-align:center;"><small >click untuk melihat detail</small></5>':"");  
                    }
                  
                },
                plotOptions:{
                    mapbubble:{
                        point:{
                            cursor:"pointer",
                            events:{
                                click:function(){
                                    API_CON.post("{{route('web_api.daerah.profile')}}/"+this.kode_daerah).then(function(res){
                                            $('#modal_map_detail .modal-header').html(res.data.title);
                                            $('#modal_map_detail .modal-body').html(res.data.data);
                                            $('#modal_map_detail').modal();
                                    });
                                    // console.log(this);
                                }
                            }
                        }
                    }
                },
                title: {
                    text: 'NATIONAL URBAN WATER SUPPLY PROGRAM',
                    style:{
                        color:'#fff'
                    }
                },
                subtitle: {
                    text: 'PETA DAERAH NUWSP ',
                     style:{
                        color:'#fff'
                    }
                },
                series: series_real
        });





    }

    var map_table_index={};




    $(function(){
        API_CON.post('{{route('web_api.nuwas.daerah.target.2')}}').then(function(res){
        var data_map_source=res.data;
        var data_all=JSON.stringify(res.data.data);
        data_all=JSON.parse(data_all);
        build_data(data_map_source.data);
        var data_chace=[];

        for(var i in data_all){
          if(data_all[i].target!=null){
            data_chace.push(data_all[i]);
          }
        }


        map_table_index=$('#map_table_index').DataTable({
          dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'DOWNLOAD EXCEL',
                    className:'btn btn-success btn-xs',
                    messageTop: 'TARGET NUWSP',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10,11]
                    }
                },
            ],
          pageLength:5,
          "order": [[ 0, 'asc' ]],
          columnDefs:[
                { "visible": false, "targets": 0 },
            
          ],

          drawCallback: function ( settings ) {
            $("#table_daerah thead").css('display','none');
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var rows_data = api.rows( {page:'current'} ).data();
            var last=null;
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
              if(last!=group){
                var dt=(rows_data[i]);
                    $(rows).eq( i ).before(
                       '<tr class="" style="background:'+dt.color+'"><td colspan="11"><b style="color:#fff; background:#222; padding:5px;">REGIONAL '+group.toUpperCase()+'</b> </td></tr>'

                    );

                last=group;
              }

            });

          },
          columns:[
            {
              data:'regional',
              createdCell: function (td, cellData, rowData, row, col) {
                $(td).attr('style','background-color:'+rowData.color);

              },
            },
            {
              data:'kode_daerah',
            },
            {

               data:'nama_daerah'
            },
            {
              render:function(data,type,dataRow){
                return '<a href="'+dataRow.link_tipologi+'" target="_blank" class="bg-orange btn btn-xs text-dark ">TIPOLOGI</a>';
              }
            },
              {
              render:function(){
                // l wil
                return '';
              }
            },
              {
              render:function(){
                // jumlah pp
                return '';
              }
            },
              {
              render:function(){
                //ikfd
                return '';
              }
            },
             {
              render:function(data,type,dataRow){

                if(dataRow.pdam){
                  var pdam=dataRow.pdam.split('->');
                  return '<b>NAMA PDAM : </b> '+pdam[0]+'<br>'+'<b>KATEGORI PDAM  : </b> '+pdam[1];
                }

                return '';
              }
            },
            {
              render:function(data,type,dataRow){
                if((dataRow.tahun!=null)&&(dataRow.tahun!=1)){
                    return dataRow.tahun;
                }
                return '';
              }
            },
            {
              render:function(data,style,dataRow){
                var doc=dataRow.doc_kebijakan_daerah;
                if(doc){
                  return doc.replace(/['||']/g,' , ') +'<br><a class="btn btn-primary btn-xs">Detail Dokumen</a>';
                }
                return '';
              }
            },
            {
              render:function(data,style,dataRow){
                var dm='';
                if(dataRow.nama_daerah=='KABUPATEN DELI SERDANG / PROVINSI SUMATERA UTARA'){
                }


                if(dataRow.jumlah_kegiatan){

                  dm+='<a href="" class="btn btn-success btn-xs">RKPD {{HP::fokus_tahun()}}</a>';
                }

                if(dataRow.jumlah_kegiatan_1){
                  dm+='<a href="" class="btn btn-success btn-xs">RKPD {{HP::fokus_tahun()+1}}</a>';
                }


                return dm;
              }
            },
           
          ]
        });

        map_table_index.rows.add(data_chace).draw();


        map_table_index.on('order.dt search.dt', function () {

          sRun();


        }).draw();

        function sRun(){
          setTimeout(function(){
              var data=map_table_index.rows({ filter : 'applied', order:'applied'}).data(); 
              
              build_data_map(data);

            },1000);
        }

        $('.filter').on('change',function(){
              map_table_index.draw();        

              var data=map_table_index.rows({ filter : 'applied', order:'applied'}).data(); 
              
              build_data_map(data);


        });


        setTimeout(function(){
            var data=map_table_index.rows({ filter : 'applied', order:'applied'}).data();
            build_data_map((data));

        },500);







        $.fn.dataTable.ext.search.push(
       function( settings, dt, index,data ) {
        var approve=true;
        

        if(approve){

            if($('#regional_filter').val()!=''){

                if($('#regional_filter').val()){
                  if(data.regional==($('#regional_filter').val())){
                    approve= true;
                  }else{

                    approve= false;
                  }
                }else{
                  // approve=true;
                }
              
            }
        }

        if(approve){

            if(($('#tahun_bantuan_filter').val()!='xxx')){
                
                if(data.tahun==($('#tahun_bantuan_filter').val()!=''?($('#tahun_bantuan_filter').val()):null) ){
                  approve= true;
                }else{

                  approve= false;
                }
              }else{
                // approve=true;
              }
            
            
        }

        


        if(approve){
          if($('#provinsi_filter').val()!=''){

            if((data.kode_daerah_parent==$('#provinsi_filter').val())||(data.kode_daerah==$('#provinsi_filter').val())){
            }else{
              approve=false

            }
          }
        }

        if(approve){
          return true;
        }else{
          return false;
        }

          

    });










        var tahun_semua=[{

                        name: 'Poligon',
                        showInLegend: false,
                        type:'map',
              
                        mapData:Highcharts.maps['ind_kota'],
                        data:[],
                },
        ];

        for(var i in data_map_source.all){
            tahun_semua.push({
              name:data_map_source.all[i].name+' ('+data_map_source.all[i].jumlah_daerah+')',
              type:'map',
              allAreas: false,
              mapData:Highcharts.maps['ind_kota'],
              data:data_map_source.all[i].data,
              color:data_map_source.all[i].color,
              joinBy:['id','kode_daerah'],

            });
        }

        tahun_semua.push( {
            index:99999,
            name: 'Target NUWSP'+' ('+data_map_source.point_target.data.length+')',
            type:'mapbubble',
            minSize: 1,
            maxSize: '3%',
            opacity:1,
            color:data_map_source.point_target.color,
            borderColor: 'black',
            borderWidth: 0.2,
            states: {
                hover: {
                    borderWidth: 1
                }
            },
            mapData:Highcharts.maps['ind_kota'],
            joinBy:['id','kode_daerah'],
            data:data_map_source.point_target.data,

        });

        var tahun_{{HP::fokus_tahun()}}=[
            {
                    name: 'Poligon',
                    showInLegend: false,
                    type:'map',
                    mapData:Highcharts.maps['ind_kota'],
                    data: [],

            },
             {
                    name: 'Stimulan'+' ('+data_map_source.t{{HP::fokus_tahun()}}.stimulan.data.length+')',
                    type:'map',
                    color:'#f6d55c',
                      joinBy:['id','kode_daerah'],
                     allAreas: false,
                    mapData:Highcharts.maps['ind_kota'],
                    data:data_map_source.t{{HP::fokus_tahun()}}.stimulan.data,

            },
            {
                    name: 'Pendamping'+' ('+data_map_source.t{{HP::fokus_tahun()}}.pendamping.data.length+')',
                    type:'map',
                    color:'#3caea3',
                    joinBy:['id','kode_daerah'],
                     allAreas: false,
                    mapData:Highcharts.maps['ind_kota'],
                    data:data_map_source.t{{HP::fokus_tahun()}}.pendamping.data,

            },

        ];

        var tahun_{{1+HP::fokus_tahun()}}=[
                  {
                        name: 'Poligon',
                        showInLegend: false,
                        type:'map',
                        mapData:Highcharts.maps['ind_kota'],
                        data: [],

                },
                 {
                        name: 'Stimulan'+' ('+data_map_source.t{{HP::fokus_tahun()+1}}.stimulan.data.length+')',
                        type:'map',
                        color:'#f6d55c',
                        joinBy:['id','kode_daerah'],
                        allAreas: false,
                        mapData:Highcharts.maps['ind_kota'],
                        data:data_map_source.t{{1+HP::fokus_tahun()}}.stimulan.data,

                },
                {
                        name: 'Pendamping'+' ('+data_map_source.t{{HP::fokus_tahun()+1}}.stimulan.data.length+')',
                        type:'map',
                        color:'#3caea3',
                        joinBy:['id','kode_daerah'],
                         allAreas: false,
                        mapData:Highcharts.maps['ind_kota'],
                        data:data_map_source.t{{1+HP::fokus_tahun()}}.pendamping.data,

                },

        ];

       

        


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
                        text: 'JENIS HIBAH',
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
                tooltip: {
                  headerFormat: '',
                    formatter: function() {
                        var jenis_bantuan=this.point.jenis_bantuan!=null?this.point.jenis_bantuan.split(',@'):[];
                        for(var i in jenis_bantuan){
                            jenis_bantuan[i]=jenis_bantuan[i].replace('@','');
                        }

                        jenis_bantuan='<b>'+jenis_bantuan.join(', ')+'</b>';
                       return "<h5><b>"+this.point.nama_daerah+"</b></h5><br>"+
                       (this.point.tahun!=null?'<h5><b>'+(this.point.tahun!=1?'TAHUN PROYEK : '+this.point.tahun+' ('+jenis_bantuan+')</b> </h5>':'Target NUSWP'):'')+'<br>'+
                    (this.point.tahun!=null?(this.point.pdam!=null?'<b>'+this.point.pdam+'</b>':''):'')+'<br>'+
                       (this.point.tahun!=null?'<h5 style="text-align:center;"><small >click untuk melihat detail</small></5>':"");  
                    }
                  
                },
                plotOptions:{
                    series:{
                        point:{
                            cursor:"pointer",
                            events:{
                                click:function(){
                                    API_CON.post("{{route('web_api.daerah.profile')}}/"+this.kode_daerah).then(function(res){
                                            $('#modal_map_detail .modal-header').html(res.data.title);
                                            $('#modal_map_detail .modal-body').html(res.data.data);
                                            $('#modal_map_detail').modal();
                                    });
                                    // console.log(this);
                                }
                            }
                        }
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
                        text: 'JENIS HIBAH',
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
              tooltip: {
                  headerFormat: '',
                    formatter: function() {
                        var jenis_bantuan=this.point.jenis_bantuan!=null?this.point.jenis_bantuan.split(',@'):[];
                        for(var i in jenis_bantuan){
                            jenis_bantuan[i]=jenis_bantuan[i].replace('@','');
                        }

                        jenis_bantuan='<b>'+jenis_bantuan.join(', ')+'</b>';
                       return "<h5><b>"+this.point.nama_daerah+"</b></h5><br>"+
                       (this.point.tahun!=null?'<h5><b>'+(this.point.tahun!=1?'TAHUN PROYEK : '+this.point.tahun+' ('+jenis_bantuan+')</b> </h5>':'Target NUSWP'):'')+'<br>'+
                    (this.point.tahun!=null?(this.point.pdam!=null?'<b>'+this.point.pdam+'</b>':''):'')+'<br>'+
                       (this.point.tahun!=null?'<h5 style="text-align:center;"><small >click untuk melihat detail</small></5>':"");  
                    }
                  
                },
                plotOptions:{
                    series:{
                        point:{
                            cursor:"pointer",
                            events:{
                                click:function(){
                                    API_CON.post("{{route('web_api.daerah.profile')}}/"+this.kode_daerah).then(function(res){
                                            $('#modal_map_detail .modal-header').html(res.data.title);
                                            $('#modal_map_detail .modal-body').html(res.data.data);
                                            $('#modal_map_detail').modal();
                                    });
                                    // console.log(this);
                                }
                            }
                        }
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


 
<div class="modal fade" tabindex="-1" role="dialog" id="modal_map_detail">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@stop
