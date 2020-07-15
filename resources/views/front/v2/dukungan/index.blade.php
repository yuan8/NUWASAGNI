@extends('adminlte::page')


@section('content_header')
<div class="row bg-navy">
	<div class="col-md-12">
		<h4 class="text-center"></h4>
    <h5 class="text-center"> PROGRAM KEGIATAN TAHUN {{HP::fokus_tahun()}} -  <small class="text-white">AIR MINUM</small> </h5>
	</div>
</div>

<style type="text/css">
  .info-box-m0 .info-box{
    margin-bottom: 0px;
  }
  .pm0 p{
    margin-bottom: 0px!important;
  }
</style>
@stop

@section('content')
<div class="row no-gutter info-box-m0 pm0" >
    <div class="col-md-3">
    <div class="info-box bg-red">
          <span class="info-box-icon" style="font-size:10px;line-height: 10px;">
            <br>

            <p>
            DAERAH
            TARGET
            <br>
            <b><h5 id="tt_daerah_target"></h5></b>
            <hr style="margin-bottom: 5px; margin-top: 5px; border-color:#fff;" >
            <i>Tahun {{HP::fokus_tahun()}}</i>
            </p>

          </span>

          <div class="info-box-content">
            <span class="info-box-text">KETERSEDIAN DATA RKPD {{HP::fokus_tahun()}}</span>
            <span class="info-box-number" style="font-size:13px;" id="tt_rkpd" ></span>

            <div class="progress">
              <div class="progress-bar" style="width: 50%"></div>
            </div>
            <span class="progress-description" style="font-size:10px;" >
                  MASUK KEDALAM SISTEM NUWSP
                  <br> 
                  <p id="tt_rkpd_sistem_air_minum"></p>
                </span>
          </div>
          <!-- /.info-box-content -->
        </div>
  </div>
  <div class="col-md-3">
    <div class="info-box bg-aqua">
          <span class="info-box-icon"><i class="ion ion-pricetags"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">TOTAL PROGRAM</span>
            <span class="info-box-number" style="font-size:13px;" id="ttg_program"></span>

            <div class="progress">
              <div class="progress-bar" style="width: 50%"></div>
            </div>
            <span class="progress-description" style="font-size:10px;">
                  JUMLAH PROGRAM
                  <br> 
                  <p id="tt_program"></p>
                </span>
          </div>
          <!-- /.info-box-content -->
        </div>
  </div>
  <div class="col-md-3">
    <div class="info-box bg-yellow">
          <span class="info-box-icon"><i class="ion ion-pricetags"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">TOTAL KEGIATAN</span>
            <span class="info-box-number" style="font-size:13px;" id="ttg_kegiatan"></span>

            <div class="progress">
              <div class="progress-bar" style="width: 50%"></div>
            </div>
            <span class="progress-description" style="font-size:10px;">
                  JUMLAH KEGIATAN
                  <br> 
                  <p id="tt_kegiatan"></p>

                </span>
          </div>
          <!-- /.info-box-content -->
        </div>
  </div>
  <div class="col-md-3">
    <div class="info-box bg-green">
          <span class="info-box-icon"><i class="ion ion-card"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">TOTAL ANGGARAN</span>
            <span class="info-box-number" style="font-size:13px;" id="ttg_anggaran"></span>

            <div class="progress">
              <div class="progress-bar" style="width: 50%"></div>
            </div>
            <span class="progress-description" style="font-size:10px;">
                  JUMLAH ANGGARAN
                  <br> 
                  <p id="tt_anggaran"></p>

                </span>
          </div>
          <!-- /.info-box-content -->
        </div>
  </div>



</div>
<div class="row no-gutter">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body" id="column_chart"></div>
    </div>
  </div>
</div>
  
  <div class="row no-gutter">
  	<div class="col-md-12">
  		<div class="box box-primary">
  			<div class="box-header with-border">
  				<div class="row">
  					<div class="col-md-2">
  						<div class="form-group">
  							<label>REGIONAL</label>
  							<select id="regional_filter" class="filter form-control">
  								<option value="">SEMUA</option>
  								@foreach($regional as $r)
                    <option value="{{$r}}">{{$r}}</option>
                  @endforeach

  							</select>
  						</div>
  					</div>
            <div class="col-md-2">
              <div class="form-group">
                <label>KAT</label>
                <select id="kat_daerah_filter" class="filter form-control">
                  <option value="">SEMUA</option>
                  <option value="P">PROVINSI</option>
                  <option value="K">KOTA</option>

                </select>
              </div>
            </div>
  					<div class="col-md-2">
  						<div class="form-group">
  							<label>MEMILIKI KEGIATAN</label>
  							<select id="value_filter" class="filter form-control">
  								<option value="">SEMUA</option>
  								<option value="1">MEMILIKI</option>
  								<option value="0">TIDAK MEMILIKI</option>

  							</select>
  						</div>
  					</div>
            <div class="col-md-2">
              <div class="form-group">
                <label>TAHUN BANTUAN</label>
                <select id="tahun_bantuan_filter" class="filter form-control">
                  <option value="xxx">SEMUA</option>
                  <option value="">DAERAH NUWSP NON PRIORITAS</option>
                  <option value="{{HP::fokus_tahun()}}">PRIORITAS {{HP::fokus_tahun()}}</option>
                  <option value="{{HP::fokus_tahun()+1}}">PRIORITAS {{HP::fokus_tahun()+1}}</option>
                  

                </select>
              </div>
            </div>
  					<div class="col-md-2">
  						<div class="form-group">
  							<label>JENIS HIBAH</label>
  							<select id="target_nuwas_filter" class="filter form-control">
  								<option value="">SEMUA</option>
  								<option value="@STIMULAN">STIMULAN</option>
  								<option value="@PENDAMPING">PENDAMPING</option>

  							</select>
  						</div>
  					</div>

  					<div class="col-md-2">
  						<div class="form-group">
  							<label>PROVINSI</label>
  							<select id="provinsi_filter" class="filter form-control">
  								<option value="">SEMUA</option>
  								<?php foreach ($provinsi as $key => $d): ?>
  									<option value="{{$d->id}}" {{isset($_GET['provinsi'])?($_GET['provinsi']==$d->id?'selected':''):''}}>{{$d->nama}}</option>
  									
  								<?php endforeach ?>

  							</select>
  						</div>
  					</div>
  				</div>
  			</div>
  			<div class="box-body">
  				<table class="table-bordered table" id="table_daerah">
  					<thead>
  						<tr>
                <th>REGIONAL</th>

  							<th>KODE</th>
  							<th>NAMA DAERAH</th>
  							<th>NAMA PROVINSI</th>
                <th>TAHUN BANTUAN</th>
			  				<th>JENIS HIBAH</th>
			           <th>JUMLAH PROGRAM</th>
			  				<th>JUMLAH KEGIATAN</th>
			           <th>JUMLAH ANGGARAN</th>
			           <th>PERSENTASE ANGGARAN</th>
  							<th>ACTION</th>

  						</tr>
  					</thead>
  					<tbody></tbody>
  					<tfoot>
  						<tr>
                <th></th>

  							<th></th>
  							<th></th>
                <th></th>

  							<th></th>

  							<th></th>
  							<th></th>
                <th></th>

                <th></th>
                <th></th>



  						</tr>
  					</tfoot>
  				</table>
  			</div>
  		</div>
  	</div>
  </div>
@stop

@section('js')
<script type="text/javascript">
	var data_source=<?php  echo json_encode($data) ?>;
    var chart='';

  var glob_var={
      category:[],
      data:[
      {
        name:'JUMLAH PROGRAM',
        type:'column',
        yAxis:1,
        data:[]


      },{
        name:'JUMLAH KEGIATAN',
        type:'column',
        yAxis:1,


        data:[],


      },
      {
        name:'TOTAL ANGGARAN',
        type:'line',
        color:'#05668d',
        yAxis:0,

        data:[],


      }
    ]

    }

	var table_daerah=$('#table_daerah').DataTable({
    dom: 'Bfrtip',
      buttons: [
          {
              extend: 'excelHtml5',
              text: 'DOWNLOAD EXCEL',
              className:'btn btn-success btn-xs',
              messageTop: 'REKAP RKPD AIR MINUM  TAHUN {{HP::fokus_tahun()}}',
              exportOptions: {
                  columns: [0,1,2,3,4,5,6]
              }
          },
      ],
     "order": [[ 0, 'asc' ]],
    columnDefs:[
            { "visible": false, "targets": 0 },

    ],
		 drawCallback: function (settings) {
      console.log('draw run');

        var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var rows_data = api.rows( {page:'current'} ).data();
            var last=null;
            var data_show=[];
 
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
              data_show.push(rows_data[i]);

                if ( last !== group ) {
                    var dt=(rows_data[i]);
                    $(rows).eq( i ).before(
                      '<tr class="bg-navy"><td colspan="10";><b class="text-uppercase">REGIONAL '+group+'</b></td></tr>'
                      );
                  }
                  last=group;
            });

	      var api = this.api();
		     $( api.table().column(1).footer() ).html('TOTAL');
		     
         $(api.table().column(6).footer()).html(
		     		api.column(6, {"filter": "applied"}).data().sum().toFixed(2)
		     );	

          $(api.table().column(7).footer()).html(
            api.column(7, {"filter": "applied"}).data().sum().toFixed(2)
         ); 

          $(api.table().column(8).footer()).html(
            api.column(8, {"filter": "applied"}).data().sum().toFixed(2)
         ); 

          for(var i=6;i<9;i++){
             $(api.table().column(i).footer()).html(
                formatNumber($(api.table().column(i).footer()).html(),0)
            ); 
          }
		  },
		createdRow:function(row,data,dataIndex,cells){
			if(data.jumlah_kegiatan==0){
				$(row).addClass("bg-danger");
			}
		},
		columns:[
      {
        data:'regional',
        orderable:false,

      },
			{
				data:'kode_daerah',
        orderable:false,

				createdCell: function (td, cellData, rowData, row, col) {
					$(td).addClass(' hover-bg-td '+(cellData.length<3?'bg-primary':''));
			    },
			},
			{
				data:'nama_daerah',
        orderable:false,

			},
			{
				data:'nama_provinsi',
        orderable:false,

			},
      {
        data:'tahun',

      },
			{
		data:'jenis_bantuan',
        orderable:false,

				render:function(data){
					if((data!=null) &&(data!='')){
						return data.replace(/@/g,' ');
					}else{
						return '';
					}
				}
			},

		  {
        data:'jumlah_program',
        type:'currency',
        render:function(data){
          return formatNumber(data,0);
        }
        
      },
      {
        data:'jumlah_kegiatan',
        type:'currency',
        render:function(data){
          return formatNumber(data,0);
        }
      },
      {
        data:'jumlah_anggaran',
        type:'currency',
        render:function(data){
          return formatNumber(data,2);
        }
      },
      { type:'currency',
        render:function(data,type,dataRow){
          var per=(dataRow.jumlah_anggaran / dataRow.jumlah_anggaran_total)*100;
          if(Number.isNaN(per)){
            return '0%';
          }else{
          return formatNumber(per,2)+'%';

          } 

        }
      },
			{
        orderable:false,
        
				render:function(data,type,dataRow){
					if(dataRow.jumlah_kegiatan>0){
						return '<a target="_blank" href="'+dataRow.link_detail+'" class="btn btn-primary btn-xs">DETAIL PROGRAM</a>';
					}else{
						return '';
					}
				}
			},
      
		]
	});


	table_daerah.rows.add(data_source).draw();

	$.fn.dataTable.ext.search.push(
       function( settings, dt, index,data ) {
        var approve=true;
        	if(approve){

		        if($('#kat_daerah_filter').val()!=''){
		        		if($('#kat_daerah_filter').val()=='P'){
			        		if(data.kode_daerah.length>3){
			        			approve=false;
			        		}
		        		}else if($('#kat_daerah_filter').val()=='K'){
		        			if(data.kode_daerah.length<3){
			        			approve=false;
			        		}
		        		}
		        	
		        }
		    }

		    if(approve){

		        if($('#value_filter').val()!=''){

		        		if($('#value_filter').val()==1){
			        		if(data.jumlah_kegiatan==0){
			        			approve=false;
			        		}
		        		}else if($('#value_filter').val()==0){
		        			if(data.jumlah_kegiatan!=0){
			        			approve=false;
			        		}
		        		}
		        	
		        }
		    }

		     if(approve){

		        if($('#target_nuwas_filter').val()!=''){

		        		if($('#target_nuwas_filter').val()){
		        			if(data.jenis_bantuan.includes($('#target_nuwas_filter').val())){
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


table_daerah.on('order.dt search.dt', function () {
     drawChart();
}).draw();


 function drawChart(){
     setTimeout(function(){
           var data_show=table_daerah.rows({ filter : 'applied', order:'applied'}).data();
           var data_global=table_daerah.rows().data();

            calculate_total(data_show,data_global);
            generate_data_chart(data_show);


            
            if(!chart==''){
             chart.destroy();
            }

             chart=Highcharts.chart('column_chart', {
                   chart: {
                          zoomType: 'xy',
                          scrollablePlotArea: {
                              minWidth:(data_show.length * 110),
                              scrollPositionX: 1
                          },
                          backgroundColor:'#fff',
                          plotBorderWidth: 2,
                      },
                  navigator: {
                      enabled: true
                    },
                  title: {
                      text: ''
                  },
                  subtitle: {
                      text: ''
                  },
                  colors:['#80b918','#bfd200','#fff'],
                  xAxis: {
                      categories: glob_var.category,
                      label:{
                        style: {
                                color: '#222'
                      },
                      },
                      color:'#222',
                      gridLineWidth: 1,
                      gridZIndex: 4,
                      crosshair: true
                  },
                  yAxis: [
                    { // Primary yAxis
                        labels: {
                            format: 'Rp. {value}',
                            style: {
                                color: '#222'
                            }
                        },
                        title: {
                            text: 'JUMLAH ANGGARAN',
                            style: {
                                color: '#222'
                            }
                        },
                        lineWidth: 1,
                        opposite: true,
                        minRange:100000000,
                        crosshair: true


                    }, { // Secondary yAxis
                        // gridLineWidth: 0,
                        crosshair: true,
                         // angle: 150,
                      
                        title: {
                            text: 'JUMLAH PROGRAM / KEGIATAN',
                            style: {
                                color: '#222' 
                            }
                        },
                        labels: {
                            format: '{value} ',
                            style: {
                                color: '#222' 
                            }
                        }

                    }

                  ],

                  tooltip: {
                      headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                      pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                          '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                      footerFormat: '</table>',
                      shared: true,
                      useHTML: true
                  },
                 plotOptions: {
                      line: {
                          lineWidth: 1,
                          dataLabels: {
                              enabled: true
                          },
                      },
                       column: {
                          dataLabels: {
                              enabled: true
                          },
                      }
                  },
                  series:glob_var.data,
                   responsive: {
                      rules: [{
                          condition: {
                              maxWidth:'100%' 
                          },
                          chartOptions: {
                              legend: {
                                  floating: false,
                                  layout: 'horizontal',
                                  align: 'center',
                                  verticalAlign: 'bottom',
                                  x: 0,
                                  y: 0
                              },
                              yAxis: [{
                                  labels: {
                                      align: 'right',
                                      x: 0,
                                      y: -6
                                  },
                                  showLastLabel: false
                              }, {
                                  labels: {
                                      align: 'left',
                                      x: 0,
                                      y: -6
                                  },
                                  showLastLabel: false
                              }
                              ]
                          }
                      }]
                  }
              });

            
            

    },500);
 }

$('.filter').on('change',function(){
    	table_daerah.draw();

    


      drawChart();
 
});


function calculate_total(data,dataGlob=[]){
  var tt_rkpd=0;
  var tt_rkpd_sistem=0;
  var tt_rkpd_sistem_air_minum=0;

  var tt_program=0;
  var ttg_program=0;

  var tt_kegiatan=0;
  var ttg_kegiatan=0;

  var tt_anggaran=0;
  var ttg_anggaran=0;

  var tt_daerah_target=0;


  data.each(function(d){
    tt_program+=d.jumlah_program!=null?parseFloat(d.jumlah_program):0;
    tt_anggaran+=d.jumlah_anggaran!=null?parseFloat(d.jumlah_anggaran):0;
    tt_kegiatan+=d.jumlah_kegiatan!=null?parseFloat(d.jumlah_kegiatan):0;
    tt_daerah_target+=1;

    if(d.jumlah_kegiatan!=0){
      tt_rkpd_sistem_air_minum+=1;
    }

    if(d.terdapat_data_rkpd_di_sistem){
       tt_rkpd_sistem+=1;
    }

  });

  $('#tt_daerah_target').html(formatNumber(tt_daerah_target,0));
  $('#tt_anggaran').html('Rp. '+formatNumber(tt_anggaran,3));
  $('#tt_program').html(formatNumber(tt_program,0));
  $('#tt_kegiatan').html(formatNumber(tt_kegiatan,0));
  $('#tt_rkpd_sistem_air_minum').html(formatNumber(tt_rkpd_sistem,0)+' RKPD / '+formatNumber(tt_rkpd_sistem_air_minum,0)+' Memuat Air Minum');

  dataGlob.each(function(d){
    if(d.status_data_sipd==5){
        tt_rkpd+=1;
    }

    ttg_program+=d.jumlah_program!=null?parseFloat(d.jumlah_program):0;
    ttg_anggaran+=d.jumlah_anggaran!=null?parseFloat(d.jumlah_anggaran):0;
    ttg_kegiatan+=d.jumlah_kegiatan!=null?parseFloat(d.jumlah_kegiatan):0;

  });

  $('#tt_rkpd').html(formatNumber(tt_rkpd,0)+' DAERAH MELAPOR RKPD');
  $('#ttg_program').html(formatNumber(ttg_program,0));
  $('#ttg_kegiatan').html(formatNumber(ttg_kegiatan,0));
  $('#ttg_anggaran').html(formatNumber(ttg_anggaran,0));




}

    $($('.filter')[0]).trigger('change');

    

    function generate_data_chart(data){

      glob_var.category=[];
      glob_var.data[0].data=[];
      glob_var.data[1].data=[];
      glob_var.data[2].data=[];

      data.each( function (row) {

       
        glob_var.data[0].data.push(parseFloat(row.jumlah_program));
        glob_var.data[1].data.push(parseFloat(row.jumlah_kegiatan));
        glob_var.data[2].data.push(parseFloat(row.jumlah_anggaran==null?0:row.jumlah_anggaran));
        glob_var.category.push(row.nama_daerah);
      });
        
        
      


    }


    function build_chart(){


    }

</script>

@stop