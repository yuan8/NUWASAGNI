@extends('adminlte::page')


@section('content_header')
     <div class="row">
    	<div class="col-md-12">
    		<h3 class="text-uppercase text-center">PROGRAM KEGIATAN PER DAERAH TERKAIR AIR MINUM</h3>
    	</div>
    </div>
   
    <?php
?>
@stop

@section('content')
	<div id="chart_container_next">
		<h5 class="text-center"><b>Loading...</b></h5>
	</div>


@stop



@section('js')

<script type="text/javascript">
	var scrollbar_active=false;
	$.get('{{route('p.prokeg.per.daerah')}}',function(res){
		$('#chart_container_next').html(res);
	});

</script>
@stop