
			<div class="mail-box-header">
                <div class="pull-right tooltip-demo">
                    <a href="#" class="btn btn-white btn-sm draft-button" data-toggle="tooltip" data-placement="top" title="Move to draft folder"><i class="fa fa-pencil"></i> Draft</a>
                    <a href="#" class="btn btn-danger btn-sm discard-button" data-toggle="tooltip" data-placement="top" title="Discard email"><i class="fa fa-times"></i> Discard</a>
                </div>
                <h2>
                    Compose mail
                </h2>
            </div>
                <div class="mail-box">

                {!! Form::open(array('url'=>'mailbox/save/'.SiteHelpers::encryptID($row['Id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'mailboxFormAjax')) !!}
                <div class="mail-body">

                
				  
				  	@if($act =='reply')
				  		{!! Form::hidden('Id', null ,array('class'=>'form-control', 'placeholder'=>'',   )) !!}
				  	@else					
				  		{!! Form::hidden('Id', $row['Id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
				  	@endif 

				  	{!! Form::hidden('SenderID',Session::get('uid'),array('class'=>'form-control', 'placeholder'=>'',   )) !!}                 
                        <div class="form-group">
							<label class="col-sm-2 control-label">
								{!! SiteHelpers::activeLang('ReceiverID', (isset($fields['ReceiverID']['language'])? $fields['ReceiverID']['language'] : array())) !!} :
							</label>
                            <div class="col-sm-10">
								{{--<select id="ReceiverID" name="ReceiverID" class="select2"  required></select>--}}
								<select id="ReceiverID" name="ReceiverID" class="select2" required>
									<option value="">-- Select --</option>
									@foreach($receivers as $receiver)
										<option value="{{ $receiver->id }}">{{ $receiver->first_name }} {{ $receiver->last_name }} [{{ $receiver->email }}]</option>
									@endforeach
								</select>
							</div>
                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">{!! SiteHelpers::activeLang('Subject', (isset($fields['Subject']['language'])? $fields['Subject']['language'] : array())) !!} :</label>

                            <div class="col-sm-10"> {!! Form::text('Subject', $row['Subject'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'   )) !!} </div>
                        </div>
               
                       

                	</div>

                    <div class="mail-text h-200" >

                        <textarea class="editor" name="Message">{{ htmlentities($row['Message'])}}</textarea>
						<div class="clearfix"></div>
                    </div>
                    <div class="mail-body text-right tooltip-demo">

                        <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" data-placement="top" title="Send" type="submit" name="send"><i class="fa fa-reply"></i> Send</button>
                        <button  class="btn btn-white btn-sm discard-button" data-toggle="tooltip" data-placement="top" title="Discard email"><i class="fa fa-times"></i> Discard</button>
                        <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to draft folder" type="submit" name="draft" onclick="$('#mailboxFormAjax').attr('action','{{ url('mailbox/save?act=draft')}}')"><i class="fa fa-pencil"></i> Draft</button>
                    </div>
                    <div class="clearfix"></div>

                     {!! Form::close() !!}
                    </div>

	
</div>	
			 
<script type="text/javascript">
$(document).ready(function() { 
	 
	 $('.discard-button').click(function(){
	 	$('.mail-content').show();
	 	$('.dynamic-content').html('');

	 })

	{{--$("#ReceiverID").jCombo("{{ URL::to('mailbox/comboselect?filter=tb_users:id:first_name|last_name') }}",--}}
	{{--{  selected_value : '{{ $row["ReceiverID"]}}' });--}}

	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	$(".select2").select2({ width:"98%"});	
	$('.date').datepicker({format:'yyyy-mm-dd',autoClose:true})
	$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'}); 
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
	});			
	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});			
	var form = $('#mailboxFormAjax'); 
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
		ajaxViewClose('#{{ $pageModule }}');
		ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
		notyMessage(data.message);	
		$('#sximo-modal').modal('hide');	
	} else {
		notyMessageError(data.message);	
		$('.ajaxLoading').hide();
		return false;
	}	
}			 

</script>		 