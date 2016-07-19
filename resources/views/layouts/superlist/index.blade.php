<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

    <link href="http://fonts.googleapis.com/css?family=Nunito:300,400,700" rel="stylesheet" type="text/css">
    <link href="{{ asset('sximo/themes/superlist/libraries/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('sximo/themes/superlist/libraries/owl.carousel/assets/owl.carousel.css')}}" rel="stylesheet" type="text/css" >
    <link href="{{asset('sximo/themes/superlist/libraries/colorbox/example1/colorbox.css')}}" rel="stylesheet" type="text/css" >
    <link href="{{asset('sximo/themes/superlist/libraries/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('sximo/themes/superlist/libraries/bootstrap-fileinput/fileinput.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('sximo/themes/superlist/css/superlist.css')}}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('sximo/js/plugins/select2/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('sximo/themes/sximone/js/fancybox/source/jquery.fancybox.css') }}" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('sximo/images/'.CNF_FAVICON) }}">

    <title> {{ CNF_APPNAME }}</title>
    {{--<script src="{{ asset('sximo/themes/superlist/js/jquery.js')}}" type="text/javascript"></script>--}}
    <script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery.min.js') }}"></script>
    <style>

        .directlink{
            border-bottom: 0 !important;
        }

        .directlink >li > a{
            font-size: 16px !important;
            outline: none;
            text-transform: uppercase;
        }
        .directlink > li.active > a,.directlink > li.active > a:hover,
        .directlink > li.active > a:focus{
            font-size:16px !important;
            border: none !important;
            background: none !important;
            background-color: transparent!important;
            color : #fff !important;
            border-bottom: 3px solid #fff !important;
        }
        .directlink > li > a:hover{
            background-color: transparent !important;
            border: none !important;
            border-bottom: 3px solid #fff !important;
        }

        .ui-autocomplete span.hl_results {
            background-color: #ffff66;
        }

        /* loading - the AJAX indicator */
        .ui-autocomplete-loading {
            background: white ;
        }

        .ui-autocomplete {
            background: #fff !important;
            box-sizing: border-box !important;
            color: #444 !important;
            padding: 0px !important;
            width: 320px !important;
            z-index: 1000 !important;
            border: 0 !important;
            list-style-type: none !important;
        }

        @media(max-width: 768px){
            .ui-autocomplete{
                width: 95% !important;
            }

        }

        .ui-autocomplete li {
            border-bottom: 0 !important;
            padding: 10px !important;
        }

        .ui-autocomplete li:hover{border:0px !Important;
            background-color:#f5f5f5 !important;
        }
        .keyword{
            color:#ccc;
            font-size: 12px;
        }


        .ui-autocomplete li {
            font-size: 14px;
            width: 100%;
        }

        .ui-state-focus{
            background:none !important;
            color:#444 !important;
            border: 0px !important;
        }



        .header-content .nav > li .sub-menu li .fa{
            position: relative !important;
            float: left;
            font-size: 14px;
            top: 0;
            right: 10px;
        }
        .header-content .nav > li .sub-menu{
            min-width: 200px !important;
        }


        /* IE 6 doesn't support max-height
        * we use height instead, but this forces the menu to always be this tall
        */
        * html .ui-autocomplete {
            height: 350px;
        }

        ul.breadcrumb{
            margin-bottom: 50px !important;
        }

        .ui-helper-hidden-accessible{
            display: none !important;
        }
    </style>

</head>


<body>

<div class="page-wrapper">
    
    <header class="header header-transparent">
      @include('layouts.superlist.headmenu')
    </header><!-- /.header -->

    <div class="main">
        <div class="main-inner">
                @include($pages)
        </div><!-- /.main-inner -->
    </div><!-- /.main -->

    <footer class="footer">
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-left">
                &copy; All rights reserved for {{ CNF_APPNAME }} by {{ CNF_COMNAME }} | Powered by <a href="http://eyuva.com">EYUVA Technologies</a>
            </div><!-- /.footer-bottom-left -->

            <div class="footer-bottom-right">
                <ul class="nav nav-pills">
                    <li><a href="{{ url('') }}">Home</a></li>
                    <li><a href="{{ url('about-us') }}">About Us</a></li>
                    <li><a href="{{ url('terms-conditions') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ url('contact-us') }}">Contact</a></li>
                </ul><!-- /.nav -->
            </div><!-- /.footer-bottom-right -->
        </div><!-- /.container -->
    </div>
</footer><!-- /.footer -->

</div><!-- /.page-wrapper -->


<script src="{{ asset('sximo/themes/superlist/js/map.js')}}" type="text/javascript"></script>

<script src="{{ asset('sximo/themes/superlist/libraries/bootstrap-sass/javascripts/bootstrap/collapse.js')}}" type="text/javascript"></script>
<script src="{{ asset('sximo/themes/superlist/libraries/bootstrap-sass/javascripts/bootstrap/carousel.js')}}" type="text/javascript"></script>
<script src="{{ asset('sximo/themes/superlist/libraries/bootstrap-sass/javascripts/bootstrap/transition.js')}}" type="text/javascript"></script>
<script src="{{ asset('sximo/themes/superlist/libraries/bootstrap-sass/javascripts/bootstrap/dropdown.js')}}" type="text/javascript"></script>
<script src="{{ asset('sximo/themes/superlist/libraries/bootstrap-sass/javascripts/bootstrap/tooltip.js')}}" type="text/javascript"></script>
<script src="{{ asset('sximo/themes/superlist/libraries/bootstrap-sass/javascripts/bootstrap/tab.js')}}" type="text/javascript"></script>
<script src="{{ asset('sximo/themes/superlist/libraries/bootstrap-sass/javascripts/bootstrap/alert.js')}}" type="text/javascript"></script>

<script src="{{ asset('sximo/themes/superlist/libraries/colorbox/jquery.colorbox-min.js')}}" type="text/javascript"></script>

<script src="{{ asset('sximo/themes/superlist/libraries/flot/jquery.flot.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('sximo/themes/superlist/libraries/flot/jquery.flot.spline.js')}}" type="text/javascript"></script>

<script src="{{ asset('sximo/themes/superlist/libraries/bootstrap-select/bootstrap-select.min.js')}}" type="text/javascript"></script>

<script src="http://maps.googleapis.com/maps/api/js?libraries=weather,geometry,visualization,places,drawing&amp;sensor=false" type="text/javascript"></script>

<script type="text/javascript" src="{{ asset('sximo/themes/superlist/libraries/jquery-google-map/infobox.js')}}"></script>
<script type="text/javascript" src="{{ asset('sximo/themes/superlist/libraries/jquery-google-map/markerclusterer.js')}}"></script>
<script type="text/javascript" src="{{ asset('sximo/themes/superlist/libraries/jquery-google-map/jquery-google-map.js')}}"></script>

<script type="text/javascript" src="{{ asset('sximo/themes/superlist/libraries/owl.carousel/owl.carousel.js')}}"></script>
<script type="text/javascript" src="{{ asset('sximo/themes/superlist/libraries/bootstrap-fileinput/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('sximo/js/plugins/fancybox/jquery.fancybox.js') }}"></script>
<script type="text/javascript" src="{{ asset('sximo/themes/sximone/js/fancybox/source/jquery.fancybox.js') }}"></script>
<script src="{{ asset('sximo/themes/superlist/js/superlist.js')}}" type="text/javascript"></script>
<script>
    $('.previewImage').fancybox({
                width : '500px',
                height : '500px'}
    );
</script>
</body>
</html>
