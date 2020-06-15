@extends('adminlte::dash')


@section('content_header')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
	<h1>KEGIATAN TAHUN {{HP::fokus_tahun()}}</h1>

@stop

@section('content')
<div class="row">
	<form action="{{route('d.post.kegiatan.store')}}" method="post" enctype='multipart/form-data'>
		@csrf
		<div class="col-md-9">
		<div class="form-group">
	<label>Judul</label>
	<input type="text" required="" name="title" class="form-control">
	</div>
	<div id="editor" style="min-height: 500px;">
		<p>sjkjsk</p>
		<b>sjkjskj</b>
		<h5>jskjskj</h5>
	</div>
	</div>
	<textarea name="content" id="content" style="display: none"></textarea>
	<div class="col-md-3">
		<div class="form-group">
			<button class="btn btn-primary" type="submit">Simpan</button>
		</div>
		<div class="form-group">
			<label>Thumbnail</label>
				<div class="text-center" id="thumbnail_box" style="cursor:pointer; min-height: 100px;line-height: 100px;width: 100%; border: 1px solid #222">
					<i class="fa fa-camera" style="font-size:20px;"></i>
					
				</div>
				<input type="file" style="display: none;" name="thumbnail" id="thumbnail" accept="image/*">

		</div>
	</div>

	</form>
</div>
   
@stop

@section('js')
	
	<script type="text/javascript">
	 var quill = new Quill('#editor', {
	    theme: 'snow'
	  });

	 quill.on('text-change', function(delta, oldDelta, source) {
	 	$('#content').html(quill.container.firstElementChild.innerHTML);
	});

	 $('#content').html(quill.container.firstElementChild.innerHTML);




	function readURL(input) {
	  if (input.files && input.files[0]) {
	    var reader = new FileReader();
	    
	    reader.onload = function(e) {
	    	$('#thumbnail_box').html('<img class="img-responsive" src="'+e.target.result+'">')
	    }
	    
	    reader.readAsDataURL(input.files[0]); // convert to base64 string
	  }
	}

	$("#thumbnail_box").on('click',function(){
		$('#thumbnail').click();
	});

	$("#thumbnail").change(function() {

		if(this.files[0]){
	  		readURL(this);
		}else{
			$('#thumbnail_box').html('<i class="fa fa-camera" style="font-size:20px;"></i>');
		}
	});
	</script>

@stop