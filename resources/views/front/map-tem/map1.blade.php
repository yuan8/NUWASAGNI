	<div class="row">
		<div class="col-md-12" id="{{$id_dom}}chart_container" style="min-height: 500px; max-width: 100%;">
		
	</div>
	</div>	
    	

  <div id="{{$id_dom}}chart_container_next" >
  	<div class="row">
  		<div class="col-md-12">
  			<div class="box box-warning">
  				<div class="box-body">
  					<table class="table-bordered table" id="{{$id_dom}}_table">
  				<thead>
  					<tr>
  						<th>Category</th>
  						@if(in_array($id_dom,['per_kota']) )
  						<th>Detail</th>
  						@endif
  						<th>Jumlah Program</th>
  						<th>Jumlah Kegiatan</th>
  						<th>Jumlah Anggaran</th>
  					</tr>
  				</thead>
  				<tbody>
  					@foreach($data['data'] as $d)
  					<tr>
  						<td>{{$d->nama}}</td>
  						@if(in_array($id_dom,['per_kota']) )
  						<td><a href="{{route('pr.data',['id'=>$d->id])}}" target="_blank" class="btn btn-primary btn-xs">Detail</a></td>
  						@endif
  						<td>{{$d->jumlah_program}}</td>
  						<td>{{$d->jumlah_kegiatan}}</td>
  						<td>{{$d->jumlah_anggaran}}</td>

  					</tr>

  					@endforeach
  				</tbody>
  			</table>
  				</div>
  			</div>
  		</div>
  	</div>
    		
   </div>

  <script type="text/javascript">

  $('#{{$id_dom}}_table').DataTable({
      dom: 'Bfrtip',
      buttons: [
          {
              extend: 'excelHtml5',
              text: 'DOWNLOAD EXCEL',
              className:'btn btn-success btn-xs',
              messageTop: 'RKPD AIR MINUM  TAHUN {{HP::fokus_tahun()}}',
              exportOptions: {
              }
          },
      ],
  });

	var {{$id_dom}}data_r=<?php echo json_encode($data['data']) ?>;
	Highcharts.chart('{{$id_dom}}chart_container', {
    chart: {
        type: 'column',
         scrollablePlotArea: {
            scrollPositionX: 1
        },
          mapNavigation: {
            enableMouseWheelZoom: true
        },
        events:{
              click:function(e){

                  var index=parseInt(Math.round(e.xAxis[0].value));
                  if(index<0){
                    index=0;
                  }

                }
        },

    },
  
    title: {
        text: '{{$title}}'
    },
    subtitle: {
        text: 'Sumber Data RKPD'
    },
    // accessibility: {
    //     announceNewData: {
    //         enabled: true
    //     }
    // },
    xAxis: {
     
      categories: <?php  echo json_encode($data['category']); ?>,
      scrollbar:{
        enabled:true
      },
    },
    yAxis: {

        title: {
            text: 'PROGRAM KEGIATAN'
        },
       
    },
    legend: {
        enabled: true
    },
    plotOptions: {
    
        series: {
            // stacking: 'normal',
            // borderWidth: 50,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            },
            point:{
            events:{
              click:function(e){
              		var id={{$id_dom}}data_r[this.index].id;
              		$.get('{{url($next).'/'}}'+id,function(res){
              			$('#{{$id_dom}}chart_container_next').html(res);
              		});
                }
             },

          }
        }
    },

    tooltip: {
      headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
      pointFormatter:function(){
        var val=(this.y).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return '<tr><td style="color:{series.color};padding:0">'+this.series.name+' </td>' +
          '<td style="padding:0"><b>: '+val+'</b></td></tr>'

      },

      footerFormat: '</table>',
      shared: true,
      useHTML: true
  },

    series: <?php echo json_encode($data['series']); ?>,
    
});

  if(scrollbar_active){
      $([document.documentElement, document.body]).animate({
        scrollTop: $("#{{$id_dom}}chart_container").offset().top
    }, 2000);
    }else{
      scrollbar_active=true;
    }


</script>