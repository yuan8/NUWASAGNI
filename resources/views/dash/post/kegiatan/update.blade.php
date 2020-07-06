@extends('adminlte::dash')


@section('content_header')

	<h1>KEGIATAN TAHUN {{HP::fokus_tahun()}}</h1>

@stop

@section('content')
<div class="row">
	<form action="{{route('d.post.kegiatan.update',['id'=>$data->id])}}" method="post" enctype='multipart/form-data'id="content_posting">
		@csrf
		<div class="col-md-9">
		<div class="form-group">
	<label>Judul</label>
	<input type="text" required="" name="title" class="form-control" value="{{$data->title}}">
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
          @if($data->path)
          <img class="img-responsive" src="{{asset($data->path)}}">
          @else
					<i class="fa fa-camera" style="font-size:20px;"></i>
          @endif
					
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

   var data_block=<?php echo $data->content ?>;
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
         raw: RawTool,
        embed: {
          class: Embed,
          config: {
            services: {
              youtube: true,
              coub: true,
              pdf: {
                regex: /(^http.*)([.]pdf$)/,
                embedUrl: 'https://codepen.io/<%= remote_id %>?height=300&theme-id=0&default-tab=css,result&embed-version=2',
                html: "<iframe height='300' scrolling='no' frameborder='no' allowtransparency='true' allowfullscreen='true' style='width: 100%;'></iframe>",
                height: 300,
                width: 600,
                id: (groups) =>{
                  console.log(groups);
                  return  groups.join('');
                }
              }
            },
              patterns:{
                pdf:/(^http.*)([.]pdf$)/
              }

          }
        },
        inlineCode: {
          class: InlineCode,
          shortcut: 'CMD+SHIFT+M',
        },
        personality: {
        class: Personality,
        config: {
          endpoint:  '{{Route('d.post.kegiatan.file_store')}}'  // Your backend file uploader endpoint
          },
        },
        quote: {
          class: Quote,
          inlineToolbar: true,
          config: {
            quotePlaceholder: 'Enter a quote',
            captionPlaceholder: 'Quote\'s author',
          },
          shortcut: 'CMD+SHIFT+O'
        },
        attaches: {
          class: AttachesTool,
          config: {
         
                additionalRequestHeaders: {
                    "Authorization": 'Bearer {{Auth::User()->api_token}}',
                    "X-CSRF-TOKEN": token
                },
                endpoint: '{{Route('d.post.kegiatan.file_store')}}'
            }
        },
        image: {
            class: ImageTool,
            config: {
         
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

        linkTool: {
          class: LinkTool,
            config: {
              endpoint: '{{route('d.post.kegiatan.active_url')}}', // Your backend endpoint for url data fetching
            }
        },

        embed: Embed,

        table: {
          class: Table,
          inlineToolbar: true,
          shortcut: 'CMD+ALT+T'
        },

      },
      data: {
        
        blocks:data_block.blocks
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