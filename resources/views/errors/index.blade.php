@extends('adminlte::page')

@section('title', 'ERROR '.$code)

@section('content_header')
   
@stop

@section('content')

   <div class="col-md-4 col-md-offset-4 text-center animated bounceInUp">
    	<img src="{{url('logo.png')}}" style="max-width: 40%">
    	<h4><b>BANGDA KEMENDAGRI</b></h4>
    	<h5><b>SUPD II</b></h2>
    	<hr style="border:1px solid #fff">
   <H1 class="text-center">ERROR {{$code}}</H1>
   <p>{{$message}}</p>
    </div>



@stop