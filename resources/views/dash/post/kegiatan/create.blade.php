@extends('adminlte::dash')


@section('content_header')

	<h1>KEGIATAN TAHUN {{HP::fokus_tahun()}}</h1>

@stop

@section('content')
<div class="row">
	<form action="{{route('d.post.kegiatan.store')}}" method="post" enctype='multipart/form-data'id="content_posting">
		@csrf
		<div class="col-md-9">
		<div class="form-group">
	<label>Judul</label>
	<input type="text" required="" name="title" class="form-control">
	</div> 

	<div class="box box-primary">
		<div class="box-body" id="editorjs">
			
		</div>
	</div>


	
	</div>
	<textarea name="content" id="content" style="display: none"></textarea>


	<div class="col-md-3">
		<div class="form-group">
			<button class="btn btn-primary" type="button" onclick="savingPost()">Simpan</button>
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
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

	
	<script type="text/javascript">

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



		

		


	
	

	 var token = "{{ csrf_token()}}";
	  var editor = new EditorJS({
      /**
       * Wrapper of Editor
       */
      holder: 'editorjs',

      /**
       * Tools list

       */
      placeholder: 'Let`s write an awesome story!',
      tools: {
        /**
         * Each Tool is a Plugin. Pass them via 'class' option with necessary settings {@link docs/tools.md}
         */
        header: {
          class: Header,
          inlineToolbar: ['link'],
          config: {
            placeholder: 'Header'
          },
          shortcut: 'CMD+SHIFT+H'
        },

        /**
         * Or pass class directly without any configuration
         */
        // image: SimpleImage,

        list: {
          class: List,
          inlineToolbar: true,
          shortcut: 'CMD+SHIFT+L'
        },

        checklist: {
          class: Checklist,
          inlineToolbar: true,
        },
        embed: Embed,

        quote: {
          class: Quote,
          inlineToolbar: true,
          config: {
            quotePlaceholder: 'Enter a quote',
            captionPlaceholder: 'Quote\'s author',
          },
          shortcut: 'CMD+SHIFT+O'
        },
        image: {
            class: ImageTool,
            config: {
          //   	uploader:{
          //   		uploadByFile(file){
			       //      return MyAjax.upload(file).then((res) => {

			       //        // return {
			       //        //   success: 1,
			       //        //   file: {
			       //        //     url: 'https://codex.so/upload/redactor_images/o_80beea670e49f04931ce9e3b2122ac70.jpg',
			       //        //     // any other image data you want to store, such as width, height, color, extension, etc
			       //        //   }
			       //        // };
			       //  });
		        // }

          //   	},
                additionalRequestHeaders: {
                    "Authorization": 'Bearer {{Auth::User()->api_token}}',
                    "X-CSRF-TOKEN": token

                },
                endpoints: {
                    byFile: '{{Route('d.post.kegiatan.file_store')}}',

                }
            }
        },

        // warning: Warning,

        // marker: {
        //   class:  Marker,
        //   shortcut: 'CMD+SHIFT+M'
        // },

        // code: {
        //   class:  CodeTool,
        //   shortcut: 'CMD+SHIFT+C'
        // },

        // delimiter: Delimiter,

        // inlineCode: {
        //   class: InlineCode,
        //   shortcut: 'CMD+SHIFT+C'
        // },

        linkTool: LinkTool,

        embed: Embed,

        table: {
          class: Table,
          inlineToolbar: true,
          shortcut: 'CMD+ALT+T'
        },

      },
      data: {
        blocks: [
         
        ]
      },
      onReady: function(){
      },
      onChange: function(dd) {

      }

    });


	  function savingPost(){
	  	editor.save().then(function(data){
	  		$('#content').html(JSON.stringify(data));
	  		$('#content').val(JSON.stringify(data));

	  	}).then(function(){
	  	$('#content_posting').submit();
	  		
	  	});



	  }



    /**
     * Saving example
     */
   

	</script>

@stop