@extends('adminlte::page')


@section('content_header')
<h5 class="text-center">PROGRAM KEGIATAN DAERAH TAHUN {{HP::fokus_tahun()}}</h5>
  <p class="text-center"><small>AIR MINUM</small></p>

@stop

@section('content')
  
  <div class="row no-gutter">
  	<div class="col-md-12">
  		<div class="box box-primary">
  			<div class="box-header">
  				<div class="row">
  					<div class="col-md-3">
  						<div class="form-group">
  							<label>KAT</label>
  							<select id="kat_daerah_filter" class="filter form-control">
  								<option value="">SEMUA</option>
  								<option value="P">PROVINSI</option>
  								<option value="K">KOTA</option>

  							</select>
  						</div>
  					</div>
  					<div class="col-md-3">
  						<div class="form-group">
  							<label>MEMILIKI KEGIATAN</label>
  							<select id="value_filter" class="filter form-control">
  								<option value="">SEMUA</option>
  								<option value="1">MEMILIKI</option>
  								<option value="0">TIDAK MEMILIKI</option>

  							</select>
  						</div>
  					</div>
  					<div class="col-md-3">
  						<div class="form-group">
  							<label>TARGET NUWAS</label>
  							<select id="target_nuwas_filter" class="filter form-control">
  								<option value="">SEMUA</option>
  								<option value="1">YA</option>
  								<option value="0">TIDAK MEMILIKI</option>

  							</select>
  						</div>
  					</div>
  					<div class="col-md-3">
  						<div class="form-group">
  							<label>PROVINSI</label>
  							<select id="provinsi_filter" class="filter form-control">
  								<option value="">SEMUA</option>
  								<?php foreach ($provinsi as $key => $d): ?>
  									<option value="{{$d->id}}" {{isset($_GET['provinsi'])?($_GET['provinsi']==$d->id?'selected':''):''}}>{{$d->nama}}</option>
  									
  								<?php endforeach ?>

  							</select>
  						</div>
  					</div>
  				</div>
  			</div>
  			<div class="box-body">
  				<table class="table-bordered table" id="table_daerah">
  					<thead>
  						<tr>
  							<th>KODE</th>
  							<th>NAMA DAERAH</th>
  							<th>TARGET NUWAS</th>
                <th>JUMLAH PROGRAM</th>
  							<th>JUMLAH KEGIATAN</th>
                <th>JUMLAH ANGGARAN</th>
                <th>PERSENTASE ANGGARAN</th>
  							<th>ACTION</th>

  						</tr>
  					</thead>
  					<tbody></tbody>
  					<tfoot>
  						<tr>
  							<th></th>
  							<th></th>
  							<th></th>

  							<th></th>
  							<th></th>
                <th></th>

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
              messageTop: 'RKPD AIR MINUM  TAHUN {{HP::fokus_tahun()}}',
              exportOptions: {
                  columns: [ 0,1,2,3,4,5,6 ]
              }
          },
      ],
		 drawCallback: function () {
	      var api = this.api();
		     $( api.table().column(0).footer() ).html('TOTAL');
		     
         $(api.table().column(3).footer()).html(
		     		api.column(3, {"filter": "applied"}).data().sum().toFixed(2)
		     );	

          $(api.table().column(4).footer()).html(
            api.column(4, {"filter": "applied"}).data().sum().toFixed(2)
         ); 

          $(api.table().column(5).footer()).html(
            api.column(5, {"filter": "applied"}).data().sum().toFixed(2)
         ); 

          for(var i=3;i<6;i++){
             $(api.table().column(i).footer()).html(
                formatNumber($(api.table().column(i).footer()).html(),0)
              ); 
          }
		  },
		createdRow:function(row,data,dataIndex,cells){
			if(data.jumlah_kegiatan==0){
				$(row).addClass("bg-danger");
			}
		},
		columns:[
			{
				data:'id',
        orderable:false,

				createdCell: function (td, cellData, rowData, row, col) {
					$(td).addClass(' hover-bg-td '+(cellData.length<3?'bg-primary':''));
			    },
			},
			{
				data:'nama_daerah',
        orderable:false,

			},
			{
				data:'target_nuwas',
        orderable:false,

				render:function(data){
					if(data!=null){
						return data.jenis_bantuan.replace(/@/g,' ');

					}else{
						return '';
					}
				}
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
      { type:'currency',
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
        orderable:false,
        
				render:function(data,type,dataRow){
					if(dataRow.jumlah_kegiatan>0){
						return '<a target="_blank" href="{{route('pr.data',['id'=>null])}}/'+dataRow.id+'" class="btn btn-primary btn-xs">DETAIL</a>';
					}else{
						return '';
					}
				}
			},
      
		]
	});


	table_daerah.rows.add(data_source).draw();

	$.fn.dataTable.ext.search.push(
       function( settings, dt, index,data ) {
        var approve=true;
        	if(approve){

		        if($('#kat_daerah_filter').val()!=''){

		        		if($('#kat_daerah_filter').val()=='P'){
			        		if(data.id.length>3){
			        			approve=false;
			        		}
		        		}else if($('#kat_daerah_filter').val()=='K'){
		        			if(data.id.length<3){
			        			approve=false;
			        		}
		        		}
		        	
		        }
		    }

		    if(approve){

		        if($('#value_filter').val()!=''){

		        		if($('#value_filter').val()==1){
			        		if(data.jumlah_kegiatan==0){
			        			approve=false;
			        		}
		        		}else if($('#value_filter').val()==0){
		        			if(data.jumlah_kegiatan!=0){
			        			approve=false;
			        		}
		        		}
		        	
		        }
		    }

		     if(approve){

		        if($('#target_nuwas_filter').val()!=''){

		        		if($('#target_nuwas_filter').val()==1){
			        		if(data.target_nuwas==null){
			        			approve=false;
			        		}
		        		}else if($('#target_nuwas_filter').val()==0){
		        			if(data.target_nuwas!=null){
			        			approve=false;
			        		}
		        		}
		        	
		        }
		    }


        if(approve){
        	if($('#provinsi_filter').val()!=''){

        		if((data.kode_daerah_parent==$('#provinsi_filter').val())||(data.id==$('#provinsi_filter').val())){
        		}else{
        			approve=false

        		}
        	}
        }

        if(approve){
        	return true;
        }else{
        	return false;
        }

        	

    });

    $('.filter').on('change',function(){
    	table_daerah.draw();
    });

    $($('.filter')[0]).trigger('change');

</script>

@stop