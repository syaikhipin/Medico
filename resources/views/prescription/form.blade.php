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
		<li><a href="{{ URL::to('prescription?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'prescription/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Prescription</legend>
									
				  <div class="form-group hidethis " style="display:none;"> 
					<label for="Prescription" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Prescription', (isset($fields['PrescriptionID']['language'])? $fields['PrescriptionID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('PrescriptionID', $row['PrescriptionID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<label for="Appointment" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Appointment', (isset($fields['AppointmentID']['language'])? $fields['AppointmentID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
						<label class="control-label">
							{!! date('d-m-Y h:i a',strtotime(SiteHelpers::gridDisplayView($row['AppointmentID'],'AppointmentID','1:tb_appointment:AppointmentID:StartAt'))) !!}
							<input type="hidden" name="AppointmentID" id="AppointmentID" value="{{ $row['AppointmentID'] }}">
						</label>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> </fieldset>
			</div>
			
			

			

	@if($subgrid['access']['is_add'] == '1')				
	<hr /><div class="clr clear"></div>	
	
	<h5> Prescription Detail </h5>
	
	<div class="table-responsive">
	    <table class="table table-striped ">
	        <thead>
				<tr>
					@foreach ($subgrid['tableGrid'] as $t)
						@if($t['view'] =='1' && $t['field'] !='PrescriptionID')
							<th>
							{{ SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) }}
							</th>
						@endif
					@endforeach
					<th></th>	
				  </tr>

	        </thead>

        <tbody>
        @if(count($subgrid['rowData'])>=1)
            @foreach ($subgrid['rowData'] as $rows)
	            <tr class="clone clonedInput">									
					 @foreach ($subgrid['tableGrid'] as $field)
						 @if($field['view'] =='1' && $field['field'] !='PrescriptionID')
						 <td>					 
						 	{!! SiteHelpers::bulkForm($field['field'] , $subgrid['tableForm'] , $rows->$field['field']) !!}							 
						 </td>
						 @endif					 
					 
					 @endforeach
					 <td>
					 	<a onclick=" $(this).parents('.clonedInput').remove(); return false" href="#" class="remove btn btn-xs btn-danger">-</a>
					 	<input type="hidden" name="counter[]">
					 </td>
				</tr>  
			@endforeach
			

		@else

            <tr class="clone clonedInput">								
			 @foreach ($subgrid['tableGrid'] as $field)

				 @if($field['view'] =='1' && $field['field'] !='PrescriptionID')
				 <td>					 
				 	{!! SiteHelpers::bulkForm($field['field'] , $subgrid['tableForm'] ) !!}							 
				 </td>
				 @endif					 
			 
			 @endforeach
				 <td>
				 	<a onclick=" $(this).parents('.clonedInput').remove(); return false" href="#" class="remove btn btn-xs btn-danger">-</a>
				 	<input type="hidden" name="counter[]">
				 </td>
			
			</tr> 	
		@endif	


        </tbody>	

     	</table>  
    	<input type="hidden" name="enable-masterdetail" value="true">
    </div><br /><br />
     
     <a href="javascript:void(0);" class="addC btn btn-xs btn-info" rel=".clone"><i class="fa fa-plus"></i> New Item</a>
     <hr />		
	@endif
     
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
					<button type="button" onclick="location.href='{{ URL::to('prescription?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
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




		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop