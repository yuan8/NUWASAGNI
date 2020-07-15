<!DOCTYPE html>
<html>
<head>
	
	<title></title>
	<style type="text/css">
		#map-{{$id_map}},#panel-x-{{$id_map}}{
			min-height: 500px;
		}

		#map-{{$id_map}} .load{
			vertical-align: middle;
		} 

	</style>
</head>
<body>


    <div class="row no-gutter">
    	<div class="col-md-9 col-xs-9 col-sm-9">
	    	<div class="" id="map-{{$id_map}}" style="background: #32a6db">
		    	<div class="load">
		    		<h5 class="text-center">LOADING MAP ..</h5>
		    	</div>
	    	</div>
    	</div>
    	<div class="col-md-3 col-xs-3 col-sm-3 text-dark">
    		<div class="panel" id="panel-x-{{$id_map}}">
    			<div class="panel-body">
    				<ul class="list-group">
    					
    				</ul>
    			</div>
    		</div>
    	</div>
    </div>

<script type="text/javascript" src="{{asset('L_MAP/asset/jq.js?v='.date('i'))}}"></script>

<script type="text/javascript" src="{{asset('L_MAP/asset/hi.js?v='.date('i'))}}"></script>
<script type="text/javascript" src="{{asset('L_MAP/asset/proj4.js?v='.date('i'))}}"></script>

<script type="text/javascript" src="{{asset('L_MAP/ind/ind.js?v='.date('i'))}}"></script>
<script type="text/javascript" src="{{asset('L_MAP/ind/kota.js?v='.date('i'))}}"></script>

<script type="text/javascript">
	var series_{{$id_map}}=[];
	var series_visible_one_{{$id_map}}=1;
	var map_init_{{$id_map}}={};

	$.get('{{$data_name_path}}',function(res){
		$('#map-{{$id_map}}').html('');
		LAYER=[];

		for(var i in res.series){


			series_{{$id_map}}.push({
			    data: res.series[i].data,
			    keys: ['id', 'nama', 'value','cat','link','color', 'tooltip'],
			    name: res.series[i].name_data,
			    joinBy: 'id',
			    type:'map',
			    visible:series_visible_one_{{$id_map}}?true:false,
			    mapData:Highcharts.maps[res.series[i].mapData_name],
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

			$('#panel-x-{{$id_map}} .list-group').append(
			'<li class="list-group-item">'+
				res.series[i].name_layer
			+'</li>'
			);

			series_visible_one_{{$id_map}}=0;




		}


		map_init_{{$id_map}}=Highcharts.mapChart('map-{{$id_map}}', {
                chart: {
                    // map: 'idn',
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                },
                title: {
                    text: res.title,
                    style:{
                        color:'#fff'
                    }
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
                        return (this.point.tooltip == undefined ? (this.point.integrasi !== undefined ? this.point.integrasi : this.point.nama) : this.point.tooltip); 
                    }
                },
                 mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'bottom'
                    }
                },
                series:series_{{$id_map}}

            });


		
			

	});
</script>


</body>
</html>