@extends('adminlte::page')


@section('content_header')
<div class="row bg-navy">
	<div class="col-md-12">
		<h5 class="text-center">TIPOLOGI DINAS DAERAH NUWAS {{HP::fokus_tahun()}}</h5>
	</div>
</div>

@stop

@section('content')
  
  <div class="row no-gutter">
  	<div class="col-md-12">
  		<div class="box box-primary">
  			<div class="box-header">
  			
  			</div>
  			<div class="box-body">
  				<table class="table-bordered table" id="table_daerah" style="width: 100%">
  					<thead>
  						<tr>
		                <th>KODE DAERAH</th>
		                <th>NAMA DAERAH</th>
		                <th>NAMA PROVINSI</th>
		                <th>DINAS</th>
		                <th>TIPOLOGI</th>
		                <th>KETERANGAN</th>

  						</tr>
  					</thead>
  					<tbody></tbody>
  					<tfoot>
  						<tr>
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
	var table_daerah=$('#table_daerah').DataTable({
    dom: 'Bfrtip',
      buttons: [
          {
              extend: 'excelHtml5',
              text: 'DOWNLOAD EXCEL',
              className:'btn btn-success btn-xs',
              messageTop: 'TIPOLOGI DAERAH NUWAS {{HP::fokus_tahun()}}',
              exportOptions: {
                  columns: [0,1,3,4,5]
              }
          },
      ],
      "order": [[ 0, 'asc' ]],
      columnDefs:[
            { "visible": false, "targets": 0 },
            { "visible": false, "targets": 1 },
            { "visible": false, "targets": 2 },
        
      ],
		drawCallback: function () {

			$("#table_daerah thead").css('display','none');
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var rows_data= api.rows( {page:'current'} ).data();

            var last=null;
 
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {

                if ( last !== group ) {
                    var dt=(rows_data[i]);
                      $(rows).eq(i).before(
                        '<tr class="bg-navy"><td colspan="1"><b>KODE DAERAH</b> </td>'+
                        '<td><b>NAMA DAERAH</b></td>'+
                        '<td><b>NAMA  PROVINSI</b></td>'+
                        '<td><b>ACTION</b></td>'+
                        '</tr>'+
                        '<tr class="bg-navy"><td colspan="1">'+group+'</td>'+
                        '<td>'+dt.nama_daerah+'</td>'+
                        '<td>'+dt.nama_provinsi+'</td>'+
                        '<td><a class="btn btn-warning btn-xs text-dark" href="'+
                        dt.link_detail
                        +'">DETAIL TIPOLOGI</a></td>'+
                        '</tr>'+
                        '<tr class="bg-yellow text-dark">'+
                        '<td class="bg-info"><b></b></td>'+
                        '<td class="" ><b>DINAS</b></td>'+

                        '<td ><b>TIPOLOGI</b> </td>'+
                        '<td ><b>KETERANGAN</b> </td>'+


                        '</tr>'
                    );

                    last=group;
                }
            });
     }, 
		createdRow:function(row,data,dataIndex,cells){
		
		},
		columns:[
			{
				data:'kode_daerah',
        		orderable:false,

				
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
				data:'pemda',
       				 orderable:false,

				
			},
			{
				data:'dinas',
		        orderable:false,
		         

			},
			{
				data:'keterangan',
		        orderable:false,
		          createdCell: function (td, cellData, rowData, row, col) {
			          $(td).parent().addClass('bg-warning');
			           $(td).parent().addClass('bg-warning');
			          $(td).parent().prepend('<td class="bg-info"></td>');


			        },

			}
			
		
		]
	});


	table_daerah.rows.add(data_source).draw();

</script>

@stop