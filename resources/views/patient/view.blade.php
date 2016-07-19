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
		<li><a href="{{ URL::to('patients?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {{ Lang::get('core.detail') }} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper">   
	   <div class="toolbar-line">
	   		<a href="{{ URL::to('patients?return='.$return) }}" class="tips btn btn-xs btn-default" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('patients/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{{ Lang::get('core.btn_edit') }}</a>
			@endif  		   	  
		</div>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	


	
	<table class="table table-striped table-bordered" >
		<tbody>	
	
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Name', (isset($fields['first_name']['language'])? $fields['first_name']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->first_name,'first_name','1:tb_patient:first_name:first_name|last_name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('BirthDate', (isset($fields['BirthDate']['language'])? $fields['BirthDate']['language'] : array())) }}	
						</td>
						<td>{{ $row->BirthDate }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Height', (isset($fields['Height']['language'])? $fields['Height']['language'] : array())) }}	
						</td>
						<td>{{ $row->Height }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Weight', (isset($fields['Weight']['language'])? $fields['Weight']['language'] : array())) }}	
						</td>
						<td>{{ $row->Weight }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Gender', (isset($fields['Gender']['language'])? $fields['Gender']['language'] : array())) }}	
						</td>
						<td>{{ $row->Gender }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Blood Group', (isset($fields['BloodGroup']['language'])? $fields['BloodGroup']['language'] : array())) }}	
						</td>
						<td>{{ $row->BloodGroup }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('History', (isset($fields['History']['language'])? $fields['History']['language'] : array())) }}
						</td>
						<td>{{ $row->History }} </td>
						
					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Contact Number', (isset($fields['ContactNo']['language'])? $fields['ContactNo']['language'] : array())) }}
						</td>
						<td>{{ $row->ContactNo }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Email', (isset($fields['Email']['language'])? $fields['Email']['language'] : array())) }}
						</td>
						<td>{{ $row->Email }} </td>

					</tr>

				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Last Visit', (isset($fields['LatestVisit']['language'])? $fields['LatestVisit']['language'] : array())) }}	
						</td>
						<td>{{ $row->LatestVisit }} </td>
						
					</tr>
				
		</tbody>	
	</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop