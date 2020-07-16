<div id="map_ikfd" style="width:100%"></div>


<script type="text/javascript">
	@php

		$id_map='map_'.rand(1,5);

	@endphp
	var series_{{$id_map}}=[];
	var  {{$id_map}}={

		series:<?php echo json_encode($data); ?>
	}

	series_{{$id_map}}.push({
		name:'poligon',
		mapData:Highcharts.maps['ind_kota'],
		type:'map',
		showInLegend:false,
		


	});

	for(var i in {{$id_map}}.series){
			series_{{$id_map}}.push({
			    data: {{$id_map}}.series[i].data,
			    keys: ['id', 'nama', 'value','cat','link','color', 'tooltip'],
			    name: {{$id_map}}.series[i].nama+' ('+{{$id_map}}.series[i].data.length+')',
			    color: {{$id_map}}.series[i].color,
			    joinBy: ['id','id'],
			    type:'map',
			    allAreas:false,
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
			        enabled: false,
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
		chart=Highcharts.mapChart('map_ikfd',{
                chart: {
                   
                     backgroundColor: 'transparent'
                },
                title: {
                    text: 'IKFD PEMDA {{$tahun}}',
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
                		bubbleLegend:{
                			color:'#fff'
                		},
                		itemStyle:{
                			color:'#fff'
                		},
                        align: 'center',
                        title:{
                        	text: 'KATEGORI IKFD',
                          style:{
                        		color:'#fff'
                        }
                    }
                 },
                credits: {
                    enabled: false
                },
      //            mapNavigation:{
				  //   enabled:true,
				  //   enableButtons:true,
				  //   enableDoubleClickZoom:false,
				  //   enableMouseWheelZoom:false,
				  //   enableTouchZoom:true,
				  // },
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
             
                series:series_{{$id_map}}

                

            }


	 );



	},1000);


	var types = ['DOMMouseScroll', 'mousewheel'];


// function handler(event) {
//     var orgEvent = event || window.event, args = [].slice.call( arguments, 1 ), delta = 0, returnValue = true, deltaX = 0, deltaY = 0;
//     event = $.event.fix(orgEvent);
//     event.type = "mousewheel";
    
//     // Old school scrollwheel delta
//     if ( event.wheelDelta ) { delta = event.wheelDelta/120; }
//     if ( event.detail     ) { delta = -event.detail/3; }
    
//     // New school multidimensional scroll (touchpads) deltas
//     deltaY = delta;
    
//     // Gecko
//     if ( orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS ) {
//         deltaY = 0;
//         deltaX = -1*delta;
//     }
    
//     // Webkit
//     if ( orgEvent.wheelDeltaY !== undefined ) { deltaY = orgEvent.wheelDeltaY/120; }
//     if ( orgEvent.wheelDeltaX !== undefined ) { deltaX = -1*orgEvent.wheelDeltaX/120; }
    
//     // Add event and delta to the front of the arguments
//     args.unshift(event, delta, deltaX, deltaY);
    
//     return $.event.handle.apply(this, args);

// }

// $.event.special.mousewheel = {
//     setup: function() {
//         if ( this.addEventListener ) {
//             for ( var i=types.length; i; ) {
//                 this.addEventListener( types[--i], handler, false );
//             }
//         } else {
//             this.onmousewheel = handler;
//         }
//     },
    
//     teardown: function() {
//         if ( this.removeEventListener ) {
//             for ( var i=types.length; i; ) {
//                 this.removeEventListener( types[--i], handler, false );
//             }
//         } else {
//             this.onmousewheel = null;
//         }
//     }
// };

// $.fn.extend({
//     mousewheel: function(fn) {
//         return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
//     },
    
//     unmousewheel: function(fn) {
//         return this.unbind("mousewheel", fn);
//     }
// });


  

// var zoomRatio = 1;
// var lastX;
// var lastY;
// var mouseDown;

// Highcharts.setOptions({
//     lang: {
//         resetZoom: ''
//     }
// });

// function createData() {
//     var arr = [];
//     for (var i = 0; i < 200; i++) {
//         arr.push(Math.random()*100);
//     }
//     return arr;
// }


// var setZoom = function() {

//     var xMin = chart.xAxis[0].getExtremes().dataMin;
//     var xMax = chart.xAxis[0].getExtremes().dataMax;
//     var yMin = chart.yAxis[0].getExtremes().dataMin;
//     var yMax = chart.yAxis[0].getExtremes().dataMax;
//     console.log(xMax);
   
//     chart.xAxis[0].setExtremes(xMin + (1 - zoomRatio) * xMax, xMax * zoomRatio);
//     chart.yAxis[0].setExtremes(yMin + (1 - zoomRatio) * yMax, yMax * zoomRatio);
// };

// $("#map_ikfd").mousewheel(function(objEvent, intDelta) {
//     if (intDelta > 0) {
//         if (zoomRatio > 0.7) {
//             zoomRatio = zoomRatio - 0.1;
//             setZoom();
//         }
//     }
//     else if (intDelta < 0) {
//         zoomRatio = zoomRatio + 0.1;
//         setZoom();
//     }
// });



// $('#resetZoom').click(function() {

//     var xExtremes = chart.xAxis[0].getExtremes;
//     var yExtremes = chart.yAxis[0].getExtremes;
//     chart.xAxis[0].setExtremes(xExtremes.dataMin, xExtremes.dataMax);
//     chart.yAxis[0].setExtremes(yExtremes.dataMin, yExtremes.dataMax);
//     zoomRatio = 1;
    
// });

// $('#map_ikfd').mousedown(function() {
//     mouseDown = 1;
// });

// $('#map_ikfd').mouseup(function() {
//     mouseDown = 0;
// });

// $('#map_ikfd').mousemove(function(e) {
//     if (mouseDown == 1) {
//         if (e.pageX > lastX) {
//             var diff = e.pageX - lastX;
//             var xExtremes = chart.xAxis[0].getExtremes();
//             chart.xAxis[0].setExtremes(xExtremes.min - diff, xExtremes.max - diff);
//         }
//         else if (e.pageX < lastX) {
//             var diff = lastX - e.pageX;
//             var xExtremes = chart.xAxis[0].getExtremes();
//             chart.xAxis[0].setExtremes(xExtremes.min + diff, xExtremes.max + diff);
//         }

//         if (e.pageY > lastY) {
//             var ydiff = 1 * (e.pageY - lastY);
//             var yExtremes = chart.yAxis[0].getExtremes();
//             chart.yAxis[0].setExtremes(yExtremes.min + ydiff, yExtremes.max + ydiff);
//         }
//         else if (e.pageY < lastY) {
//             var ydiff = 1 * (lastY - e.pageY);
//             var yExtremes = chart.yAxis[0].getExtremes();
//             chart.yAxis[0].setExtremes(yExtremes.min - ydiff, yExtremes.max - ydiff);
//         }
//     }
//     lastX = e.pageX;
//     lastY = e.pageY;
// });
    
</script>