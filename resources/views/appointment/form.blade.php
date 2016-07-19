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

		 {!! Form::open(array('url'=>'appointment/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Appointment</legend>
									
				  <div class="form-group hidethis " style="display:none;"> 
					<label for="AppointmentID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('AppointmentID', (isset($fields['AppointmentID']['language'])? $fields['AppointmentID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('AppointmentID', $row['AppointmentID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Doctor" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Doctor', (isset($fields['DoctorID']['language'])? $fields['DoctorID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
						<label class="control-label">
							{!! SiteHelpers::gridDisplayView($row->DoctorID,'DoctorID','1:tb_doctor,tb_users:DoctorID:first_name|last_name',"id = tb_doctor.UserID") !!}
						</label>
						<input type="hidden" name="DoctorID" id="DoctorID" value="{{ $row['DoctorID'] }}">
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Patient" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Patient', (isset($fields['PatientID']['language'])? $fields['PatientID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
						<label class="control-label">
							{!! SiteHelpers::gridDisplayView($row['PatientID'],'PatientID','1:tb_patient:PatientID:first_name|last_name') !!}
						</label>
						<input type="hidden" name="PatientID" id="PatientID" value="{{ $row['PatientID'] }}">
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
					<label for="From" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('From', (isset($fields['StartAt']['language'])? $fields['StartAt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('StartAt', $row['StartAt'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="To" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('To', (isset($fields['EndAt']['language'])? $fields['EndAt']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('EndAt', $row['EndAt'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
				 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group hidethis " style="display:none;">
					<label for="CreatedAt" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('CreatedAt', (isset($fields['CreatedAt']['language'])? $fields['CreatedAt']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('CreatedAt', $row['CreatedAt'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div>
					 <div class="col-md-2">

					 </div>
				  </div>
				  {{--<div class="form-group  " > --}}
					{{--<label for="Type" class=" control-label col-md-4 text-left"> --}}
					{{--{!! SiteHelpers::activeLang('Type', (isset($fields['Type']['language'])? $fields['Type']['language'] : array())) !!}	--}}
					{{--</label>--}}
					{{--<div class="col-md-6">--}}
					  {{--{!! Form::text('Type', $row['Type'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} --}}
					 {{--</div> --}}
					 {{--<div class="col-md-2">--}}
					 	{{----}}
					 {{--</div>--}}
				  {{--</div> 					--}}
				  <div class="form-group  " > 
					<label for="Diagnosis" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Diagnosis', (isset($fields['Diagnosis']['language'])? $fields['Diagnosis']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Diagnosis', $row['Diagnosis'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<button type="button" onclick="location.href='{{ URL::to('appointment?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		$('.addC').relCopy({});
		
        $("#DoctorID").jCombo("{{ URL::to('appointment/comboselect?filter=tb_users,tb_doctor:DoctorID:first_name|last_name&limit=Where:id:=:tb_doctor.UserID') }}",
        {  selected_value : '{{ $row["DoctorID"] }}' });
        
        $("#PatientID").jCombo("{{ URL::to('appointment/comboselect?filter=tb_patient:PatientID:first_name|last_name') }}",
        {  selected_value : '{{ $row["PatientID"] }}' });
        
        $("#ClinicID").jCombo("{{ URL::to('appointment/comboselect?filter=tb_clinic:ClinicID:Name|Address') }}",
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