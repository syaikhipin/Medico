
		 {!! Form::open(array('url'=>'calendar/save?return='.$return, 'class'=>'form-vertical','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

				
									  {!! Form::hidden('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
									
					
								  <div class="form-group  " >
									<label for="Title" class=" control-label  text-left"> Title </label>
									
									  {!! Form::text('title', $row['title'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
									
								  </div> 	
								  <div class="form-group  " >
									<label for="Start" class=" control-label  text-left"> From - To  </label>
									<div class="row">
										<div class="col-md-6">
											<div class="input-group m-b">
											  {!! Form::text('start', $row['start'],array('class'=>'form-control datetime', 'placeholder'=>'', 'required'=>'true'   )) !!}
											  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
										 </div> 
										 <div class="col-md-6">
										 	<div class="input-group m-b">
										 		{!!  Form::text('end', $row['end'],array('class'=>'form-control datetime', 'placeholder'=>'', 'required'=>'true'   )) !!}
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
										 </div>
									</div>	 
								  </div>								  				
								  <div class="form-group  form-horizontal" >
									<label for="Description" class=" control-label "> Description </label>
									
									 
									  <textarea name="description" rows="15" class="form-control markItUp">{{ $row['description'] }}</textarea>
									 
								  </div> 
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="text-right">&nbsp;</label>
					
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
					<button type="button" onclick=" $('#sximo-modal').modal('hide'); " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
					@if($row['id'] != '')
					<button type="button" onclick="SximoDelete();" id="submit" class="btn btn-danger ">  Delete </button>
					@endif				
			
				  </div> 
		 
		 {!! Form::close() !!}
		 {!! Form::open(array('url'=>'calendar/delete', 'class'=>'form-horizontal' ,'ID' =>'SximoTable' )) !!}
	 <input type="checkbox" style="display:none" checked="checked" class="ids"  name="id[]" value="{{ $row['id'] }}" />
	{!! Form::close() !!}	

   <script type="text/javascript">
	$(document).ready(function() { 
			$('.date').datepicker({format:'yyyy-mm-dd',autoClose:true})	;
		$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});

		
	});
	</script>		 
