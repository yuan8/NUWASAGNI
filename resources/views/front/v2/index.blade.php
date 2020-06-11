
@extends('adminlte::page')

@section('content_header')
    
@stop

@section('content')
	<div id="map_index">
		
	</div>

@stop

@section('js')
<script type="text/javascript" src="{{asset('L_MAP/ind/ind.js')}}"></script>
<script type="text/javascript" src="{{asset('L_MAP/ind/kota.js')}}"></script>

<script type="text/javascript">
   // Instantiate the map
Highcharts.mapChart('map_index', {
    chart: {
        map: 'ind',
        backgroundColor: 'rgba(255, 255, 255, 0)',

    },

    title: {
        text: 'Nordic countries'
    },

    subtitle: {
        text: 'Demo of drawing all areas in the map, only highlighting partial data'
    },

    legend: {
        enabled: false
    },

    series: [{
        name: 'Country',
        data: [
            ['is', 1],
            ['no', 1],
            ['se', 1],
            ['dk', 1],
            ['fi', 1]
        ],
        dataLabels: {
            enabled: true,
            color: '#FFFFFF',
            formatter: function () {
                if (this.point.value) {
                    return this.point.name;
                }
            }
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '{point.name}'
        }
    }]
});


</script>

@stop

