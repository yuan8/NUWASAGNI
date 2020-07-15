<?php
	$dom='widget_1_'.rand(0,100);
?>

<h5><b>RKPD DAERAH (AIR MINUM) TAHUN {{HP::fokus_tahun()}} </b></h5>

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
					<th>JUMLAH PROGRAM</th>
					<th>JUMLAH KEGIATAN</th>
					<th>JUMLAH ANGGARAN</th>
					<th>PERSENTASE ANGGARAN</th>
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
                messageTop: 'RKPD DAERAH (AIR MINUM) TAHUN {{HP::fokus_tahun()}}',
                exportOptions: {
                    columns: [ 0,1,2,3,4,5,6 ]
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
			{	type:'currency',
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

				type:'html',
				orderable:false,

				render:function(data,type,dataRow){
					return "<a class='btn btn-primary btn-xs' target='_blank' href='{{route('pr.data',['id'=>null])}}/"+dataRow.kode+"'>DETAIL</a>";
				}
			}
		]
	});

	API_CON.get("{{route('web_api.prokeg.w1')}}").then(function(res){
		{{$dom}}.rows.add(res.data).draw();
	});

	},500);
</script>