<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
  <link href="{{asset('vendor/editor/assets/demo.css')}}" rel="stylesheet">
  <script src="{{asset('vendor/editor/assets/json-preview.js')}}"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
  <style type="text/css">
  	#editorjs{
  		border: 1px dotted #ddd;
  	}
  </style>
</head>
<body class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
			<label>Juduk</label>
			<input type="text" class="form-control" name="">
		</div>
		</div>
		<div class="col-md-9 card">
			<div class="" id="editorjs"></div>
		</div>
		<div class="col-md-3">
			<button class="btn btn-info">Simpan</button>
		</div>
	</div>

  <div class="ce-example">
   
    <div class="ce-example__content _ce-example__content--small">
      

      <div class="ce-example__button" id="saveButton">
        editor.save()
      </div>
    </div>
    <div class="ce-example__output">
      <pre class="ce-example__output-content" id="output"></pre>

    
    </div>
  </div>

  <!-- Load Tools -->
  <!--
   You can upload Tools to your project's directory and use as in example below.

   Also you can load each Tool from CDN or use NPM/Yarn packages.

   Read more in Tool's README file. For example:
   https://github.com/editor-js/header#installation
   -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script><!-- Header -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest"></script><!-- Image -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script><!-- Delimiter -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script><!-- List -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script><!-- Checklist -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script><!-- Quote -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script><!-- Code -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script><!-- Embed -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script><!-- Table -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/link@latest"></script><!-- Link -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/warning@latest"></script><!-- Warning -->

  <script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script><!-- Marker -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/inline-code@latest"></script><!-- Inline Code -->

  <!-- Load Editor.js's Core -->
  <script src="{{asset('vendor/editor/dist/editor.js')}}"></script>

  <!-- Initialization -->
  <script>
    /**
     * Saving button
     */
    const saveButton = document.getElementById('saveButton');

    /**
     * To initialize the Editor, create a new instance with configuration object
     * @see docs/installation.md for mode details
     */
    var editor = new EditorJS({
      /**
       * Wrapper of Editor
       */
      holder: 'editorjs',

      /**
       * Tools list
       */
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
        image: SimpleImage,

        list: {
          class: List,
          inlineToolbar: true,
          shortcut: 'CMD+SHIFT+L'
        },

        checklist: {
          class: Checklist,
          inlineToolbar: true,
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

        warning: Warning,

        marker: {
          class:  Marker,
          shortcut: 'CMD+SHIFT+M'
        },

        code: {
          class:  CodeTool,
          shortcut: 'CMD+SHIFT+C'
        },

        delimiter: Delimiter,

        inlineCode: {
          class: InlineCode,
          shortcut: 'CMD+SHIFT+C'
        },

        linkTool: LinkTool,

        embed: Embed,

        table: {
          class: Table,
          inlineToolbar: true,
          shortcut: 'CMD+ALT+T'
        },

      },

      /**
       * This Tool will be used as default
       */
      // initialBlock: 'paragraph',

      /**
       * Initial Editor data
       */
      data: {
        blocks: [
          {
            type: "paragraph",
            data: {
              text: "Tulias Sesuatu...",
            }
          }
        ]
      },
      onReady: function(){
        saveButton.click();
      },
      onChange: function() {
        console.log('something changed');
      }
    });

    /**
     * Saving example
     */
    saveButton.addEventListener('click', function () {
      editor.save().then((savedData) => {
        cPreview.show(savedData, document.getElementById("output"));
      });
    });
  </script>
</body>
</html>
