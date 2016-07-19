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

		 {!! Form::open(array('url'=>'report/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Report</legend>
									
				  <div class="form-group hidethis " style="display:none;"> 
					<label for="ReportID" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('ReportID', (isset($fields['ReportID']['language'])? $fields['ReportID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('ReportID', $row['ReportID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Patient" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Patient', (isset($fields['PatientID']['language'])? $fields['PatientID']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <select name='PatientID' rows='5' id='PatientID' class='select2 '   ></select> 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Title" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Title', (isset($fields['Title']['language'])? $fields['Title']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  {!! Form::text('Title', $row['Title'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="File" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('File', (isset($fields['File']['language'])? $fields['File']['language'] : array())) !!}	
					</label>
					<div class="col-md-6">
					  <input  type='file' name='File' id='File' accept="application/msword,application/pdf" @if($row['File'] =='') class='required' @endif style='width:150px !important;'  />
					 	<div >

						{!! SiteHelpers::showUploadedFile($row['File'],'/uploads/Reports/') !!}
						
						</div>					
					 
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
					<button type="button" onclick="location.href='{{ URL::to('report?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
</div>			 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		
        $("#PatientID").jCombo("{{ URL::to('report/comboselect?filter=tb_patient:PatientID:first_name|last_name&limit=where:entry_by:=:'.\Auth::user()->id) }}",
        {  selected_value : '{{ $row["PatientID"] }}' });
         

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop