<div class="ibox float-e-margins">
   
    <div class="sbox-content mailbox-content">
        <div class="file-manager">
            <a class="btn btn-block btn-primary compose-mail" href="{{ url('mailbox/update') }}" onclick="return false;" >Compose Mail</a>
            <div class="space-25"></div>
            <h5>Folders</h5>
            <ul class="folder-list m-b-md main-link" style="padding: 0">
                <li><a href="#" code="inbox"> <i class="fa fa-inbox "></i> Inbox <span class="label label-warning pull-right">{{ count($totalInbox)}}</span> </a></li>
                <li><a href="{{ url('mailbox/index/sent') }}" code="sent"> <i class="fa fa-envelope-o"></i> Sent Mail</a></li>
               
                <li><a href="{{ url('mailbox/index/draft') }}" code="draft"> <i class="fa fa-file-text-o"></i> Drafts <span class="label label-danger pull-right">{{ count($totalDraft)}}</span></a></li>
                <li><a href="{{ url('mailbox/index/trash') }}" code="trash"> <i class="fa fa-trash-o"></i> Trash</a></li>
            </ul>
           
            <h5>Categories</h5>
            <ul class="category-list" style="padding: 0">
                <li><a href="#" code="work"> <i class="fa fa-circle text-navy"></i> Work </a></li>
                <li><a href="#" code="documents"> <i class="fa fa-circle text-danger"></i> Documents</a></li>
                <li><a href="#" code="social"> <i class="fa fa-circle text-primary"></i> Social</a></li>
                <li><a href="#" code="advertising"> <i class="fa fa-circle text-info"></i> Advertising</a></li>
                <li><a href="#" code="clients"> <i class="fa fa-circle text-warning"></i> Clients</a></li>
            </ul>
            <!--
            <h5 class="tag-title">Labels</h5>
            <ul class="tag-list" style="padding: 0">
                <li><a href=""><i class="fa fa-tag"></i> Family</a></li>
                <li><a href=""><i class="fa fa-tag"></i> Work</a></li>
                <li><a href=""><i class="fa fa-tag"></i> Home</a></li>
                <li><a href=""><i class="fa fa-tag"></i> Children</a></li>
                <li><a href=""><i class="fa fa-tag"></i> Holidays</a></li>
                <li><a href=""><i class="fa fa-tag"></i> Music</a></li>
                <li><a href=""><i class="fa fa-tag"></i> Photography</a></li>
                <li><a href=""><i class="fa fa-tag"></i> Film</a></li>
            </ul> -->
            <div class="clearfix"></div>
        </div>
    </div>
</div>

  <script type="text/javascript">
  $(function(){



    $('ul.main-link li a').click(function(){
        var code = $(this).attr('code');
        $('.ajaxLoading').show();
            reloadData( '#mailbox' ,'mailbox/data?search=Status:equal:'+code); 
        return false;

    })

    $('ul.category-list li a').click(function(){
        var code = $(this).attr('code');
        $('.ajaxLoading').show();
            reloadData( '#mailbox' ,'mailbox/data?search=Category:equal:'+code); 
        return false;

    })    

    

  })
  </script>
