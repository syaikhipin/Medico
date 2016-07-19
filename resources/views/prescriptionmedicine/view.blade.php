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
		<li><a href="{{ URL::to('prescriptionmedicine?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {{ Lang::get('core.detail') }} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper">   
	   <div class="toolbar-line">
	   		<a href="{{ URL::to('prescriptionmedicine?return='.$return) }}" class="tips btn btn-xs btn-default" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('prescriptionmedicine/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{{ Lang::get('core.btn_edit') }}</a>
			@endif  		   	  
		</div>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	


	
	<table class="table table-striped table-bordered" >
		<tbody>	
	
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('PresMedicineID', (isset($fields['PresMedicineID']['language'])? $fields['PresMedicineID']['language'] : array())) }}	
						</td>
						<td>{{ $row->PresMedicineID }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('PrescriptionID', (isset($fields['PrescriptionID']['language'])? $fields['PrescriptionID']['language'] : array())) }}	
						</td>
						<td>{{ $row->PrescriptionID }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('MedicineID', (isset($fields['MedicineID']['language'])? $fields['MedicineID']['language'] : array())) }}	
						</td>
						<td>{{ $row->MedicineID }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('MorningDose', (isset($fields['MorningDose']['language'])? $fields['MorningDose']['language'] : array())) }}	
						</td>
						<td>{{ $row->MorningDose }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('AfternoonDose', (isset($fields['AfternoonDose']['language'])? $fields['AfternoonDose']['language'] : array())) }}	
						</td>
						<td>{{ $row->AfternoonDose }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('EveningDose', (isset($fields['EveningDose']['language'])? $fields['EveningDose']['language'] : array())) }}	
						</td>
						<td>{{ $row->EveningDose }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('BeforeMeal', (isset($fields['BeforeMeal']['language'])? $fields['BeforeMeal']['language'] : array())) }}	
						</td>
						<td>{{ $row->BeforeMeal }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Entry By', (isset($fields['entry_by']['language'])? $fields['entry_by']['language'] : array())) }}	
						</td>
						<td>{{ $row->entry_by }} </td>
						
					</tr>
				
		</tbody>	
	</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop