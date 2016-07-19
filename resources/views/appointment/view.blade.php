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
		<li><a href="{{ URL::to('appointment?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {{ Lang::get('core.detail') }} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper">   
	   <div class="toolbar-line">
	   		<a href="{{ URL::to('appointment?return='.$return) }}" class="tips btn btn-xs btn-default" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
			@if($access['is_edit'] ==1)
	   		<a href="{{ URL::to('appointment/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{{ Lang::get('core.btn_edit') }}</a>
			@endif  		   	  
		</div>
<div class="sbox animated fadeInRight">
	<div class="sbox-title" style="padding: 7px 15px;"> <h4 style="float: left"> <i class="fa fa-table"></i> </h4>
	<form method="post" action="{{ url('prescription/update') }}" style="float: right">
		<input type="hidden" name="PatientID" value="{{$row->PatientID}}">
		<input type="hidden" name="AppointmentID" value="{{$row->AppointmentID}}">
		<button type="submit"  class="btn btn-success" name="createprescription"> Generate Prescription </button>
	</form>
	</div>
	<div class="sbox-content"> 	


	
	<table class="table table-striped table-bordered" >
		<tbody>	
	
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Doctor', (isset($fields['DoctorID']['language'])? $fields['DoctorID']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->DoctorID,'DoctorID','1:tb_doctor,tb_users:DoctorID:first_name|last_name',"id = tb_doctor.UserID") !!}</td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Patient', (isset($fields['PatientID']['language'])? $fields['PatientID']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->PatientID,'PatientID','1:tb_patient:PatientID:first_name|last_name') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Clinic', (isset($fields['ClinicID']['language'])? $fields['ClinicID']['language'] : array())) }}	
						</td>
						<td>{!! SiteHelpers::gridDisplayView($row->ClinicID,'ClinicID','1:tb_clinic:ClinicID:Name|Address') !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('From', (isset($fields['StartAt']['language'])? $fields['StartAt']['language'] : array())) }}	
						</td>
						<td>{{ date('h:i a',strtotime($row->StartAt)) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('To', (isset($fields['EndAt']['language'])? $fields['EndAt']['language'] : array())) }}	
						</td>
						<td>{{  date('h:i a',strtotime($row->EndAt)) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Date', (isset($fields['CreatedAt']['language'])? $fields['CreatedAt']['language'] : array())) }}	
						</td>
						<td>{{ $row->CreatedAt }} </td>
						
					</tr>
				
					{{--<tr>--}}
						{{--<td width='30%' class='label-view text-right'>--}}
							{{--{{ SiteHelpers::activeLang('Type', (isset($fields['Type']['language'])? $fields['Type']['language'] : array())) }}	--}}
						{{--</td>--}}
						{{--<td>{{ $row->Type }} </td>--}}
						{{----}}
					{{--</tr>--}}
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Diagnosis', (isset($fields['Diagnosis']['language'])? $fields['Diagnosis']['language'] : array())) }}	
						</td>
						<td>{{ $row->Diagnosis }} </td>
						
					</tr>
				
		</tbody>	
	</table>   

	 	
	{{--@if($subgrid['access']['is_detail'] == '1')	--}}
		{{--<hr />	--}}
		{{--<h5> Prescription </h5>--}}
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
     
	
	</div>
</div>	

	</div>
</div>
	  
@stop