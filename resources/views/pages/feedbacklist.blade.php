
<div class="col-md-6" id="feedback">
    <h4>
        <span class="fa fa-comments-o"></span>
        Feedback
    </h4>
</div>
<div class="col-md-12">
<ul class="list-group">
    @foreach($feedback as $feed)
        <li class="list-group-item">
            <div class="row">
                <div class="col-xs-12">
                <div class="col-xs-2 col-md-1">
                    {!! SiteHelpers::showUploadedFile(SiteHelpers::gridDisplayView($feed->FromUserID,'UserID','1:tb_users:id:avatar'),'/uploads/users/',50) !!}
                </div>
                <div class="col-xs-10 col-md-11">
                    <div>
                        {!! SiteHelpers::gridDisplayView($feed->FromUserID,'UserID','1:tb_users:id:first_name|last_name')  !!}
                        <span style="float: right">{!! \Carbon\Carbon::parse($feed->created_at)->diffForHumans() !!}</span>
                    </div>
                    <div class="comment-text">
                        {{ $feed->Feedback }}
                    </div>

                </div>
                </div>
            </div>
        </li>
    @endforeach
</ul>
    <a id="show-more" class="btn btn-xs  btn-block btn-primary" role="button"><span class="fa fa-refresh"></span> More</a>
    <a id="show-less" class="btn btn-xs btn-block btn-primary" role="button"><span class="fa fa-refresh"></span> less</a>

</div>


