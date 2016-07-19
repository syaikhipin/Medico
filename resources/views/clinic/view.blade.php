@extends('layouts.app')

@section('content')
<div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
		<li><a href="{{ URL::to('clinic?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {{ Lang::get('core.detail') }} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper">   
	   <div class="toolbar-line">
	   		<a href="{{ URL::to('clinic?return='.$return) }}" class="tips btn btn-xs btn-default" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('clinic/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{{ Lang::get('core.btn_edit') }}</a>
			@endif  		   	  
		</div>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	


	
	<table class="table table-striped table-bordered" >
		<tbody>

		<tr>
			<td colspan="2" style="text-align: center"> {!! SiteHelpers::showUploadedFile($row->Photo,'/uploads/clinics/',100,'square') !!} </td>
		</tr>
	
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Name', (isset($fields['Name']['language'])? $fields['Name']['language'] : array())) }}	
						</td>
						<td>{{ $row->Name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Speciality', (isset($fields['Speciality']['language'])? $fields['Speciality']['language'] : array())) }}	
						</td>
						<td>{{ $row->Speciality }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Description', (isset($fields['Description']['language'])? $fields['Description']['language'] : array())) }}	
						</td>
						<td>{{ $row->Description }} </td>
						
					</tr>
				

				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Address', (isset($fields['Address']['language'])? $fields['Address']['language'] : array())) }}	
						</td>
						<td>{{ $row->Address }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('City', (isset($fields['City']['language'])? $fields['City']['language'] : array())) }}	
						</td>
						<td>{{ $row->City }} </td>
						
					</tr>

				
		</tbody>	
	</table>

	<hr/>
	<h5>Gallary</h5>
		<div>
			<?php

			$row->Gallary = $row->Gallary!="" ? explode(",",$row->Gallary) : array()?>
			@if(empty($row->Gallary))
				No Photos
			@endif
			@foreach($row->Gallary as $photo)
				{!! \SiteHelpers::showUploadedFile($photo,'/uploads/clinics/gallery/',100,'square') !!}
			@endforeach
		</div>


	 	
	{{--@if($subgrid['access']['is_detail'] == '1')	--}}
		{{--<hr />	--}}
		{{--<h5> Staff </h5>--}}
	{{----}}
		{{--<div class="table-responsive">--}}
	    {{--<table class="table table-striped ">--}}
	        {{--<thead>--}}
				{{--<tr>--}}
					{{--<th class="number"> No </th>--}}
						{{--@foreach ($subgrid['tableGrid'] as $t)--}}
						{{--@if($t['view'] =='1')--}}
							{{--<th>--}}
								{{--{{ SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) }}--}}
							{{--</th>--}}
						{{--@endif--}}
					{{--@endforeach--}}
					{{----}}
				  {{--</tr>--}}
	        {{--</thead>--}}

	        {{--<tbody>--}}
	            {{--@foreach ($subgrid['rowData'] as $row)--}}
	            {{--<tr>--}}
					{{--<td width="30">  </td>		--}}
				 {{--@foreach ($subgrid['tableGrid'] as $field)--}}
					 {{--@if($field['view'] =='1' )--}}
					 {{--<td>					 --}}
					 	{{--@if($field['attribute']['image']['active'] =='1')--}}
							{{--{!! SiteHelpers::showUploadedFile($row->$field['field'],$field['attribute']['image']['path']) !!}--}}
						{{--@else	--}}
							{{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array() ) /*--}}
							{{--{!! SiteHelpers::gridDisplay($row->$field['field'],$field['field'],$conn) !!}	--}}
						{{--@endif						 --}}
					 {{--</td>--}}
					 {{--@endif					 --}}
				 {{----}}
				 {{--@endforeach--}}
				{{--@endforeach--}}
				{{--</tr> --}}


	        {{--</tbody>	--}}

	     {{--</table>   --}}
	     {{--</div>--}}
	{{--@endif--}}
     {{----}}
	
	</div>
</div>	

	</div>
</div>
	  
@stop