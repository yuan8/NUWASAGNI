@extends('adminlte::page')


@section('content_header')
    <div class="row bg-yellow">
        <div class="col-md-12">
        	<h5 class="text-center text-dark"><b><i class="fa fa-file"></i> DOKUMEN KEBIJAKAN </b></h5>
    	</div>
    </div>
@stop

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
					<h5><b>{{$daerah->nama_daerah}}</b></h5>
					<p><small>{{$jenis}}</small></p>
				</div>
				<div class="box-body">
					@foreach($data as $d)
						<?php 
						 $dx=['xlsx','doc','docx','csv','xls'];
							if(in_array($d->extension,$dx)){
								$link='http://view.officeapps.live.com/op/view.aspx?src=';
							}else{
								$link='';
							}


						?>
						<div class="col-md-4">
							<a href="{{$link.asset($d->path)}}">
								<div class="info-box bg-green">
					            <span class="info-box-icon">
					            	{{-- <i class="ion ion-ios-cloud-download-outline"></i> --}}
					            	.{{$d->extension}}
					            </span>

					            <div class="info-box-content">
					              <span class="info-box-text">{{$d->nama}}</span>
					              <span class="info-box-number">{{$d->tahun.' - '.$d->tahun_selesai}}</span>

					              <div class="progress">
					                <div class="progress-bar" style="width: 70%"></div>
					              </div>
					              <span class="progress-description">
					                    {{'@'.$d->nama_user}}
					                  </span>
					            </div>
					            <!-- /.info-box-content -->
					          </div>
							</a>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>

@stop