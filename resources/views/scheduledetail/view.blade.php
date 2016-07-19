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
		<li><a href="{{ URL::to('scheduledetail?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {{ Lang::get('core.detail') }} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper">   
	   <div class="toolbar-line">
	   		<a href="{{ URL::to('scheduledetail?return='.$return) }}" class="tips btn btn-xs btn-default" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('scheduledetail/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{{ Lang::get('core.btn_edit') }}</a>
			@endif  		   	  
		</div>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	


	
	<table class="table table-striped table-bordered" >
		<tbody>	
	
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('ScheduleDetailID', (isset($fields['ScheduleDetailID']['language'])? $fields['ScheduleDetailID']['language'] : array())) }}	
						</td>
						<td>{{ $row->ScheduleDetailID }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('ScheduleID', (isset($fields['ScheduleID']['language'])? $fields['ScheduleID']['language'] : array())) }}	
						</td>
						<td>{{ $row->ScheduleID }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('FirstSessionStart', (isset($fields['FirstSessionStart']['language'])? $fields['FirstSessionStart']['language'] : array())) }}	
						</td>
						<td>{{ $row->FirstSessionStart }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('FirstSessionEnd', (isset($fields['FirstSessionEnd']['language'])? $fields['FirstSessionEnd']['language'] : array())) }}	
						</td>
						<td>{{ $row->FirstSessionEnd }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('SecondSessionStart', (isset($fields['SecondSessionStart']['language'])? $fields['SecondSessionStart']['language'] : array())) }}	
						</td>
						<td>{{ $row->SecondSessionStart }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('SecondSessionEnd', (isset($fields['SecondSessionEnd']['language'])? $fields['SecondSessionEnd']['language'] : array())) }}	
						</td>
						<td>{{ $row->SecondSessionEnd }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Day', (isset($fields['Day']['language'])? $fields['Day']['language'] : array())) }}	
						</td>
						<td>{{ $row->Day }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Entry By', (isset($fields['entry_by']['language'])? $fields['entry_by']['language'] : array())) }}	
						</td>
						<td>{{ $row->entry_by }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Morning Slots', (isset($fields['morning_slots']['language'])? $fields['morning_slots']['language'] : array())) }}	
						</td>
						<td>{{ $row->morning_slots }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Afternoon Slots', (isset($fields['afternoon_slots']['language'])? $fields['afternoon_slots']['language'] : array())) }}	
						</td>
						<td>{{ $row->afternoon_slots }} </td>
						
					</tr>
				
		</tbody>	
	</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop