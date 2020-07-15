@extends('adminlte::page')


@section('content_header')
<div class="row bg-navy">
	<div class="col-md-12">
		<h5 class="text-center">PROGRAM {{$daerah->nama .' / '.$daerah->nama_provinsi}} TAHUN {{HP::fokus_tahun()}}  - <small class="text-white">AIR MINUM</small> </h5>
	</div>
</div>

@stop

@section('content')
  
  <div class="row no-gutter">
  	<div class="col-md-12">
  		<div class="box box-primary">
  		  
  			<div class="box-body">
  				<table class="table-bordered table" id="table_daerah" style="width:100%">
  					<thead>
  						<tr>
  							<th>KODE</th>
  							<th>NAMA KEGIATAN</th>
                <th>ANGGARAN</th>
                <th>SUMBER DANA APBD</th>
                <th>ID INDIKATOR</th>
                <th>NAMA INDIKATOR</th>
                <th>TARGET</th>
                <th>SATUAN</th>
  						</tr>
  					</thead>
  					<tbody></tbody>
  					<tfoot>
  						{{-- <tr>
  							<th></th>
  							<th></th>
  							

  						</tr> --}}
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
              messageTop: 'PROGRAM {{$daerah->nama .' / '.$daerah->nama_provinsi}} TAHUN {{HP::fokus_tahun()}}',
              exportOptions: {
                  columns: [0,1,2,3,4,5,6,7]
              }
          },
      ],
      "order": [[ 0, 'asc' ]],
      columnDefs:[
            { "visible": false, "targets": 0 },
            { "visible": false, "targets": 1 },
            { "visible": false, "targets": 2 },
            { "visible": false, "targets": 3 },



      ],
		drawCallback: function ( settings ) {
            $("#table_daerah thead").css('display','none');
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var rows_data = api.rows( {page:'current'} ).data();

            var last=null;
 
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {

                if ( last !== group ) {
                    var dt=(rows_data[i]);
                    $(rows).eq( i ).before(
                        '<tr class="bg-navy"><td colspan="1"><b>KODE KEGIATAN</b> </td>'+
                        '<td><b>NAMA KEGIATAN</b></td>'+
                        '<td><b>JUMLAH  ANGGARAN</b></td>'+
                        '<td><b>JUMLAH  ANGGARAN APBD</b></td>'+
                        '<td><b>ACTION</b></td>'+
                        '</tr>'+
                        '<tr class="bg-navy"><td colspan="1">'+dt.kode_kegiatan+'</td>'+
                        '<td>'+dt.nama_kegiatan+'</td>'+
                        '<td>'+formatNumber(dt.jumlah_anggaran,3)+'</td>'+
                        '<td>'+formatNumber(dt.jumlah_anggaran_apbd,3)+'</td>'+
                        '<td>'+
                        // '<a class="btn btn-warning btn-xs text-dark" href="'+
                        // dt.link_detail
                        // +
                        // '">DETAIL KEGIATAN</a>'+
                        '</td>'+

                        '</tr>'+
                        '<tr class="bg-yellow text-dark">'+
                        '<td class="bg-info"><b></b></td>'+
                        '<td class=""><b>KODE INDIKATOR</b></td>'+

                        '<td colspan="1"><b>NAMA INDIKATOR</b> </td>'+
                        '<td><b>TARGET</b></td>'+
                        '<td><b>SATUAN</b></td>'+

                        '</tr>'
                    );


                    last = group;
                }
            } );
    },
		createdRow:function(row,data,dataIndex,cells){
			// if(data.jumlah_kegiatan==0){
			// 	$(row).addClass("bg-danger");
			// }
		},
		columns:[
			{
				data:'kode_kegiatan',
        orderable:false,

			},
			{
				data:'nama_kegiatan',
        orderable:false,

			},
      {
        data:'jumlah_anggaran',
        orderable:false,

      },
      {
        data:'jumlah_anggaran_apbd',
        orderable:false,

      },
      {
        data:'kode_indikator',
        orderable:false,

      },
       {
        data:'nama_indikator',
        orderable:false,
        

      },
      {
        render:function(data,type,dataRow){
          if(dataRow.target_ahir!=null){
            return dataRow.target_awal+' Hingga '+dataRow.target_ahir;
          }else{

            return dataRow.target_awal;
          }
          
        }
      },
      {
        data:'satuan',
        createdCell: function (td, cellData, rowData, row, col) {
          $(td).parent().addClass('bg-warning');
          $(td).parent().prepend('<td class="bg-info"></td>');

        },
      }
		]
	});


	table_daerah.rows.add(data_source).draw();


</script>

@stop