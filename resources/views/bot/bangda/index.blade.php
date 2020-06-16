@extends('adminlte::page')


@section('content_header')
<div class="row bg-navy">
	<div class="col-md-12">
	
	</div>
</div>

@stop

@section('content')
	<div class="" id="content_snap">
		{!!$data!!}
	</div>

@stop


@section('js')

<script type="text/javascript">
	var data_api=[];
	var main_link='https://bangda.kemendagri.go.id/berita/baca_kontent/';
	var id=$('.topic').find('a').attr('href').replace(main_link,'');
		id=id.split('/')[0];
		id=parseInt(id);
	var c={
		id:id,
		link:$('.topic').find('a').attr('href'),
		link_img:$('.topic').find('img').attr('src'),
		title:$('.topic').find('h3').text().trim(),
		content:$('.topic').find('p').text().trim(),
		time:$('.topic').find('.time').text().split(':')[1].trim(),
	}

	data_api.push(c);

	$('.list-unstyled li').each(function(i,d){
		var id=$(d).find('a').attr('href').replace(main_link,'');
		id=id.split('/')[0];
		id=parseInt(id);
		var dt={
			id:id,
			link:$(d).find('a').attr('href'),
			link_img:$(d).find('img').attr('src'),
			title:$(d).find('h4').text().trim(),
			content:$(d).find('p').text().trim(),
			time:$($('.list-unstyled li')[i]).find('.time').text().split(':')[1].trim(),
		}

		data_api.push(dt);



	});


	$.post('{{route('bot.bangda.store')}}',{data:data_api},function(res){
		if(res.code==200){
			$('body').html('selesai');
		}
	});





</script>


@stop