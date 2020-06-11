@extends('adminlte::dash')


@section('content_header')
    <h1>RENJA BERLAKU TAHUN {{HP::fokus_tahun()}} </h1>
@stop

@section('content')
<hr>
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body">
				<table class="table table-bordered table-striped" id="table_data">
					<thead>
						<tr>
							<th rowspan="2">
								NAMA DAERAH
							</th>
							<th colspan="2">
								BERLAKU
							</th>
							<th rowspan="2">ACTION</th>

						</tr>
						<tr>
							<th>TAHUN AWAL</th>
							<th>TAHUN AHIR</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


@stop


@section('js')
<script type="text/javascript">
	var table_data=$('#table_data').DataTable({
		sort:false,
		columns:[
			{
				data:'nama_daerah',
			},
			{
				data:'tahun'
			},
			{
				data:'tahun_selesai',
			},
			{
				render:function(data,type,dataRaw){
					return '<button class="btn btn-danger btn-xs">DELETE</button><a class="btn btn-success btn-xs" >UPDATE</a><a class="btn btn-info btn-xs" >VIEW</a>';

				}
			}

		]
	});

	var data_source=<?php echo json_encode($data)?>;

	$(function(){

		table_data.rows.add(data_source).draw();

	});

</script>

@stop