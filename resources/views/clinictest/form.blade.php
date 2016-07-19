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
		<li><a href="{{ URL::to('clinictest?return='.$return) }}">{{ $pageTitle }}</a></li>
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

		 {!! Form::open(array('url'=>'clinictest/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> ClinicTest</legend>
									
				  <div class="form-group  " > 
					<label for="ClinicID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ClinicID', (isset($fields['ClinicID']['language'])? $fields['ClinicID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('ClinicID', $row['ClinicID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Name" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Name', (isset($fields['Name']['language'])? $fields['Name']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Name', $row['Name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Address" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Address', (isset($fields['Address']['language'])? $fields['Address']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Address', $row['Address'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Latitude" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Latitude', (isset($fields['Latitude']['language'])? $fields['Latitude']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Latitude', $row['Latitude'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Longitude" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Longitude', (isset($fields['Longitude']['language'])? $fields['Longitude']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Longitude', $row['Longitude'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Description" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Description', (isset($fields['Description']['language'])? $fields['Description']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <textarea name='Description' rows='5' id='Description' class='form-control '  
				           >{{ $row['Description'] }}</textarea> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="City" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('City', (isset($fields['City']['language'])? $fields['City']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('City', $row['City'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Speciality" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Speciality', (isset($fields['Speciality']['language'])? $fields['Speciality']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Speciality', $row['Speciality'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Photo" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Photo', (isset($fields['Photo']['language'])? $fields['Photo']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <input  type='file' name='Photo' id='Photo' @if($row['Photo'] =='') class='required' @endif style='width:150px !important;'  />
					 	<div >
						{!! SiteHelpers::showUploadedFile($row['Photo'],'/clinic/') !!}
						</div>					
					 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="IsSponsored" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('IsSponsored', (isset($fields['isSponsored']['language'])? $fields['isSponsored']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('isSponsored', $row['isSponsored'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Recommendation" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Recommendation', (isset($fields['Recommendation']['language'])? $fields['Recommendation']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Recommendation', $row['Recommendation'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Gallary" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Gallary', (isset($fields['Gallary']['language'])? $fields['Gallary']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  
					<a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles('Gallary')"><i class="fa fa-plus"></i></a>
					<div class="GallaryUpl">	
					 	<input  type='file' name='Gallary[]'  />			
					</div>
					<ul class="uploadedLists " >
					<?php $cr= 0; 
					$row['Gallary'] = explode(",",$row['Gallary']);
					?>
					@foreach($row['Gallary'] as $files)
						@if(file_exists('./clinic/gallary/'.$files) && $files !='')
						<li id="cr-<?php echo $cr;?>" class="">							
							<a href="{{ url('/clinic/gallary/'.$files) }}" target="_blank" >{{ $files }}</a>
							<span class="pull-right" rel="cr-<?php echo $cr;?>" onclick=" $(this).parent().remove();"><i class="fa fa-trash-o  btn btn-xs btn-danger"></i></span>
							<input type="hidden" name="currGallary[]" value="{{ $files }}"/>
							<?php ++$cr;?>
						</li>
						@endif
					
					@endforeach
					</ul>
					 
					 </div> 
					 <div class="col-md-2">
					 	<a href="#" data-toggle="tooltip" placement="left" class="tips" title="upload clinic pictures"><i class="icon-question2"></i></a>
					 </div>
				  </div> </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
					<button type="button" onclick="location.href='{{ URL::to('clinictest?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
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