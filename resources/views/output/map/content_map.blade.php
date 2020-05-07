		<style type="text/css">
			#map-{{$id_map}},#panel-x-{{$id_map}}{
				height: {{isset($height)?$height.'px':'calc(100vh - 100px)'}};
				font-size: 10px;
				overflow-y: scroll;

			}

			#map-{{$id_map}} .load{
				vertical-align: middle;
			} 
			.cursor-link{
				cursor: pointer;
			}
			.cursor-link .active{
				background: navy;
				color:#fff;
			}
			.cursor-link:hover{
				background: #d2d2d2;
			}
			#body_{{$id_map}} .btn{
				font-size: 10px;
			}
			#legend_05_20_11_04_45id table th{
				font-size: 10px;
			}
			#body_{{$id_map}},#body_{{$id_map}} table th,#body_{{$id_map}} table td{
				font-size: 10px;
			}

	</style>
    	<div class="row">
    <div id="body_{{$id_map}}"  style="background: #141d21;position: relative; width: 100%; margin-right: 0px; margin-left: 0px;">

    	@if(isset($own_content) && isset($build_ofline_path))
    	<div class="col-md-12 text-center" style="color: #fff; padding: 10px;">
    		<a href="{{$build_ofline_path}}" download="" class="btn btn-primary btn btn-xs">DOWNLOAD VERSI OFLINE</a>
    	</div>
    	@else
		<div class="col-md-12 text-center" style="color: #fff; padding: 10px;">
    		OFFLINE MODE UPDATED AT {{Carbon\Carbon::now()->format('d F Y h:i A')}}
    	</div>
    	@endif
    	<div class="col-md-9 col-xs-9 col-sm-9" style="padding-right: 0px; padding-left: 0px;">
	    	<div class="" id="map-{{$id_map}}" style="background: #1d2c33;color:#fff">
		    	<div class="load">
		    		<h5 class="text-center">LOADING MAP ..</h5>
		    	</div>
	    	</div>
    	</div>
    	<div class="col-md-3 col-xs-3 col-sm-3 text-dark " style="background: #141d21; ">
    		<div class="panel" id="panel-x-{{$id_map}}" style="padding-left: 0px; background: #141d21">
    			<div class="panel-heading" style="padding-left: 0px; padding-right: 0px;">
    				<h6 style="color: #fff">
    					@if(!isset($own_content))
    					<img src="asset/logo.png" style="max-width: 25px; margin-right: 10px;">
    					@else
    					<img src="{{asset('/L_MAP/asset/logo.png')}}" style="max-width: 25px; margin-right: 10px;">
    					@endif
    					<b>BANGDA KEMENDAGRI (SUPD II)</b></h6>
    				<hr>
    				<input type="checkbox" name="nama_daerah" checked="" id="named_map_{{$id_map}}"  onchange="namad_daerah_{{$id_map}}(this)"> <span style="color: #fff;">&nbsp;MENGUNAKAN NAMA DAERAH</span>
    				<br>
    				<div class="btn-group" style="margin-top: 10px;">
    					<button class="btn btn-xs btn-success" onclick="$('#modal_data_{{$id_map}}').modal()" style="font-size: 10px;">DATA TABLE</button>
			    		@if(!isset($own_content))
						<a href="asset/{{$id_map}}.xlsm" class="btn btn-primary btn-xs" download="" style="font-size: 10px">DOWNLOAD EXCEL</a>

						@else
						<a href="{{$file_path}}" class="btn btn-primary btn-xs" download="" style="font-size: 10px">DOWNLOAD EXCEL</a>
						@endif
    				</div>
    				<hr>
    				<h5 style="color: #fff"><b><i class="fa fa-pages"></i> LAYER</b></h5>
    			</div>
    			<div class="panel-body" style="padding-left: 0px; padding-right: 0px;">
    				<ul class="list-group">
    					
    				</ul>
    			</div>
    		</div>
    	</div>
    	<div class="" id="legend_05_20_11_04_45id">
    		<table class="table-bordered table">
    			<thead style="color: #fff">
    				<tr id="n"></tr>
    				
    			</thead>
    			<tbody>
    				<tr id="c"></tr>

    				
    			</tbody>

    		</table>
    	</div>
    </div>
    </div>

@if(!isset($own_content))

<script type="text/javascript" src="asset/data_builder.js"></script>
<script type="text/javascript" src="asset/{{$id_map}}.js"></script> 


@else


	@if(isset($current_data_db))
		<script type="text/javascript">
			var {{$id_map}}=<?php echo json_encode($current_data_db); ?>;
		</script>
	@else
		


		<script type="text/javascript" src="{{asset('L_MAP/asset/jq.js')}}"></script>
		<script type="text/javascript" src="{{asset('L_MAP/asset/bootstrap.min.js')}}"></script>

		<script type="text/javascript" src="{{asset('L_MAP/asset/hi.js')}}"></script>
		<script type="text/javascript" src="{{asset('L_MAP/asset/proj4.js')}}"></script>

		<script type="text/javascript" src="{{asset('L_MAP/ind/ind.js')}}"></script>x
		<script type="text/javascript" src="{{asset('L_MAP/ind/kota.js')}}"></script>

		<script type="text/javascript" src="{{asset($data_path)}}"></script>
	@endif
@endif


<script type="text/javascript">
	var series_{{$id_map}}=[];
	var series_visible_one_{{$id_map}}=1;
	var map_init_{{$id_map}}={};
	var LAYER_{{$id_map}}=[];
	var series_active_{{$id_map}}=0;
	var series_active_data_{{$id_map}}=[];


	for(var i in {{$id_map}}.series){

			series_{{$id_map}}.push({
			    data: {{$id_map}}.series[i].data,
			    keys: ['id', 'nama', 'value','cat','link','color', 'tooltip'],
			    name: {{$id_map}}.series[i].name_data,
			    joinBy: 'id',
			    type:'map',
			    visible:series_visible_one_{{$id_map}}?true:false,
			    mapData:Highcharts.maps[{{$id_map}}.series[i].mapData_name],
			    dataLabels: {
			        enabled: true,
			        format: '{point.name}',
			        color: '#fff',
			        style: {
			            fontSize: 9,
			            font: '9px Trebuchet MS, Verdana, sans-serif'
			        },
			    },
			    states: {
			        hover: {
			            color: '#BADA55'
			        }
			    },
			});

			LAYER_{{$id_map}}.push({
				name:{{$id_map}}.series[i].name_layer,
				legend:{{$id_map}}.series[i].legend,
				status:series_visible_one_{{$id_map}}?1:0
			});


			

			series_visible_one_{{$id_map}}=0;




		}

		var option_map_{{$id_map}}={
                chart: {
                    // map: 'idn',
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                },
                title: {
                    text: {{$id_map}}.title,
                    style:{
                        color:'#fff'
                    }
                },
                subtitle:{
                	text:{{$id_map}}.sub_title,
                	style:{
                        color:'#fff',

                    },
                	enabled:{{$id_map}}.sub_title?true:false
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                tooltip: {
                    headerFormat: '',
                    formatter: function() {
                    	var link_click='';
                    	if((this.point.link==null)||this.point.link==""){

                    	}else{
                    		link_click='<a class="btn btn-primary btn-xs" href="'+this.point.link+'" target="_blank">DETAIL</a>';
                    	}

                        return (((this.point.tooltip == null)||(this.point.tooltip == ""))? this.point.name: this.point.tooltip)+'<br>'+link_click; 
                    }
                },
                 mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'bottom',
                        horizontalAlign: 'right'

                    }
                },
                series:series_{{$id_map}}

            };


	


			setTimeout(function(){
			$('#map-{{$id_map}}').html('');

			map_init_{{$id_map}}=Highcharts.mapChart('map-{{$id_map}}', option_map_{{$id_map}});
			active_sataus_layer_{{$id_map}}(0);
			control_layer_{{$id_map}}();

			},500);



	function active_sataus_layer_{{$id_map}}(index){
		if(LAYER_{{$id_map}}[index]!=undefined){

			for(var i in LAYER_{{$id_map}}){
				LAYER_{{$id_map}}[i].status=0;
				map_init_{{$id_map}}.series[i].hide();
			}

			LAYER_{{$id_map}}[index].status=1;
			map_init_{{$id_map}}.series[index].show();
			series_active_{{$id_map}}=index;
			var lname='';
			$('#legend_05_20_11_04_45id thead tr#n').html('');
			$('#legend_05_20_11_04_45id tbody tr#c').html('');

			for(var i in LAYER_{{$id_map}}[index].legend.cat){
				$('#legend_05_20_11_04_45id thead tr#n').append("<th>"+LAYER_{{$id_map}}[index].legend.cat[i]+"</th>");
				$('#legend_05_20_11_04_45id tbody tr#c').append("<td style='background-color:"+LAYER_{{$id_map}}[index].legend.color[i]+";'>"+''+"</td>");
			}
			$('#named_map_{{$id_map}}').trigger('change');



			control_layer_{{$id_map}}();
			series_active_data_{{$id_map}}={{$id_map}}.series[index];
			builder_data_table_all_{{$id_map}}();



		}
	}


	function builder_data_table_all_{{$id_map}}(){
		var d=series_active_data_{{$id_map}}.data;
		var dom_tb='';
		var c_clor='';
		for(var i in d){
				if((''+d[i].id).length <3){
					c_clor='bg-primary';
				}else{
					c_clor='';
				}

				dom_tb+='<tr class="'+c_clor+'"><td>'+d[i].id+'</td>'+
				'<td>'+d[i].nama+'</td>'+
				'<td>'+d[i].value+'</td>'+
				'<td>'+d[i].cat+'</td>'+
				'<td style="background-color:'+(d[i].cat=='TIDAK TERDAPAT DATA'?'':d[i].color)+'">'+''+'</td></tr>';
			
		}

		$('#modal_data_{{$id_map}} .modal-body table tbody').html(dom_tb);
		$('#modal_data_{{$id_map}} .modal-header h5 b').html(series_active_data_{{$id_map}}.name_layer);

		console.log('#modal_data_{{$id_map}} .modal-body table tbody');
	}


	function control_layer_{{$id_map}}(){
			$('#panel-x-{{$id_map}} .list-group').html('');

			for(var i in LAYER_{{$id_map}}){
				$('#panel-x-{{$id_map}} .list-group').append(
					'<li class="list-group-item cursor-link '+(LAYER_{{$id_map}}[i].status?'active':'')+'" onclick="active_sataus_layer_{{$id_map}}('+i+')">'+
						LAYER_{{$id_map}}[i].name
					+'</li>'
				);

			}

	}

	function namad_daerah_{{$id_map}}(dom){
		var option={};
		option=map_init_{{$id_map}}.series[series_active_{{$id_map}}].options;

		if($(dom).prop('checked')){
			option.dataLabels.enabled=true;

		}else{


			option.dataLabels.enabled=false;

		}

		map_init_{{$id_map}}.series[series_active_{{$id_map}}].update(option);

	}



</script>

<div class="modal fade" id="modal_data_{{$id_map}}">
	<div class="modal-dialog modal-lg">
		<div class="modal-content ">
		<div class="modal-header">
			<h5><b></b></h5>

		</div>
		<div class="modal-body">
			<table class="table-bordered table">
				<thead>
					<tr>
						<th>ID DAERAH</th>
						<th>NAMA</th>

						<th class="text-uppercase nama_data_layer" >
							NILAI
						</th>
						<th class="text-uppercase">
							KATEGORI
						</th>
						<th class="text-uppercase">
							WARNA
						</th>
					</tr>
				</thead>
				<tbody></tbody>
				
			</table>
		</div>
	</div>
	</div>
</div>


<div class="modal fade " id="modal_data_kat_{{$id_map}}">
	<div class="modal-dialog modal-lg">
		<div class="modal-content ">
		<div class="modal-header">
			<b></b>
			<button class="btn btn-xs btn btn-primary" style="font-size: 10px">DOWNLOAD EXCEL</button>
		</div>
		<div class="modal-body">
			<table class="table-bordered table">
				<thead>
					<tr>
						<th>ID DAERAH</th>
						<th>NAMA</th>
						<th class="text-uppercase nama_data_layer">
							NILAI
						</th>
						<th class="text-uppercase">
							KATEGORI
						</th>
						<th class="text-uppercase">
							WARNA
						</th>
					</tr>
				</thead>
				
			</table>
		</div>
	</div>
	</div>
</div>