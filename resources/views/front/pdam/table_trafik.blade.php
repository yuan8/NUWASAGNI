<?php
	$id_dom='table_trafik_'.rand(0,2000);
 ?>
 <style type="text/css">
 	#{{$id_dom}} tr th, #{{$id_dom}} tr td{
 		font-size:10px;
 	}
 </style>
 <h5>
 	<b>
 		@isset($status)
 			@if($status==1)
 				KATEGORI PDAM  TRAFIK NAIK
 			@elseif($status==0)
 				KATEGORI PDAM  TRAFIK STABIL

 			@else
 				KATEGORI PDAM  TRAFIK TURUN

 			@endif
 		@endisset
 	</b>
 </h5>
<table class="table table-bordered" id="{{$id_dom}}">
    <thead>
        <tr>
            <th>NAMA PDAM</th>
            <th>DAERAH</th>
            <th>PENILAIAN NUWAS</th>
            <th>TRAFIK KATEGORI</th>

        </tr>
    </thead>
</table>
<script type="text/javascript">
$(function(){
		var {{$id_dom}} =$('#{{$id_dom}}').DataTable({
		sort:false,
		pagingType:'simple',
		pageLength:3,
		bLengthChange:false,
		columns:[
			{
				data:'nama_pdam',
			},
			{
				data:'nama_daerah',
			},
			{
				data:'kategori_pdam',
			},
			{
				data:'trafik',
				render:function(data,style,dataRow){
					var icon='fa-arrow-up';
					var bg='';
					var text='';


					switch(parseInt(data)){
						case 1:
						icon='fa-arrow-up';
						bg="bg-green";
						text='NAIK';

						break;
						case 0:
						 icon='fa-minus ';
						bg="bg-yellow";
						text='STABIL';



						break;
						case -1:
						 icon='fa-arrow-down';
						bg="bg-maroon";
						text='TURUN';



						break;
					}

					return "<a target='_blank' href='{{route('p.laporan_sat',['id'=>null])}}/"+dataRow.kode_daerah+"' class='btn btn-circle btn-xs "+bg+"'><i class='fa "+icon+"'></i> <span>"+text+"</span></a>";
				}
			}
		]
	});

	API_CON.get("{{route('web_api.pdam.trafik',['status'=>isset($status)?$status:null,'target_nuwas'=>isset($target_nuwas)?$target_nuwas:null])}}").then(function(res){
		{{$id_dom}}.rows.add(res.data).draw();
	});
});

</script>