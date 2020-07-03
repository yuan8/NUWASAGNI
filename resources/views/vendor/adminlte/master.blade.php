<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
@yield('title', config('adminlte.title', 'AdminLTE 2'))
@yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">
    <script type="text/javascript" src="{{asset('L_MAP/asset/jq.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/custome/axios.js')}}"></script>

    <script type="text/javascript">

    const TOKEN_KN='{{(Auth::User())?'Bearer '.Auth::User()->api_token:''}}';

    const API_CON = axios.create({
          timeout: 10000,
          headers: {
            'Authorization': TOKEN_KN,
            'Content-Type': 'application/json',
          }
    });
</script>


    @include('adminlte::plugins', ['type' => 'css'])

    <!-- Theme style -->

    @yield('adminlte_css')
    <style type="text/css">
        .editor-render img{
            max-width: 100%;
        }
    </style>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition @yield('body_class')">

@yield('body')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">


<!-- <script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script> -->
<script type="text/javascript" src="{{asset('L_MAP/asset/jq.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/editorHtml/browser.js?v='.date('i'))}}"></script>



<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('vendor/custome/cs.css?v='.date('i'))}}">

<script type="text/javascript" src="{{asset('L_MAP/asset/hi.js')}}"></script>
<script type="text/javascript" src="{{asset('L_MAP/asset/proj4.js')}}"></script>







@include('adminlte::plugins', ['type' => 'js'])
<script type="text/javascript">
function formatNumber(num,fix=3) {
if((num!='')&&(num!=null)){
    num=parseFloat(num).toFixed(fix);
}else{
    num=0;
}

var num=num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ');
return num.replace(/ /g,',');

}
</script>
<script type="text/javascript">


    $.fn.dataTable.Api.register( 'sum()', function ( ) {
        return this.flatten().reduce( function ( a, b ) {
            if ( typeof a === 'string' ) {
                a = a.replace(/[^\d.-]/g, '') * 1;
            }
            if ( typeof b === 'string' ) {
                b = b.replace(/[^\d.-]/g, '') * 1;
            }
     
            return a + b;
        }, 0 );
    } );
    
</script>

<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
@include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])

@include('sweetalert::alert')
@yield('adminlte_js')

<script type="text/javascript">
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "currency-pre": function ( a ) {
            a = (a==="-") ? 0 : a.replace( /[^\d\-\.]/g, "" );
            return parseFloat( a );
        },

        "currency-asc": function ( a, b ) {
            return a - b;
        },

        "currency-desc": function ( a, b ) {
            return b - a;
        }
    } );

</script>

</body>
</html>
