@extends('adminlte::page')


@section('content_header')
	@if(($profile->target_nuwas!=null) and (($profile->target_nuwas!=1)))
	<div class="row  text-center" style="border-bottom:1px solid #222">
		<div class="col-md-12 bg bg-yellow">
			<b class="text-dark">MERUPAKAN DAERAH PRIORITAS NUWASP TAHUN {{$profile->target_nuwas}} DENGAN TIPE BANTUAN ({{str_replace('@','',$profile->jenis_bantuan)}})</b>
		</div>
	</div>
	@endif
    <div class="text-center text-uppercase header-page">
    	  @php
                            switch ($profile->kategori_pdam_kode) {
                              case 0:
                               $cat='TIDAK MEMILIKI PENGKATEGORIAN';
                                break;
                                case 1:
                               $cat='SAKIT';
                                break;
                                case 2:
                               $cat='KURANG SEHAT';
                                 case 3:
                               $cat='POTENSI SEHAT';
                                break;
                                 case 4:
                               $cat='SEHAT';
                                break;
                                    case 5:
                               $cat='SEHAT BERKELANJUTAN';
                                break;


                              default:
                                # code...
                                break;
                            }



                            @endphp

    	<p class="text-center">KONDISI {{strtoupper($profile->nama_pdam)}} ({{strtoupper($cat)}}) - PERIODE LAPORAN {{$profile->periode_laporan}}</p>
    </div>






@stop

@section('content')

@php
    $open_hours=[];
	if($pdam->open_hours!=null){
			$open_hours=json_decode($pdam->open_hours,true);
	}


@endphp

	<div class="row text-dark" style="border-top:1px solid #222">
		@foreach($open_hours as $d)
		<div style="width:calc(100%/7)!important;" class=" col-md-12 {{HP::hari_ini($d['key'])?'bg-yellow text-dark':'bg-default'}}" style="border-right:1px solid #222">
			<b>{{$d['key']}} : {{$d['value']}}</b>
		</div>
		@endforeach
	</div>

<div class="row no-gutter bg-default-g text-dark">
		<div class="col-md-3" >
			<div class="box box-primary " >
				<div class="box-body">
					@if($pdam->url_image)
					<img src="{{$pdam->url_image}}" class="img-responsive">
					@endif
					<div class="sticky-top" data-margin-top="60">
						<a href="" class="btn btn-primary btn-sm col-md-12"><b>DOWNLOAD LAPORAN</b></a>
						<table class="table table-bordered ">
							<thead>
								<tr>
									<th>NAMA PDAM</th>
									<td>{{strtoupper($pdam->nama_pdam)}}

									</td>


								</tr>
								<tr>
									<th>Alamat</th>
									<td>{{$pdam->alamat}}
										@if($pdam->url_direct)
										<br>

										<a href="{{$pdam->url_direct}}" ><i class="fa fa-map"></i> Direct</a>
										@endif
									</td>


								</tr>
								<tr>
									<th>No Telp</th>
									<td>{{$pdam->no_telpon}}</td>
								</tr>
								<tr>
									<th>Website</th>
									<td>
										@if($pdam->website)
											@php
												$web=$pdam->website;
												if(strpos($web, 'http')!==false){

												}else{
													$web='http://'.$pdam->website;

												}


											@endphp
											<a href="{{$web}}" target="_blank">{{$pdam->website}}</a>
										@else
											-
										@endif

									</td>
								</tr>
							</thead>
						</table>

						@if(count($pdam_else)>0)
							<h5 class="text-center"><b>PDAM LAINYA DI DAERAH INI</b></h5>
							<hr>
							@foreach($pdam_else as $e)
							<a href="{{route('p.laporan_sat',['id'=>$e->id_laporan_terahir])}}" class="btn btn-warning col-md-12">{{strtoupper($e->nama_pdam)}} <small>({{$e->kategori_pdam}})</small>
								<br>{{Carbon\Carbon::parse($e->periode_laporan)->format('F Y')}}
								<br>
								<small>INPUT - {{Carbon\Carbon::parse($e->updated_input_at)->format('d F Y')}}</small>
							</a>
							@endforeach
							<h5 class="text-center">----</h5>
							<hr>
						@endif



					</div>
				</div>
			</div>

		</div>


		<div class="col-md-9">
			<ul class="nav nav-tabs">
				@php
					$fi=0;
				@endphp
			  @foreach($data as $key=>$value)

			 		 <li class="{{$fi==0?'active':''}}}"><a data-toggle="tab" href="#{{$key}}">{{strtoupper(str_replace('_', ' ', $key))}}</a></li>

			 	 	@php
					$fi=1;
					@endphp


			  @endforeach
			</ul>
			<div class="row">

				<div class="tab-content col-md-12">
						 @foreach($data as $key=>$value)
						   <div id="{{$key}}" class="tab-pane fade {{$fi==1?'in active':''}}">
							    <h3 class="text-center">{{strtoupper(str_replace('_', ' ', $key))}}</h3>

								<div class="row ">
									<div class="col-md-6">


							   	<table class="table">
							   		<thead>
							   			<tr>
							   				<th>INDIKATOR</th>
							   				<th>NIlAI</th>
												<th>SATUAN</th>


							   			</tr>
							   		</thead>
							   		<tbody>
							   			@php
							   				$index_k=0;

							   			@endphp
							   			@foreach($value as $k=> $d)
							   					@if($index_k<=18)
										   			@if(!in_array($k, ['kode_buku','kode_hal','tahun','id','kode_daerah','id_pdam','kategori']))
										   			<tr>
										   				<td>{{strtoupper(str_replace('_', ' ', $k))}}</td>
															@if(HP::satuan_bppspam($k)!='%')
															<td>{{number_format((float)strtoupper(str_replace('_', ' ', $d)))}}</td>
															@else
															<td>{{number_format((float)strtoupper(str_replace('_', ' ', $d))*100,2)}}</td>

															@endif
										   				<!-- <td>{{strtoupper(str_replace('_', ' ', $d))}}</td> -->
															<td>{{(HP::satuan_bppspam($k))}}</td>

										   			</tr>

									   				@endif
									   			@endif

									   			@php
									   				$index_k+=1;

									   			@endphp
							   			@endforeach
							   		</tbody>
							   	</table>

							</div>

							<div class="col-md-6">


							   	<table class="table">
							   		<thead>
							   			<tr>
							   				<th>INDIKATOR</th>
							   				<th>NIlAI</th>
												<th>SATUAN</th>


							   			</tr>
							   		</thead>
							   		<tbody>
							   			@php
							   				$index_k2=0;

							   			@endphp
							   			@foreach($value as $k=> $d)
							   					@if($index_k2>18)
										   			@if(!in_array($k, ['kode_buku','kode_hal','tahun','id','kode_daerah','id_pdam','kategori']))
														<tr>
															<td>{{strtoupper(str_replace('_', ' ', $k))}}</td>
															@if(HP::satuan_bppspam($k)!='%')
															<td>{{number_format((float)strtoupper(str_replace('_', ' ', $d)))}}</td>
															@else
															<td>{{number_format((float)strtoupper(str_replace('_', ' ', $d))*100 )}}</td>

															@endif
															<!-- <td>{{strtoupper(str_replace('_', ' ', $d))}}</td> -->
															<td>{{(HP::satuan_bppspam($k))}}</td>

														</tr>

									   				@endif
									   			@endif
									   			@php
									   				$index_k2+=1;

									   			@endphp
							   			@endforeach
							   		</tbody>
							   	</table>

							</div>

								</div>


							  	@php
								$fi=0;
								@endphp



							</div>
						@endforeach



				</div>
			</div>

		</div>
	</div>

@stop
