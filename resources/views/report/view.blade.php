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
		<li><a href="{{ URL::to('report?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {{ Lang::get('core.detail') }} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper">   
	   <div class="toolbar-line">
	   		<a href="{{ URL::to('report?return='.$return) }}" class="tips btn btn-xs btn-default" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('report/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{{ Lang::get('core.btn_edit') }}</a>
			@endif  		   	  
		</div>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	


	
	<table class="table table-striped table-bordered" >
		<tbody>	
	

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Patient', (isset($fields['PatientID']['language'])? $fields['PatientID']['language'] : array())) }}
						</td>
						<td>{!! \SiteHelpers::gridDisplayView($row->PatientID,'PatientID','1:tb_patient:PatientID:first_name|last_name') !!}</td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Title', (isset($fields['Title']['language'])? $fields['Title']['language'] : array())) }}	
						</td>
						<td>{{ $row->Title }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('File', (isset($fields['File']['language'])? $fields['File']['language'] : array())) }}	
						</td>
						<td>
							<a href="{!! url($fields['File']['option']['path_to_upload'].'/'. $row->File) !!}">View</a>
						</td>
						
					</tr>
		</tbody>
	</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop