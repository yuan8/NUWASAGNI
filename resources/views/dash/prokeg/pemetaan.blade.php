@extends('adminlte::dash')


@section('content_header')
  <h1 class=" ">PROGRAM KEGIATAN {{$daerah->nama_daerah}} {{HP::fokus_tahun()}}</h1>
  <p><small>AIR MINUM</small></p>
@stop

@section('content')
<style type="text/css">
	table tr th,table tr td{
		font-size: 10px;
	}
	.link-hover:hover{
		background: #ddd
	}
</style>
<div class="row no-gutter">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body table-responsive">
				<table class="table-condensed table table-bordered">
					<thead>
						<tr>
							<th rowspan="2">KODE</th>
							<th rowspan="2">NAMA PROGRAM</th>
							<th  rowspan="2">NAMA KEGIATAN</th>
							<th  rowspan="2">ANGGARAN</th>
							<th colspan="2">INDIKATOR DAERAH</th>
							<th colspan="2">INDIKATOR PUSAT</th>
							<th rowspan="2">RPJMN</th>

						</tr>
						<tr>
							<th>NAMA</th>
							<th>TARGET</th>
							<th>NAMA</th>
							<th>TARGET</th>

						</tr>
					</thead>
					<tbody>
						<?php 
							$id_k=0;
							$id_p=0;
							$id_ind_p=0;
							$id_ind_k=0;

							$id_indp_ps=0;

						?>
							@if((!empty($d->id_p))AND($id_p!=$d->id_p))
							<tr>
								<td class="text-right"><b>P.{{$d->kode_p}}</b></td>
								<td>{{$d->nama_p}}</td>
								<td colspan="7"></td>
							</tr>
							<?php $id_p=$d->id_p; ?>
							@endif
							@if((!empty($d->id_ind_p))AND($id_ind_p!=$d->id_ind_p))
							<tr>
								<td colspan="4" class="text-right"><b>IP.{{$d->id_ind_p}}</b></td>
								<td><b>(IP)</b> {{$d->ind_p_nama}}</td>
								<td>{{$d->ind_p_target_1}} {{$d->ind_p_satuan}} {{!empty($d->ind_p_target_2)?' - '.$d->$d->ind_p_target_2.' '.$d->ind_p_satuan:''}}</td>
								<td colspan="3"></td>
							</tr>
							<?php $id_ind_p=$d->id_ind_p; ?>
							@endif

							@if((!empty($d->id_k))AND($id_k!=$d->id_k))
							<tr>
								<td colspan="2" class="text-right"><b>K.{{$d->kode_k}}</b></td>
								<td>{{$d->nama_k}}</td>
								<td>{{number_format($d->anggaran_k,0,'.',',')}}</td>
								<td colspan="5"></td>
							</tr>
							<?php $id_k=$d->id_k; ?>
							@endif
						
							@if((!empty($d->id_ind_k))AND($id_ind_k!=$d->id_ind_k))
							<tr>
								<td colspan="4" class="text-right"><b>IK.{{$d->id_ind_k}}</b></td>
								<td><b>(IK)</b> {{$d->ind_k_nama}}</td>
								<td>{{$d->ind_k_target_1}} {{$d->ind_k_satuan}} {{!empty($d->ind_k_target_2)?' - '.$d->$d->ind_k_target_2.' '.$d->ind_k_satuan:''}}</td>
								
								<td colspan="3"></td>
							</tr>
							<?php $id_ind_k=$d->id_ind_k; ?>
							@endif
							
							@if((!empty($d->id_indp_ps))AND($id_indp_ps!=$d->id_indp_ps))
							<tr>
								<td colspan="6" class="text-right"><b>IPS.{{$d->id_indp_ps}}</b></td>
								<td><b>(I{{$d->ind_ps_jenis}})</b> {{$d->ind_ps_nama}}</td>
								<td>{{$d->ind_ps_target_1}} {{$d->ind_ps_satuan}} {{!empty($d->ind_ps_target_2)?' - '.$d->$d->ind_ps_target_2.' '.$d->ind_ps_satuan:''}}</td>
								<td colspan="">
									<p>{{$d->nama_pn}}</p>
									<p>{{$d->nama_pp}}</p>
									<p>{{$d->nama_kp}}</p>
									<p>{{$d->nama_propn}}</p>
									<p>{{$d->nama_pronas}}</p>




								</td>
							</tr>
							<?php $id_indp_ps=$d->id_indp_ps; ?>
							@endif

							
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="row no-gutter">
	<div class="col-md-12">
		<div class="box-primary box">
			<form action="{{route('d.prokeg.pemetaan.store')}}" method="post">
				@csrf
				<div class="box-body">
				<table class="table-bordered table">
					<thead>
						<tr>
							<th colspan="3">INDIKATOR DAERAH</th>
							<th colspan="5">INDIKATOR PUSAT</th>

						</tr>
						<tr>
							<th>KODE</th>
							<th>NAMA</th>
							<th>TARGET</th>
							<th>KODE</th>
							<th>NAMA</th>
							<th>TARGET</th>
							<th>LOKASI</th>
							<th>PELAKSANA</th>


						</tr>
					</thead>
					<tbody>
						<tr>
							<td><b>IK.{{$d->id_ind_k}}</b></td>
							<td><b>(IK)</b> {{$d->ind_k_nama}}</td>
							<td>{{$d->ind_k_target_1}} {{$d->ind_k_satuan}} {{!empty($d->ind_k_target_2)?' - '.$d->$d->ind_k_target_2.' '.$d->ind_k_satuan:''}}
								<input type="hidden" name="ind_keg" required="" value="{{$d->id_ind_k}}">
								<input type="hidden" id="input_ind_keg_pusat" name="ind_keg_pusat" required="" value="{{$d->id_indp_ps}}">

							</td>
							<td id="ips_kode" class="decor"><b>{{$d->id_indp_ps?'IPS.'.$d->id_indp_ps:''}}</b></td>
							<td id="ips_nama" class="decor">{!!$d->id_indp_ps?'<b>(I'.$d->ind_ps_jenis.')</b> '.$d->ind_ps_nama:''!!}</td>
							<td id="ips_target" class="decor">{{$d->ind_ps_target_1}} {{$d->ind_ps_satuan}} {{!empty($d->ind_ps_target_2)?' - '.$d->$d->ind_ps_target_2.' '.$d->ind_ps_satuan:''}}</td>
							<td id="ips_lokasi" class="decor">{!! $d->indp_ps_lokasi !!}</td>
							<td id="ips_pelaksana" class="decor">{!! $d->indp_ps_instansi !!}</td>




						</tr>
					</tbody>
				</table>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn text-dark btn-warning btn btn-xs pull-right">TAMBAH/UBAH</button>
			</div>
			</form>
		</div>
	</div>
</div>



<div class="row no-gutter " id="b" style="background: #fff">

	<div class="col-md-3">
		<div class="box-primary box">
			<div class="box-body">
					<div class="row">
<div class="col-md-12">
		<div class="form-group">
			<label>PN</label>
			<select id="select_pn" class="form-control use-select-2-def trigger-1" data-index='1'>
				@foreach($pn as $d)
				<option value="{{$d->id}}">{{$d->nama}}</option>
				@endforeach
			</select>
		</div>
	</div>
<div class="col-md-12">
		<div class="form-group">
			<label>PP</label>
			<select id="select_pp" class="form-control use-select-2-def trigger-1"  data-index='2'>
				
			</select>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<label>KP</label>
			<select id="select_kp" class="form-control use-select-2-def trigger-1"  data-index='3'>
			
			</select>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<label>PROPN</label>
			<select id="select_propn" class="form-control use-select-2-def trigger-1"  data-index='4'>
			
			</select>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<label>PROYEK</label>
			<select id="select_pronas" class="form-control use-select-2-def trigger-1"  data-index='5'>
			
			</select>
		</div>
	</div>
</div>
			</div>
		</div>

	</div>
	<div class="col-md-9">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h5><b>PILIH INDIKATOR</b></h5>
			</div>
			<div class="box-body">
				<table class="table table-bordered" id="table_indikator">
					<thead>
						<tr>
							<th>
								URUIAN
							</th>
							<th>
								LOKASI
							</th>
							<th>
								AGGARAN
							</th>
							<th>
								TARGET  {{HP::fokus_tahun()}}
							</th>
							<th>
								PELAKSANA
							</th>
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
	function pick_ind(dom){
		var data=table_indikator.row( dom ).data();
		$('#ips_kode').html('<b>IPS.'+data.id+'</b>');
		$('#ips_nama').html('<b>(I'+data.jenis+')</b> '+data.nama);
		$('#ips_target').html(data.target);
		$('#ips_lokasi').html(data.lokasi);
		$('#ips_pelaksana').html(data.instansi);

		$('.decor').addClass('bg bg-warning');
		$('.decor').removeClass('decor');


		$('#input_ind_keg_pusat').val(data.id);
		$([document.documentElement, document.body]).animate({
	        scrollTop:$("#ips_kode").offset().top - 90
	    }, 200);


	}

	var table_indikator=$('#table_indikator').DataTable({
		sort:false,
		createdRow:function(row,data,dataIndex,cells,k){
			if(1){
				$(row).addClass("cursor-link link-hover pick_ind");
				


			}
		},
		columns:[
			{
				data:'nama',

			},
			{
				data:'lokasi',
				

			},{
				data:'anggaran'
			},
			{
				data:'target'
			},
			{
				data:'instansi'
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

					

				
					if(table_indikator.data()){
						table_indikator.clear().draw();
					}
						table_indikator.rows.add(res.data.indikator).draw();

						$('.pick_ind').on('click',function(){
							pick_ind(this);
						});

					

						

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