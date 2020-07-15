@extends('adminlte::dash')


@section('content_header')
    <h1>RPJMN BERLAKU TAHUN {{HP::fokus_tahun()}}</h1>
@stop

@section('content')
<hr>
<div class="row">
<div class="col-md-3">
		<div class="form-group">
			<label>PN</label>
			<select id="select_pn" class="form-control use-select-2-def trigger-1" data-index='1'>
				@foreach($data as $d)
				<option value="{{$d->id}}">{{$d->nama}}</option>
				@endforeach
			</select>
		</div>
	</div>
<div class="col-md-3">
		<div class="form-group">
			<label>PP</label>
			<select id="select_pp" class="form-control use-select-2-def trigger-1"  data-index='2'>
				
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>KP</label>
			<select id="select_kp" class="form-control use-select-2-def trigger-1"  data-index='3'>
			
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>PROPN</label>
			<select id="select_propn" class="form-control use-select-2-def trigger-1"  data-index='4'>
			
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>PROYEK</label>
			<select id="select_pronas" class="form-control use-select-2-def trigger-1"  data-index='5'>
			
			</select>
		</div>
	</div>
</div>


<div class="row no-gutter " style="background: #fff">
	<div class="col-md-5">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h5><b>TURUNAN</b></h5>
			</div>
			<div class="box-body">
				<table class="table table-bordered" id="table_turunan">
					<thead>
						<tr>
							<th>
								URUIAN
							</th>
						</tr>
					</thead>
				</table>
				
			</div>
		</div>
	</div>
	<div class="col-md-7">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h5><b>INDIKATOR</b></h5>
			</div>
			<div class="box-body">
				<table class="table table-bordered" id="table_indikator">
					<thead>
						<tr>
							<th>
								URUIAN
							</th>
							<th>
								TARGET {{HP::fokus_tahun()}}
							</th>
							<th>
								AGGARAN
							</th>
						</tr>
					</thead>
				</table>
				
			</div>
		</div>
	</div>
</div>
@stop

@section('js')
<script type="text/javascript">
	var table_turunan=$('#table_turunan').DataTable({
		sort:false,
		columns:[
			{
				data:'nama',

			}
		]
	});


	var table_indikator=$('#table_indikator').DataTable({
		sort:false,
		columns:[
			{
				data:'nama',

			},
			{
				data:'target',
				render:function(data,type,dataRows){
					return data+' '+(dataRows.satuan?dataRows.satuan:'');
				}

			},{
				data:'anggaran'
			}
		]
	});


	$('.trigger-1').on('change',function(){
		var index_select=parseInt($(this).attr('data-index'));
		var value=$(this).val();

		if(value!=''){
			API_CON.post('{{route('web_api.d.rpjmn.turunan')}}',{index:index_select,value:value}).then(function(res,err){
				if(res.data){

					for (var i =(index_select+1); i<=5; i++) {
						$('select[data-index="'+i+'"].trigger-1').val(null);
						$('select[data-index="'+i+'"].trigger-1').html('');
					}

					

					if(table_turunan.data()){
						table_turunan.clear().draw();
					}
						table_turunan.rows.add(res.data.turunan).draw();


					if(table_indikator.data()){
						table_indikator.clear().draw();
					}
						table_indikator.rows.add(res.data.indikator).draw();

					

						

					if($('select[data-index="'+(index_select+1)+'"].trigger-1').html()!=undefined){
							$('select[data-index="'+(index_select+1)+'"].trigger-1').append('<option value="">PILIH</option>');

							for(var i in res.data.turunan){


								$('select[data-index="'+(index_select+1)+'"].trigger-1').append('<option value="'+res.data.turunan[i].id+'">'+res.data.turunan[i].nama+'</option>');
							}
					}



				}
			});
		}
		

	});

	setTimeout(function(){
	$('#select_pn').trigger('change');

	},500);
</script>

@stop