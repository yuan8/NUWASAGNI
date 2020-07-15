<!DOCTYPE html>
<html>
<head>
	
	@if(!isset($own_content))
	<link rel="stylesheet" type="text/css" href="asset/bootstrap.min.css">
	@else
	<link rel="stylesheet" type="text/css" href="{{asset('L_MAP/asset/bootstrap.min.css')}}">
	@endif
	<title></title>
	
</head>


<body class="container-fluid" >
	@include('output.map.content_map')
</body>
</html>