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

		 {!! Form::open(array('url'=>'scheduledetail/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> ScheduleDetail</legend>
									
				  <div class="form-group  " > 
					<label for="ScheduleDetailID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ScheduleDetailID', (isset($fields['ScheduleDetailID']['language'])? $fields['ScheduleDetailID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('ScheduleDetailID', $row['ScheduleDetailID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="ScheduleID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ScheduleID', (isset($fields['ScheduleID']['language'])? $fields['ScheduleID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='ScheduleID' rows='5' id='ScheduleID' class='select2 '   ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="FirstSessionStart" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('FirstSessionStart', (isset($fields['FirstSessionStart']['language'])? $fields['FirstSessionStart']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('FirstSessionStart', $row['FirstSessionStart'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="FirstSessionEnd" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('FirstSessionEnd', (isset($fields['FirstSessionEnd']['language'])? $fields['FirstSessionEnd']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('FirstSessionEnd', $row['FirstSessionEnd'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="SecondSessionStart" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('SecondSessionStart', (isset($fields['SecondSessionStart']['language'])? $fields['SecondSessionStart']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('SecondSessionStart', $row['SecondSessionStart'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="SecondSessionEnd" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('SecondSessionEnd', (isset($fields['SecondSessionEnd']['language'])? $fields['SecondSessionEnd']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('SecondSessionEnd', $row['SecondSessionEnd'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Day" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Day', (isset($fields['Day']['language'])? $fields['Day']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Day', $row['Day'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Morning Slots" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Morning Slots', (isset($fields['morning_slots']['language'])? $fields['morning_slots']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('morning_slots', $row['morning_slots'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Afternoon Slots" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Afternoon Slots', (isset($fields['afternoon_slots']['language'])? $fields['afternoon_slots']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('afternoon_slots', $row['afternoon_slots'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<button type="button" onclick="location.href='{{ URL::to('scheduledetail?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		
        $("#ScheduleID").jCombo("{{ URL::to('scheduledetail/comboselect?filter=tb_clinic_schedule:ScheduleID:ScheduleID') }}",
        {  selected_value : '{{ $row["ScheduleID"] }}' });
         

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop