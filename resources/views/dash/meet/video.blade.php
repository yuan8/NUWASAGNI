
<!DOCTYPE html>
<html>
<head>
	<title>MEETING NUWAS</title>
	<link rel="stylesheet" type="text/css" href="{{url('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendor/adminlte/dist/css/AdminLTE.min.css')}}">
	<style type="text/css">
		*{
			padding:0px;
		}
		iframe{
			border: none;
			background: #ddd;
		}
		.nav li a{
			color: #fff!important;
			font-weight: bold;
		}
		.nav{
			border: none!important;
		}
		.navbar{
			margin-bottom: 0px;
			border: none;
			border-radius: 0px;
		}
	</style>
</head>

<script type="text/javascript" src="{{asset('L_MAP/asset/jq.js')}}"></script>
<body>
<nav class="navbar navbar-default bg-navy">
  <div class="">
    <div class="navbar-header" style="background: #fff;">
      <a class="navbar-brand" href="#"><img src="{{asset('logo-nuwas.png')}}" style="width:50px"></a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="{{route('d.meet.index')}}">DASHBOARD ADMIN</a></li>

      <li><a href="#">SHARING FILES</a></li>
      <li><a href="#">CATATAN MEETING</a></li>
    </ul>
  </div>
</nav>
<iframe id="id_framing_{{$key}}" src="{{route('d.meet.initial.video')}}" allow="geolocation; microphone; camera" style="width: 100%; height: calc(100vh - 100px)"></iframe>


</body>


<script type="text/javascript">
	var the_stap_{{$key}}=0;
	$('#id_framing_{{$key}}').on('load',function(){
		if(the_stap_{{$key}}==0){
			setTimeout(function(){
			$('#id_framing_{{$key}}').attr('src','{{env('VIDEO_PATH').'/'.$key}}');
			the_stap_{{$key}}=1;
			},4000);
		}
	});
</script>
</html>