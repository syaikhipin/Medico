<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<?php use \App\Http\Controllers\MailboxController; ?>

<div class="page-content-wrapper m-t" style="padding-bottom:30px;">  
<div class="row">
            <div class="col-md-3">
            @include('mailbox.sidebar')
            </div>
            <div class="col-md-9 ">

                <div class="dynamic-content">

                </div>
                <div class="mail-content">

            <div class="mail-box-header ">

                <form method="get" action="index.html" class="pull-right mail-search">
                    <div class="input-group">
                        <input type="text" class="form-control input-sm" name="search" placeholder="Search email">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-primary">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
                <h2>
                    {{ $mail_title }} ( <b>{{ $mail_total }}</b> )
                </h2>
                <div class="mail-tools tooltip-demo m-t-md">
                    <div class="btn-group pull-right">
                         {!! $pagination->appends($pager)->render() !!}
                    </div>
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="Refresh inbox" onclick="reloadData('#{{ $pageModule }}','mailbox/data?return={{ $return }}')"><i class="fa fa-refresh"></i> Refresh</button>
                    @if($mail_title =='Inbox')
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Mark as read" onclick="AjaxAction('#mailbox','{{ url('mailbox')}}','Read')"><i class="fa fa-eye"></i> </button>
                    @endif
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to trash" onclick="AjaxAction('#mailbox','{{ url('mailbox')}}','Trash')"><i class="fa fa-trash-o"></i> </button>

                </div>
            </div>

                <div class="mail-box" style="min-height:300px !important" id="mailboxTable">

                <table class="table table-hover table-mail">
                <tbody>
                 @foreach ($rowData as $row)
                  
                <tr @if($row->IsView =='0') class="unread" @else class="read" @endif >
                    <td class="check-mail">
                        <input type="checkbox"  name="ids[]" value="<?php echo $row->Id ;?>">
                    </td>
                    <td class="mail-contact">
                     <?php
                     if($mail_title == 'Draft')
                     {
                         $url = url('mailbox/update/'.$row->Id.'?act='.$mail_title);
                     }  else {
                         $url = url('mailbox/show/'.$row->Id.'?act='.$mail_title);  
                     }

                     ?>   

                    <a href="{{ $url }}" class="mail-detail" onclick="return false;">
                     
                     @if($mail_title =='Sent' or $mail_title =='Draft')
                        <b>To: </b> {{ MailboxController::Users($row->ReceiverID) }}
                     @else
                        {{ $row->Sender }}
                     @endif


                     </a></td>
                    <td class="mail-subject">
                    <a href="{{ $url }}"  class="mail-detail" onclick="return false;">   {{ substr(strip_tags($row->Message),0,75) }}</a></td>
                    <td class="" width="10"><i class="fa fa-paperclip"></i></td>
                    <td class="text-right mail-date" width="100">{{ date('F , d',strtotime($row->SentDate))}} </td>
                </tr>
                   
                @endforeach
                
                </tbody>
                </table>


                </div>

            <div class="mail-box-header" style="margin-top:0 !important">


                <div class="mail-tools tooltip-demo m-t-md">
                    <div class="btn-group pull-right">
                      {!! $pagination->appends($pager)->render() !!}
                    </div>
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="Refresh inbox"><i class="fa fa-refresh"></i> Refresh</button>
                     @if($mail_title =='Inbox')
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Mark as read"><i class="fa fa-eye"></i> </button>
                    @endif
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to trash"><i class="fa fa-trash-o"></i> </button>

                </div>
            </div>  
            </div>              
    </div>            
  </div></div>

  <script type="text/javascript">
  $(function(){
    $('input[type="checkbox"],input[type="radio"]').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    }); 

    $('.compose-mail, .mail-detail').click(function(){
        $('.mail-content').hide(); 
        $('.ajaxLoading').show();
        $.get( $(this).attr('href') , function(callback){
        
            $('.dynamic-content').html(callback);
            $('.ajaxLoading').hide();
            return false;

        })
    })  

    $('.pagination li a').click(function() {
        var url = $(this).attr('href');
        reloadData('#{{ $pageModule }}',url);       
        return false ;
    })

  })

function AjaxAction( id, url ,task)
{
   if(task =='Read')
   {
        var text = 'Mark As Read ?';
   } else {
        var text = 'Move To Trash ?';
   } 

    var datas = $( id +'Table :input').serialize();
    if(confirm(text)) {
        $.post( url+'/trash?act='+task ,datas,function( data ) {
            if(data.status =='success')
            {
                notyMessage(data.message);  
                ajaxFilter( id ,url+'/data' );
            } else {
                notyMessageError(data.message); 
            }               
        }); 
        
    }   
}

  </script>
       
<style type="text/css">
    
    .pagination { margin: 0 !important;}
</style>
