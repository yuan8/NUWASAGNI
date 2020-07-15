@extends('adminlte::page')


@section('content_header')
    <div class="row bg-yellow">
        <div class="col-md-12">
        	<h5 class="text-center text-dark"><b><i class="fa fa-file"></i> OUTPUT ARICLE</b></h5>
    	</div>
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta3/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>
@stop

@section('content')
	<div class="row no-gutter" style="position: relative;">
		<div class="col-md-12 text-center" style="position: absolute; max-height:700px;">
			<img src="{{asset($data->file_path)}}"  style="max-width: 100%; max-width: 700px;">
			<div class="" style="width: 100%; position: absolute; height: 65%; background: linear-gradient(180deg, rgba(34,110,195,0.05675773727459732) 0%, rgba(34,110,195,0.7094188017003676) 30%, rgba(17,25,95,1) 77%); bottom: 0px;"></div>
		</div>
		<div class="" style="position: absolute; top:200px; width: 70%; margin: auto; left:0; right:0; ">
			<div class="col-md-12" >
					<h4 class="text-center badge" style="font-size: 18px" ><b>{{$data->title}}</b></h4 >
				<div class="box" style="margin-top: 24px;">
					<div class="box-body" id="post_content">
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
{{-- 
	<div class="row" >
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
		            <h4 class="text-center text-uppercase "></h4>
		        </div>
				<div class="col-md-10">
					<div class="text-center" style="position: relative; float: left; width: 100%; max-height: 300px; overflow: hidden;">
								<img src="{{asset($data->file_path)}}" class="img-responsive" style="width: 100%">

								<h4 style="float:left;position: absolute;  z-index: 88; top:0px; bottom:0px; left: 0px; right: 0px; margin:auto;"><b>{{$data->title}}</b></h4>
							</div>
					<div class="box">
						<div class="box-header with-border">
							
						</div>
						<div class="box-body editor-render" id="post_content">
							
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="box box-primary">
						<div class="box-body">
							<button class="btn btn-primary btn-xs col-md-12" onclick="download()">Download</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		 --}}
		
	</div>

@stop

@section('js')
	<script type="text/javascript">
	const edjsParser = edjsHTML();

	  var htmlDom = edjsParser.parse({!!$data->content !!});
	  for (var i =0;i<htmlDom.length;i++) {
	  	$('#post_content').append(htmlDom[i]);
	  }


	</script>


	<script type="text/javascript">
		function download(quality=10){

			const filename  = 'ThisIsYourPDFFilename.pdf';

			html2canvas(document.querySelector('#post_content')).then(canvas => {
				let pdf = new jsPDF('p', 'mm', 'a4');
				pdf.text('hahaj',10,10);
				pdf.addImage(canvas.toDataURL('image/png'), 'PNG');
				pdf.save(filename);

			});

			// const filename  = 'ThisIsYourPDFFilename.pdf';

			// html2canvas(document.querySelector('#post_content'), 
			// 						{scale: quality}
			// 				 ).then(canvas => {
			// 	let pdf = new jsPDF('p', 'mm', 'a4');
			// 	pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 0, 211, 298);
			// 	pdf.save(filename);
			// });

		// var doc = new jsPDF()

		// 	doc.text($('#post_content').html(), 10, 10)

		// 	doc.save('a4.pdf')

			// doc.text('Hello world!', 1, 1)
			// doc.save('two-by-four.pdf');



		}
	</script>

@stop