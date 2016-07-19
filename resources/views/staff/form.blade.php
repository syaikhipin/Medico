<link rel="stylesheet" href="{{ asset('sximo/js/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css') }}">
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
		<li><a href="{{ URL::to('staff?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active">{{ Lang::get('core.addedit') }} </li>
      </ul>
	  	  
    </div>
 
 	<div class="page-content-wrapper">

		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
	<div class="sbox-content"> 	

		 {!! Form::open(array('url'=>'staff/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> staff</legend>
									
				  <div class="form-group hidethis " style="display:none;"> 
					<label for="StaffID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('StaffID', (isset($fields['StaffID']['language'])? $fields['StaffID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('StaffID', $row['StaffID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Name" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Name', (isset($fields['Name']['language'])? $fields['Name']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Name', $row['Name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Email" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Email', (isset($fields['Email']['language'])? $fields['Email']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Email', $row['Email'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'email'   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="ContactNo" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Contact Number', (isset($fields['ContactNo']['language'])? $fields['ContactNo']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('ContactNo', $row['ContactNo'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'number'   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Clinic" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Clinic', (isset($fields['ClinicID']['language'])? $fields['ClinicID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='ClinicID' rows='5' id='ClinicID' class='select2 '   ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Duty  Start" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Duty  Start', (isset($fields['DutyStart']['language'])? $fields['DutyStart']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					<input type="time" name="DutyStart" id="DutyStart" value="{{ $row['DutyStart'] }}" class="form-control bootstrap-timepicker" style='width:150px !important;' >
				</div>
				 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Duty End" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Duty End', (isset($fields['DutyEnd']['language'])? $fields['DutyEnd']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					<input type="time" name="DutyEnd" id="DutyEnd" value="{{ $row['DutyEnd'] }}" class="form-control bootstrap-timepicker-component bootstrap-timepicker" style='width:150px !important;' >
				</div>
				 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Role" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Role', (isset($fields['Role']['language'])? $fields['Role']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $Role = explode(',',$row['Role']);
					$Role_opt = array( '' => '--Select--' ,  'Nurse' => 'Nurse' , 'Compounder' =>'Compounder'); ?>
					<select name='Role' rows='5'   class='select2 '  > 
						<?php 
						foreach($Role_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['Role'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
					<button type="button" onclick="location.href='{{ URL::to('staff?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>
  <script type="text/javascript" src="{{ asset('sximo/js/plugins/bootstrap-timepicker/bootstrap-timepicker.js') }}"> </script>
   <script type="text/javascript">
	$(document).ready(function() {

		$('.bootstrap-timepicker').timepicker({
			showMeridian: false,
			showInputs: false,
			minuteStep: 5
		});
        $("#ClinicID").jCombo("{{ URL::to('staff/comboselect?filter=tb_clinic:ClinicID:Name|Address') }}",
        {  selected_value : '{{ $row["ClinicID"] }}' });
         

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop