@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('sximo/templates/mailbox.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('sximo/templates/summernote-bs3.css') }}">
<div class="page-content row">
  <!-- Begin Header & Breadcrumb -->
    

	<!-- Begin Content -->
	<div class="page-content-wrapper m-t">
		<div class="resultData"></div>
		<div class="ajaxLoading"></div>
		<div id="{{ $pageModule }}View"></div>			
		<div id="{{ $pageModule }}Grid"></div>
	</div>	
	<!-- End Content -->  
</div>	
<script>
$(document).ready(function(){
	reloadData('#{{ $pageModule }}','{{ $pageModule }}/data');	
});	
</script>	
@endsection