@extends('adminlte::page')


@section('content_header')
<div class="row bg-navy">
	<div class="col-md-12">
		<h5 class="text-center">TIPOLOGI DINAS {{$daerah->nama_daerah}} </h5>
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
  				<table class="table-bordered table" id="table_daerah">
  					<thead>
  						<tr>
                <th>DINAS</th>
                <th>TIPOLOGI</th>
               
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
              messageTop: 'TIPOLOGI {{$daerah->nama_daerah}}',
              exportOptions: {
                  columns: [0,1]
              }
          },
      ],
		 drawCallback: function () {
     }, 
		createdRow:function(row,data,dataIndex,cells){
		
		},
		columns:[
			{
				data:'pemda',
        orderable:false,

				createdCell: function (td, cellData, rowData, row, col) {
					$(td).addClass(' hover-bg-td '+(cellData.length<3?'bg-primary':''));
			    },
			},
			{
				data:'dinas',
        orderable:false,

			}
			
		
		]
	});


	table_daerah.rows.add(data_source).draw();

</script>

@stop