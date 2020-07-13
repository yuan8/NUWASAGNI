
@extends('adminlte::page')

@section('content_header')
     <div class="row">
    	<div class="col-md-12">
    		<h3 class="text-uppercase text-center">DATA PAD TAHUN {{$tahun}}</h3>
    	</div>
    </div>
   
    <?php
?>
@stop

@section('content')


@stop

@section('js')
	<script type="text/javascript">
		$('table').dataTable();
	</script>

@stop