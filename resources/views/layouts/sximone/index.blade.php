<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> {{ CNF_APPNAME }} | {{ $pageTitle}} </title>
<meta name="keywords" content="{{ $pageMetakey }}">
<meta name="description" content="{{ $pageMetadesc }}"/>
<link rel="shortcut icon" href="" type="image/x-icon">	

		<link href="{{ asset('sximo/themes/sximone/css/bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ asset('sximo/themes/sximone/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
		<link href="{{ asset('sximo/themes/sximone/css/animate.css') }}" rel="stylesheet">
		<link href="{{ asset('sximo/css/icons.min.css') }}" rel="stylesheet">
		<link href="{{ asset('sximo/themes/sximone/js/fancybox/source/jquery.fancybox.css') }}" rel="stylesheet">
		<link href="{{ asset('sximo/themes/sximone/js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7') }}" rel="stylesheet">
		<link href="{{ asset('sximo/themes/sximone/css/sximone.css') }}" rel="stylesheet">
		<link href="{{ asset('sximo/js/plugins/select2/select2.css') }}" rel="stylesheet">
		<link href="{{ asset('sximo/css/animate.css') }}" rel="stylesheet">
	<link href="{{ asset('sximo/js/plugins/fancybox/jquery.fancybox.css') }}" rel="stylesheet">

		<script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery.min.js') }}"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<script type="text/javascript" src="{{ asset('sximo/themes/sximone/js/bootstrap.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/parsley.js') }}"></script>
	<script type="text/javascript" src="{{ asset('sximo/js/plugins/fancybox/jquery.fancybox.js') }}"></script>
	<script type="text/javascript" src="{{ asset('sximo/themes/sximone/js/fancybox/source/jquery.fancybox.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/themes/sximone/js/jquery.mixitup.min.js') }}"></script>				

		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		<style>




			.ui-autocomplete span.hl_results {
				background-color: #ffff66;
			}

			/* loading - the AJAX indicator */
			.ui-autocomplete-loading {
				background: white ;
			}

			/* scroll results */
			/*.ui-autocomplete {*/
				/*max-height: 250px;*/
				/*overflow-y: auto;*/
				/*/!* prevent horizontal scrollbar *!/*/
				/*overflow-x: hidden;*/
				/*/!* add padding for vertical scrollbar *!/*/
				/*padding: 0px 5px !important;*/
				/*background-color: white !important;*/
				/*width: 274px;*/
				/*list-style-type: none;*/
				/*z-index: 1000;*/
				/*margin:0px;*/
			/*}*/

			.ui-autocomplete {
				background-color: rgba(0, 0, 0, 0.4) !important;
				box-sizing: border-box !important;
				color: #fff !important;
				padding: 10px !important;
				width: 205px !important;
				z-index: 1000 !important;
				list-style-type: none !important;
			}

			@media(max-width: 768px){
				.ui-autocomplete{
					width: 95% !important;
				}

			}

			.ui-autocomplete li {
				border-bottom: 1px solid !important;
				padding-bottom: 10px !important;
			}
			.keyword{
				color:#ccc;
				font-size: 12px;
			}


			.ui-autocomplete li {
				font-size: 14px;
				width: 100%;
			}

			.ui-autocomplete li:hover{
				border: solid 2px #c3c3c3;

			}

			/* IE 6 doesn't support max-height
            * we use height instead, but this forces the menu to always be this tall
            */
			* html .ui-autocomplete {
				height: 250px;
			}
		</style>
	
  	</head>

<body >
<div class="pre-header">
    <div class="container">
        <div class="row">
            <!-- BEGIN TOP BAR LEFT PART -->
            <div class="col-md-6 col-sm-6 left">
                 {{--<ul class="list-unstyled list-inline">--}}
                	{{--<li> {{ Lang::get('core.language') }} : </li>--}}
					{{--@foreach(SiteHelpers::langOption() as $lang)--}}
						{{--<li><a href="{{ URL::TO('home/lang/'.$lang['folder']) }}"> --}}
							{{--<span @if(Session::get('lang') == $lang['folder']) class="label label-success" @endif>--}}
							{{--{{  strtoupper($lang['folder']) }} / </span>  --}}
						{{--</a></li>--}}
					{{--@endforeach--}}
                {{--</ul>               --}}
            </div>
            <!-- END TOP BAR LEFT PART -->
            <!-- BEGIN TOP BAR MENU -->
            <div class="col-md-6 col-sm-6 right">
                <ul class="list-unstyled list-inline pull-right">
                     @if(!Auth::check()) 
					 	<li><a href="{{ URL::to('user/login') }}"><i class="fa fa-sign-in"></i> {{ Lang::get('core.signin') }}</a></li>
                    	<li><a href="{{ URL::to('user/register') }}"><i class="fa fa-user"></i> {{ Lang::get('core.signup') }}</a></li>
					 @else
					 	<li><a href="{{ URL::to('user/profile') }}"><i class="fa fa-user "></i> {{ Lang::get('core.m_myaccount') }}</a></li>
                    	<li><a href="{{ URL::to('dashboard') }}"><i class="fa fa-desktop"></i> {{ Lang::get('core.m_dashboard') }}</a></li>	
						<li><a href="{{ URL::to('user/logout') }}"><i class="fa  fa-sign-out"></i> {{ Lang::get('core.m_logout') }}</a></li>					 
					 @endif	
                </ul>
            </div>
            <!-- END TOP BAR MENU -->
        </div>
    </div>        
</div>

<header>	
	<div class="container">
		<div class="navbar navbar-default ">			
				<div class="navbar-header">
					<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="{{ URL::to('') }}" class="navbar-brand site-logo">
					<img src="{{ asset('sximo/themes/sximone/img/logo.png')}}" class="img-responsive" width="70" height="70" />
					<span class="logo_title"> {{ CNF_APPNAME }}</span>
					<span class="logo_subtitle">{{ CNF_APPDESC }}</span>
					</a>
				</div>

				<div class="navbar-collapse collapse">
					@include('layouts/sximone/topbar')
				</div><!--/nav-collapse -->
			</div><!-- /container -->
		</div>
</header>		
		
<div style="min-height:400px;">
@include($pages)
</div>

<div class="clr"></div>
	




<div id="footer">
	<div class=" container">
		<div class="text-center"> Copyright 2014 {{ CNF_APPNAME }} . ALL Rights Reserved. </div>
		
	</div>	
</div>

<div class="modal fade" id="sximo-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-default">
		
			<button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Modal title</h4>
		</div>
		<div class="modal-body" id="sximo-modal-content">
	
		</div>
	</div>
</div>
</div>


<script>
	jQuery(document).ready(function() {
		window.prettyPrint && prettyPrint();

	$('.previewImage').fancybox({
			width : '500px',
			height : '500px'}
	);
	});
</script>	
</body> 
</html>