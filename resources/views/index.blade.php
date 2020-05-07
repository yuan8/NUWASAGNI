@extends('adminlte::page')


@section('content_header')

@stop

@section('content')
   <div class="row no-gutter text-dark">
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body text-center no-padding pt-15 bg bg-aqua">
                <i class="fa fa-file"></i>
                <h5><b>PROGRAM KEGIATAN (RKPD FINAL)  {{HP::fokus_tahun()}} </b></h5>
                <small>{{number_format($data_kegiatan->jumlah_kegiatan,0,'.',',')}} Kegiatan</small>
                <a href="{{route('p.prokeg')}}" class="full-w btn btn-info btn-xs">Detail</a>
            </div>
        </div>
    </div>
      <div class="col-md-3">
        <div class="box box-warning ">
            <div class="box-body text-center no-padding pt-15 bg-yellow bg">
                <i class="fa fa-file"></i>
                <h5><b>ANGGARAN (RKPD FINAL) {{HP::fokus_tahun()}}</b></h5>
                <small>Rp. {{number_format($data_kegiatan->jumlah_anggaran,0,'.',',')}} </small>
                <a href="" class="full-w btn btn-warning btn-xs">Detail</a>
            </div>
        </div>
    </div>
     <div class="col-md-3">
        <div class="box box-success ">
            <div class="box-body text-center no-padding pt-15 bg bg-green">
                <i class="fa fa-file"></i>
                <h5><b>PROFILE PDAM {{HP::fokus_tahun()}} </b></h5>
                <small>{{number_format(0,0,'.',',')}} PDAM </small>
                <a href="{{route('p.pdam')}}" class="full-w btn btn-success btn-xs">Detail</a>
            </div>
        </div>
    </div>
     <div class="col-md-3">
        <div class="box box-danger">
            <div class="box-body text-center no-padding pt-15 bg bg-red">
                <i class="fa fa-file"></i>
                <h5><b>PROFILE KEBIJAKAN {{HP::fokus_tahun()}}</b></h5>
                <small>{{number_format(0,0,'.',',')}} File Kebijakan</small>

                <a href="" class="full-w btn btn-danger btn-xs">Detail</a>
            </div>
        </div>
    </div>

 
 

</div>
 <div class="row">
      <div class="container-fluid" id="map_index" style="min-height: 500px;">
    
       
   </div>
 </div>

<div class="row no-gutter text-dark">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>NAMA PDAM</th>
                                <th>DAERAH</th>
                                <th>PENILAIAN NUWAS</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-md-6">
                      <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>NAMA PDAM</th>
                                <th>DAERAH</th>
                                <th>PENILAIAN NUWAS</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
     

  

@stop

@section('js')
        <script type="text/javascript" src="{{asset('L_MAP/ind/ind.js')}}"></script>x
<script type="text/javascript" src="{{asset('L_MAP/ind/kota.js')}}"></script>



<script type="text/javascript">
    

    $.get("{{route('p.pdam.map')}}",function(res){
        $('#map_index').html(res);
    });

</script>

@stop