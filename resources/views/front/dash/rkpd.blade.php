 <div id="column_chart"></div> 
<script type="text/javascript">
  var url_list=<?php echo json_encode($list_url) ?>;
  Highcharts.chart('column_chart', {
                   chart: {
                          zoomType: 'xy',
                          scrollablePlotArea: {
                              minWidth:(<?php echo count($category) ?>* 110),
                              scrollPositionX: 1
                          },
                          backgroundColor:'#fff',
                          plotBorderWidth: 2,
                      },
                  navigator: {
                      enabled: true
                    },
                  title: {
                      text: 'DATA RKPD AIR MINUM TAHUN {{$tahun}}',
                  },
                  subtitle: {
                      text: ''
                  },
                  
                  colors:['#80b918','#bfd200','#fff'],
                  xAxis: {
                      categories: <?php  echo json_encode($category)?>,
                      label:{
                        style: {
                                color: '#222'
                      },
                      },
                      color:'#222',
                      gridLineWidth: 1,
                      gridZIndex: 4,
                      crosshair: true
                  },
                  yAxis: [
                    { // Primary yAxis
                        labels: {
                            format: 'Rp. {value}',
                            style: {
                                color: '#222'
                            }
                        },
                        title: {
                            text: 'JUMLAH ANGGARAN',
                            style: {
                                color: '#222'
                            }
                        },
                        lineWidth: 1,
                        opposite: true,
                        minRange:100000000,
                        crosshair: true


                    }, { // Secondary yAxis
                        // gridLineWidth: 0,
                        crosshair: true,
                         // angle: 150,
                      
                        title: {
                            text: 'JUMLAH PROGRAM / KEGIATAN',
                            style: {
                                color: '#222' 
                            }
                        },
                        labels: {
                            format: '{value} ',
                            style: {
                                color: '#222' 
                            }
                        }

                    }

                  ],

                  tooltip: {
                      headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                      pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                          '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                      footerFormat: '</table>',
                      shared: true,
                      useHTML: true
                  },
                 plotOptions: {

                      line: {
                          lineWidth: 1,
                          dataLabels: {
                              enabled: true
                          },
                           cursor: 'pointer',
                            point: {
                                events: {
                                    click: function () {
                                      console.log(this);
                                        // location.href = 'https://en.wikipedia.org/wiki/' +
                                        location.href = url_list[this.index];

                                            this.options.key;
                                    }
                                }
                            }
                        },
                       column: {
                          dataLabels: {
                              enabled: true
                          },
                           cursor: 'pointer',
                                point: {
                            events: {
                                click: function () {
                                    location.href = url_list[this.index];
                                }
                            }
                          }
                      }
                  },
                  series:<?php echo json_encode($data) ?>,
                   responsive: {
                      rules: [{
                          condition: {
                              maxWidth:'100%' 
                          },
                          chartOptions: {
                              legend: {
                                  floating: false,
                                  layout: 'horizontal',
                                  align: 'center',
                                  verticalAlign: 'bottom',
                                  x: 0,
                                  y: 0
                              },
                              yAxis: [{
                                  labels: {
                                      align: 'right',
                                      x: 0,
                                      y: -6
                                  },
                                  showLastLabel: false
                              }, {
                                  labels: {
                                      align: 'left',
                                      x: 0,
                                      y: -6
                                  },
                                  showLastLabel: false
                              }
                              ]
                          }
                      }]
                  }
              });

            
            


</script>