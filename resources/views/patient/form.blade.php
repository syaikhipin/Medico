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

		 {!! Form::open(array('url'=>'patients/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Family Member </legend>
									
				  <div class="form-group hidethis " style="display:none;"> 
					<label for="PatientID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('PatientID', (isset($fields['PatientID']['language'])? $fields['PatientID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('PatientID', $row['PatientID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="First Name" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('First Name', (isset($fields['first_name']['language'])? $fields['first_name']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('first_name', $row['first_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Last Name" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Last Name', (isset($fields['last_name']['language'])? $fields['last_name']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('last_name', $row['last_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="BirthDate" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('BirthDate', (isset($fields['BirthDate']['language'])? $fields['BirthDate']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('BirthDate', $row['BirthDate'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Height" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Height', (isset($fields['Height']['language'])? $fields['Height']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Height', $row['Height'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Weight" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Weight', (isset($fields['Weight']['language'])? $fields['Weight']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Weight', $row['Weight'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div>
							<input type="hidden" name="next" value="{!! $next !!}">
				  <div class="form-group  " > 
					<label for="Gender" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Gender', (isset($fields['Gender']['language'])? $fields['Gender']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $Gender = explode(',',$row['Gender']);
					$Gender_opt = array( '' => '--Please Select--' ,  'male' => 'Male' ,  'female' => 'Female' ,  'other' => 'Other' , ); ?>
					<select name='Gender' rows='5' required  class='select2 '  > 
						<?php 
						foreach($Gender_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['Gender'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Blood Group" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Blood Group', (isset($fields['BloodGroup']['language'])? $fields['BloodGroup']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<?php $BloodGroup = explode(',',$row['BloodGroup']);
					$BloodGroup_opt = array( '' => '--Please Select--' ,  'A+' => 'A+' ,  'A-' => 'A-' ,  'B+' => 'B+' ,  'B-' => 'B-' ,  'AB+' => 'AB+' ,  'AB-' => 'AB-' ,  'O+' => 'O+' ,  'O-' => 'O-' , ); ?>
					<select name='BloodGroup' rows='5' required  class='select2 '  > 
						<?php 
						foreach($BloodGroup_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['BloodGroup'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Issue" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('History', (isset($fields['History']['language'])? $fields['History']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  {!! Form::text('History', $row['History'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div>

							<div class="form-group  " >
								<label for="Email" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Email', (isset($fields['Email']['language'])? $fields['Email']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('Email', $row['Email'],array('class'=>'form-control', 'placeholder'=>'','required'=>'true', 'parsley-type'=>'email'   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Contact Number" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('ContactNo', (isset($fields['ContactNo']['language'])? $fields['ContactNo']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('ContactNo', $row['ContactNo'],array('class'=>'form-control parsley-tel', 'placeholder'=>'','required'=>'true','parsley-type'=>'phone'   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>

							<div class="form-group hidethis" style="display: none">
					<label for="Last Visit" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Last Visit', (isset($fields['LatestVisit']['language'])? $fields['LatestVisit']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('LatestVisit', $row['LatestVisit'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<button type="button" onclick="location.href='{{ URL::to('patients?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop