@extends('adminlte::page')


@section('content_header')

   <div style="width: 100%; float: left;">
      <div class=" text-center header-page">
        <p class="text-uppercase">KONDISI PDAM TAHUN {{$tahun}} - (BPPSPAM)</p>
      </div>
    </div>
   
    <style type="text/css">
      .progress-description, .info-box-text{
        font-size:8px!important;
      }
    </style>
@stop

@section('content')
<div class="row" style="margin-top: -15px;"> 
      
  <div class="col-md-2 col-sm-6 col-xs-12 text-dark" style="margin:0px; padding:0px;">
    <div class="info-box" style="margin-bottom: 0px; border-radius: 0px;">
      <span class="info-box-icon bg-teal" style=" border-radius: 0px;"><i class="fa fa-door-open"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">TIDAK MEMILIKI KATEGORI</span>
        <span class="info-box-number">{{isset($pdam_rekap[0])?$pdam_rekap[0]['jumlah_pdam']:0}} <small>PDAM</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
    
      
  <div class="col-md-2 col-sm-6 col-xs-12 text-dark" style="margin:0px; padding:0px;">
    <div class="info-box" style="margin-bottom: 0px; border-radius: 0px;">
      <span class="info-box-icon bg-green" style=" border-radius: 0px;"><i class="fa fa-door-open"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">SEHAT BERKELANJUTAN</span>
        <span class="info-box-number">{{isset($pdam_rekap[1])?$pdam_rekap[1]['jumlah_pdam']:0}} <small>PDAM</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
    
      
  <div class="col-md-2 col-sm-6 col-xs-12 text-dark" style="margin:0px; padding:0px;">
    <div class="info-box" style="margin-bottom: 0px; border-radius: 0px;">
      <span class="info-box-icon bg-aqua" style=" border-radius: 0px;"><i class="fa fa-door-open"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">SEHAT</span>
        <span class="info-box-number">{{isset($pdam_rekap[2])?$pdam_rekap[2]['jumlah_pdam']:0}} <small>PDAM</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
    
      
  <div class="col-md-2 col-sm-6 col-xs-12 text-dark" style="margin:0px; padding:0px;">
    <div class="info-box" style="margin-bottom: 0px; border-radius: 0px;">
      <span class="info-box-icon bg-blue" style=" border-radius: 0px;"><i class="fa fa-door-open"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">POTENSI SEHAT</span>
        <span class="info-box-number">{{isset($pdam_rekap[3])?$pdam_rekap[3]['jumlah_pdam']:0}} <small>PDAM</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
    
      
  <div class="col-md-2 col-sm-6 col-xs-12 text-dark" style="margin:0px; padding:0px;">
    <div class="info-box" style="margin-bottom: 0px; border-radius: 0px;">
      <span class="info-box-icon bg-yellow" style=" border-radius: 0px;"><i class="fa fa-door-open"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">KURANG SEHAT</span>
        <span class="info-box-number">{{isset($pdam_rekap[4])?$pdam_rekap[4]['jumlah_pdam']:0}} <small>PDAM</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
    
  <div class="col-md-2 col-sm-6 col-xs-12 text-dark" style="margin:0px; padding:0px;">
    <div class="info-box" style="margin-bottom: 0px;">
      <span class="info-box-icon bg-maroon"><i class="fa fa-door-open"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">SAKIT</span>
        <span class="info-box-number">{{isset($pdam_rekap[5])?$pdam_rekap[5]['jumlah_pdam']:0}} <small>PDAM</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
</div>
<div class=""  id="map_index"></div>

<div class="row no-gutter">
    <div class="col-md-12">
         <div class="box text-dark">
        <div class="box-body">
            <table class="table table-bordered table-init-datatable" id="table_pdam">
                <thead>
                    <tr>
                        <th>KODE</th>
                        <th>NAMA PDAM</th>
                        <th>DAERAH</th>
                        <th>NAMA PROVINSI</th>
                        <th>TAHUN PRIORITAS </th>
                        <th>JENIS HIBAH </th>
                        <th>KATEGORI PDAM</th>
                        <th>STATUS FCR</th>
                        <th>PERIODE LAPORAN DIGUNAKAN</th>
                        <th>KETERANGAN DATA</th>
                        <th>ACTION</th>

                    </tr>
                </thead>
                <tbody>
                    
                    @foreach($data as $d)
                    <tr class="{{$d->target_nuwas==$tahun?'bg bg-primary':''}}">
                        <td>{{$d->id}}</td>
                        <td>{{strtoupper($d->nama_pdam)}}</td>

                        <td>{{strtoupper($d->nama_daerah)}}
                            <br>
                            <small></small>
                        </td>
                        <td>{{strtoupper($d->nama_provinsi)}}</td>
                        <td>{{(($d->target_nuwas!=1)and($d->target_nuwas!=null))?$d->target_nuwas:''}}</td>
                        <td></td>
                        <td>
                            @php
                            switch ($d->kategori_pdam_kode) {
                              case 0:
                               $cat='TIDAK MEMILIKI PENGKATEGORIAN';
                                break;
                                case 1:
                               $cat='SAKIT';
                                break;
                                case 3:
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
                            {{$cat}}
                        </td>
                        <td class="{{$d->nilai_fcr>=1?'bg-green':'bg-yellow'}}">
                          {{$d->nilai_fcr>=1?'SUDAH FCR':'BELUM FCR'}}
                          <small>{{number_format($d->nilai_fcr,3)}}</small>

                        </td>
                        <td>
                            {{$d->periode_laporan}}
                        </td>
                        <td>
                            {{$d->keterangan??'-'}}
                        </td>
                        <td>
                            <a href="{{route('bppspam.detail',['kode_daerah'=>$d->kode_daerah,'tahun'=>$tahun])}}" target="_blank" class="btn btn-primary btn-xs">Detail</a>
                        </td>


                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        
    </div>
    </div>
</div>
@stop

@section('js')

<script type="text/javascript" src="{{asset('L_MAP/ind/ind.js')}}"></script>
<script type="text/javascript" src="{{asset('L_MAP/ind/kota.js')}}"></script>

    {{-- <script type="text/javascript">
        $('#table_pdam').DataTable({
            sort:false
        })
          $.get("{{route('p.pdam.map')}}",function(res){
         $('#map_index').html(res);
    });

    </script>
 --}}
@stop