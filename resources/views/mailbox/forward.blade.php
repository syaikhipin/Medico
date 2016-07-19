<div class="page-content-wrapper">  
 {!! Form::open(array('url'=>'mailbox/forward/'.$row->Id, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'mailboxForward')) !!}
 	<div class="form-group">
 		<label> Select Email Address</label>
		 	<select class="form-control" id="ReceiverID" name="ReceiverID" required>
		 		
		 	</select>
	</div>
	<div class="form-group">
	<button class="btn btn-primary" type="submit"><i class="fa fa-arrow-right"></i> Send </button>
	</div>	 	
 {!! Form::close() !!}
 </div>

<script type="text/javascript">
	
$(document).ready(function() { 

	$("#ReceiverID").jCombo("{{ URL::to('mailbox/comboselect?filter=tb_users:id:first_name|last_name|email') }}",
	{  selected_value : '' });

	var form = $('#mailboxForward'); 
	form.parsley();
	form.submit(function(){
		
		if(form.parsley('isValid') == true){			
			var options = { 
				dataType:      'json', 
				beforeSubmit :  showRequest,
				success:       showResponse  
			}  
			$(this).ajaxSubmit(options); 
			return false;
						
		} else {
			return false;
		}		
	
	});

});	

function showRequest()
{
	$('.ajaxLoading').show();		
}  
function showResponse(data)  {		
	
	if(data.status == 'success')
	{
		//ajaxViewClose('#{{ $pageModule }}');
	//	ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
	//	notyMessage(data.message);	
		$('.ajaxLoading').hide();
		$('#sximo-modal').modal('hide');	
	} else {
		//notyMessageError(data.message);	
		$('.ajaxLoading').hide();
		return false;
	}	
}
</script>