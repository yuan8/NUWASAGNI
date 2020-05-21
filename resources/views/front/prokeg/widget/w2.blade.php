<?php
	$dom='widget_2_'.rand(0,100);
?>

<h5><b>RKPD PELAPORAN (AIR MINUM) PER-PROVINSI TAHUN {{HP::fokus_tahun()}} </b></h5>

 <style type="text/css">
 	#{{$dom}} tr th, #{{$dom}} tr td{
 		font-size:10px;
 	}
 </style>

		<table class="table table-condensed table-bordered table-hover" id="{{$dom}}" >
			<thead>
				<tr>
					<th>KODE</th>
					<th>NAMA DAERAH</th>
					<th>JUMLAH DAERAH</th>
					<th>JUMLAH DAERAH MELAPOR</th>
					<th>TOTAL JUMLAH KEGIATAN </th>
					<th>PERSENTASE PELAPORAN</th>
					<th>ACTION</th>


				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>


<script type="text/javascript">
	
$(function(){
	var {{$dom}}=$('#{{$dom}}').DataTable({
		pagingType:'simple',
		pageLength:5,
		bLengthChange:false,
        dom: 'Bfrtip',
     	 buttons: [
            {
                extend: 'excelHtml5',
                text: 'DOWNLOAD EXCEL',
                className:'btn btn-success btn-xs',
                messageTop: 'RKPD PELAPORAN (AIR MINUM) PER-PROVINSI TAHUN {{HP::fokus_tahun()}}',
                exportOptions: {
                    columns: [ 0,1,2,3,4 ]
                }
            },
        ],
		columns:[
			{
				data:'kode',
				orderable:false,
			},
			{
				data:'nama_daerah',
				orderable:false,
			},
			{
				data:'jumlah_daerah',
				type:'currency',
				render:function(data){
					return formatNumber(data,0);
				}
				
			},
			{
				data:'jumlah_daerah_melapor',
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
			
			{	type:'currency',
				render:function(data,type,dataRow){
					var per=(dataRow.jumlah_daerah_melapor / dataRow.jumlah_daerah)*100;
					if(Number.isNaN(per)){
						return '0%';
					}else{
					return formatNumber(per,2)+'%';

					}	

				}
			},
			{

				type:'html',
				orderable:false,

				render:function(data,type,dataRow){
					return "<a class='btn btn-primary btn-xs' href='{{route('pr.table')}}/?provinsi="+dataRow.kode+"'>DETAIL</a>";
				}
			}
		]
	});

	API_CON.get("{{route('web_api.prokeg.w2')}}").then(function(res){
		{{$dom}}.rows.add(res.data).draw();
	});

	},500);
</script>