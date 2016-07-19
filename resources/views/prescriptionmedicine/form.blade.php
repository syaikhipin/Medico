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

		 {!! Form::open(array('url'=>'prescriptionmedicine/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> PrescriptionMedicine</legend>
									
				  <div class="form-group  " > 
					<label for="PresMedicineID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('PresMedicineID', (isset($fields['PresMedicineID']['language'])? $fields['PresMedicineID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('PresMedicineID', $row['PresMedicineID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="PrescriptionID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('PrescriptionID', (isset($fields['PrescriptionID']['language'])? $fields['PrescriptionID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='PrescriptionID' rows='5' id='PrescriptionID' class='select2 '   ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="MedicineID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('MedicineID', (isset($fields['MedicineID']['language'])? $fields['MedicineID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('MedicineID', $row['MedicineID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="MorningDose" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('MorningDose', (isset($fields['MorningDose']['language'])? $fields['MorningDose']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('MorningDose', $row['MorningDose'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="AfternoonDose" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('AfternoonDose', (isset($fields['AfternoonDose']['language'])? $fields['AfternoonDose']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('AfternoonDose', $row['AfternoonDose'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="EveningDose" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('EveningDose', (isset($fields['EveningDose']['language'])? $fields['EveningDose']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('EveningDose', $row['EveningDose'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="BeforeMeal" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('BeforeMeal', (isset($fields['BeforeMeal']['language'])? $fields['BeforeMeal']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('BeforeMeal', $row['BeforeMeal'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<button type="button" onclick="location.href='{{ URL::to('prescriptionmedicine?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		
        $("#PrescriptionID").jCombo("{{ URL::to('prescriptionmedicine/comboselect?filter=tb_prescription:PrescriptionID:PrescriptionID') }}",
        {  selected_value : '{{ $row["PrescriptionID"] }}' });
         

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop