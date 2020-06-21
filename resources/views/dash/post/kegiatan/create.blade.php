@extends('adminlte::dash')


@section('content_header')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="//cdn.quilljs.com/1.3.6/quill.bubble.css">

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
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
	<div class="box box-primary">
		<div class="box-header">
			<div id="toolbar">
		  <!-- Add font size dropdown -->
				  <select class="ql-size">
				    <option value="small"></option>
				    <!-- Note a missing, thus falsy value, is used to reset to default -->
				    <option selected></option>
				    <option value="large"></option>
				    <option value="huge"></option>
				  </select>
				  <!-- Add a bold button -->
				   <button class="ql-bold"></button>
  					<button class="ql-italic"></button>				  <!-- Add subscript and superscript buttons -->
				  <button class="ql-script" value="sub"></button>
				  <button class="ql-image" ></button>

				  <button class="ql-script" value="super"></button>
				   <select class="ql-color">
				   </select>
				     <select class="ql-background">
				   </select>
				   </select>
				     <select class="ql-align">
				   </select>
				    <select class="ql-font">
				   </select>
				   <button type="button" class="btn-primary btn-xs text-dark">
				   	<i class="fa fa-file"></i>
				   </button>

				  

			</div>
		</div>
		<div class="box-body">
			<div id="editor" style="min-height: 500px;">
	
		</div>
		</div>
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

		var toolbarOptions = [
			  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
			  ['blockquote', 'code-block'],

			  [{ 'header': 1 }, { 'header': 2 }],               // custom button values
			  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
			  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
			  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
			  [{ 'direction': 'rtl' }],                         // text direction

			  [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
			  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

			  [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
			  [{ 'font': [] }],
			  [{ 'align': [] }],
			  ['image','files'],


			  ['clean']                                         // remove formatting button
			];
	



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



		var IMGUR_CLIENT_ID = 'bcab3ce060640ba';
		var IMGUR_API_URL = '{{route('d.post.file.up')}}';
		function imageHandler(image, callback) {
			API_CON.post(IMGUR_API_URL).then((res)=>{
				console.log(res);
			});
			
		}

		


		 var quill = new Quill('#editor', {
		    theme: 'snow',
		    modules:{
		  	toolbar:'#toolbar',

	   		 },
		  	// imageHandler: imageHandler,

	  	placeholder: 'Compose an epic...',
	  });

	 var toolbar = quill.getModule('toolbar');
	toolbar.addHandler('image', imageHandler);


	 quill.on('text-change', function(delta, oldDelta, source) {
	 	console.log('s');
	 	$('#content').html(quill.container.firstElementChild.innerHTML);
	});

	 $('#content').html(quill.container.firstElementChild.innerHTML);


	</script>

@stop