
@php
$id_dom='pdam_'.(isset($title)?'sat':'bppspam').'_r_'.rand(0,7);
@endphp
<div id="{{$id_dom}}" ></div>

@php 
	$sum=0;
	$data_pdam=[
		0=>0,
		1=>0,
		2=>0,
		3=>0,
		4=>0,
		5=>0,

	];

	foreach ($data as $key => $d) {
		# code...
		$data_pdam[($d->kat!=null)?$d->kat:0]=$d->jumlah_pdam;

		$sum+=$d->jumlah_pdam;
	}

@endphp

<script type="text/javascript">
Highcharts.chart('{{$id_dom}}', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: "{{isset($title)?'KATEGORI PDAM SAT '.number_format($sum,0).' PDAM TARGET NUWSP':'KATEGORI PDAM BPPSPAM - '.number_format($sum,0).' PDAM TARGET NUWSP' }}"
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
  },
  accessibility: {
    point: {
      valueSuffix: '%'
    }
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
      }
    }
  },
  series: [{
    name: 'Jumlah PDAM',
    colorByPoint: true,
    data: [
    {
      name: 'TIDAK MEMILIKI KATEGORI',
      y: {{($data_pdam[0]/$sum) *100}},
      color:'#222'
    },
    {
      name: 'SAKIT',
      y: {{($data_pdam[1]/$sum) *100}},
      color:'#900C3F'
    }, {
      name: 'KURANG SEHAT',
      y: {{($data_pdam[2]/$sum) *100}},
      color:'#FF5733'
    }, {
      name: 'POTENSI SEHAT',
      y: {{($data_pdam[3]/$sum) *100}},
      color:'#FFC300'
    }, {
      name: 'SEHAT',
      y: {{($data_pdam[4]/$sum) *100}},
      color:'#DAF7A6'

    }, {
      name: 'SEHAT BERKELANJUTAN',
      y: {{($data_pdam[5]/$sum) *100}},
      color:'#7DFF33'
    }
    ]
  }]
});

</script>
