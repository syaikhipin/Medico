<?php use \App\Http\Controllers\MailboxController; ?>
<div class="mail-box-header">
    <div class="pull-right tooltip-demo">
        @if($act == 'inbox')
        <a title="" data-placement="top" data-toggle="tooltip" class="btn btn-white btn-sm" href="mail_compose.html" data-original-title="Reply"><i class="fa fa-reply"></i> Reply</a>
        <a title="Print email" data-placement="top" data-toggle="tooltip" class="btn btn-white btn-sm" href="#"><i class="fa fa-print"></i> </a>
        <a title="" data-placement="top" data-toggle="tooltip" class="btn btn-white btn-sm" href="mailbox.html" data-original-title="Move to trash"><i class="fa fa-trash-o"></i> </a>
        @endif
    </div>
    <h2>
        View Message
    </h2>
    <div class="mail-tools tooltip-demo m-t-md">


        <h3>
            <span class="font-noraml">Subject: </span> {!! $row->Subject !!}
        </h3>
        <h5>
            <span class="pull-right font-noraml">{{ $row->SentDate }} </span>
             @if($act == 'sent')
            <span class="font-noraml">To: </span>  {{ MailboxController::Users($row->ReceiverID) }}
            @else
            <span class="font-noraml">From: </span> {{ $row->Sender }}
            @endif 
    </div>
</div>


            <div class="mail-box">
            	<div class="mail-body"> 
               		<div style="min-height:300px;" >
               		{!! $row->Message !!}

               		</div>
					<div class="clearfix"></div>
                </div>


                <div class="mail-body text-right tooltip-demo">
                @if($act == 'inbox')
                    <a href="{{ url('mailbox/update/'.$row->Id.'?act=reply')}}" class="btn btn-sm btn-white reply-mail" onclick="return false;"><i class="fa fa-reply"></i> Reply</a>
                    <a href="mail_compose.html" class="btn btn-sm btn-white" onclick="SximoModal('{{ url('mailbox/forward/'.$row->Id)}}','Forward Email To :'); return false;"><i class="fa fa-arrow-right"></i> Forward</a>
                    <button class="btn btn-sm btn-white" data-original-title="Print" type="button" data-toggle="tooltip" data-placement="top" title=""><i class="fa fa-print"></i> Print</button>
                    <button class="btn btn-sm btn-white" data-original-title="Trash" data-toggle="tooltip" data-placement="top" title=""><i class="fa fa-trash-o"></i></button>
                @endif    
                    <div class="clearfix"></div>
            </div>



<script>
$(document).ready(function(){

    $('.reply-mail').click(function(){
        $('.mail-content').hide(); 
        $('.ajaxLoading').show();
        $.get( $(this).attr('href') , function(callback){
        
            $('.dynamic-content').html(callback);
            $('.ajaxLoading').hide();
            return false;

        })
    })  
 
});
</script>	