<div id="map_ikfd" style="width:100%"></div>


<script type="text/javascript">
	@php

		$id_map='map_'.rand(1,5);

	@endphp
	var series_{{$id_map}}=[];
	var  {{$id_map}}={

		series:<?php  echo json_encode($data); ?>
	}

	for(var i in {{$id_map}}.series){
			series_{{$id_map}}.push({
			    data: {{$id_map}}.series[i].data,
			    keys: ['id', 'nama', 'value','cat','link','color', 'tooltip'],
			    name: {{$id_map}}.series[i].nama,
			    joinBy: ['id','id'],
			    type:'map',
			    // allAreas:false,
			    map:{
			    	data:{
			    		events:{
			    			click:function(){
			    				console.log(this);
			    			}
			    		}
			    	}
			    },
			    point:{
			    	events:{
			    		click:function(){
			    			console.log(this);
			    			if(this.options.link!=undefined){
			    				if(this.options.link!=null){
			    					window.open(this.options.link,'_blank');
			    				}
			    			}

			    		}
			    	}
			    },
			    mapData:Highcharts.maps['ind_kota'],
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
	}
	
	setTimeout(function(){
		Highcharts.mapChart('map_ikfd',{
                chart: {
                    // map: 'idn',
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                },
                title: {
                    text: '',
                    style:{
                        color:'#fff'
                    }
                },
                subtitle:{
                	text:'',
                	style:{
                        color:'#fff',

                    },
                	enabled:true
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
                    		link_click='';
                    	}

                        return (((this.point.tooltip == null)||(this.point.tooltip == ""))? this.point.name: this.point.tooltip)+'<br>'+link_click; 
                    }
                },
                plotOptions:{
                	series:{
                		point:{
                			cursor:"pointer",
	                		events:{
	                			click:function(){
	                				if((this.link!="")&&(this.link!=null)){
	                					window.location.href=this.link;
	                				}
	                			}
	                		}
                		}
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

                

            }


	 );



	},1000);
</script>