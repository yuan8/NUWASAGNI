@extends('adminlte::dash')

@section('title', 'NUWSP')

@section('content_header')
@stop

@section('content')
    <div class="col-md-4 col-md-offset-4 text-center animated bounceInUp">
    	<img src="{{url('logo.png')}}" class="" style="max-width: 40%">
    	<h2><b>BANGDA KEMENDAGRI</b></h2>
    	<h2><b>SUPD II</b></h2>
    	<hr>
    	<p><b>NUWAS - {{HP::fokus_tahun()}}</b></p>
    </div>
@stop