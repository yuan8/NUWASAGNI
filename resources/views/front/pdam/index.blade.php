@extends('adminlte::page')


@section('content_header')
    <h1 class="text-center">KONDISI PDAM {{HP::fokus_tahun()}}</h1>
@stop

@section('content')
    <div class="box text-dark">
        <div class="box-body">
            <table class="table table-bordered" id="table_pdam">
                <thead>
                    <tr>
                        <th>KODE</th>
                        <th>NAMA PDAM</th>
                        <th>DAERAH</th>
                        <th>STATUS PDAM</th>
                        <th>PERIODE LAPORAN DIGUNAKAN</th>
                        <th>KETERANGAN DATA</th>
                        <th>ACTION</th>

                    </tr>
                </thead>
                <tbody>
                    
                    @foreach($data as $d)
                    <tr class="{{$d->target_nuwas?'bg bg-primary':''}}">
                        <td>{{$d->id}}</td>
                        <td>{{strtoupper($d->nama_pdam)}} {{$d->target_nuwas?'(Target Nuwas '.HP::fokus_tahun().')':''}}</td>
                        <td>{{strtoupper($d->nama_daerah)}}
                            <br>
                            <small>{{strtoupper($d->nama_provinsi)}}</small>
                        </td>
                        <td>
                            {{$d->kategori_pdam}}
                        </td>
                        <td>
                            {{Carbon\Carbon::parse($d->periode_laporan)->format('F Y')}}
                        </td>
                        <td>
                            {{$d->keterangan??'-'}}
                        </td>
                        <td>
                            <a href="{{route('p.laporan_sat',['id'=>$d->id_laporan_terahir])}}" target="_blank" class="btn btn-primary btn-xs">Detail</a>
                        </td>


                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        
    </div>
@stop

@section('js')

    <script type="text/javascript">
        $('#table_pdam').DataTable({
            sort:false
        })
    </script>
@stop