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
		<li><a href="{{ URL::to('medicine?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'medicine/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Medicine</legend>
									
				  <div class="form-group hidethis " style="display:none;"> 
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
					<label for="Manufacturer" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Manufacturer', (isset($fields['Manufacturer']['language'])? $fields['Manufacturer']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Manufacturer', $row['Manufacturer'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Power" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Power', (isset($fields['Power']['language'])? $fields['Power']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Power', $row['Power'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Price" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Price', (isset($fields['Price']['language'])? $fields['Price']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Price', $row['Price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Description" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Description', (isset($fields['Description']['language'])? $fields['Description']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Description', $row['Description'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="MfgDate" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('MfgDate', (isset($fields['MfgDate']['language'])? $fields['MfgDate']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('MfgDate', $row['MfgDate'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="ExpDate" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ExpDate', (isset($fields['ExpDate']['language'])? $fields['ExpDate']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('ExpDate', $row['ExpDate'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="IsAvailable" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('IsAvailable', (isset($fields['IsAvailable']['language'])? $fields['IsAvailable']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('IsAvailable', $row['IsAvailable'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Quantity" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Quantity', (isset($fields['Quantity']['language'])? $fields['Quantity']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Quantity', $row['Quantity'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
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
					<button type="button" onclick="location.href='{{ URL::to('medicine?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
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