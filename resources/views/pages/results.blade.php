<style>
    .ui-slider-range { background: grey; }
    #radioBtn .active {
        color: #3276b1;
        background-color: #fff;
    }
    #radioBtn .btn{
        background-color:lemonchiffon;
        font-size: 15px;
        padding: 4px 7px;
    }
    .media{
        box-shadow: 0px 5px 10px 0px;
        padding: 15px;
        margin-top: 20px;
        background-color: rgba(245,245,245,0.6);
    }
    ul.lists,ul.locations{
        padding: 0px !important;
    }
    ul{
        list-style-type: none;
    }
    .btn span.fa-check {
        opacity: 0;
    }
    .btn.active span.fa-check {
        opacity: 1;
    }
        .desc-content{
            height: 1.5em;
            padding: 2px; /* adjust to taste */
            overflow: hidden
        }
    .photos{
        margin: 3px 0px;
    }

</style>

<div class="wrapper-header ">
    <div class=" container">
        <div class="col-sm-6 col-xs-6">
            <div class="page-title">
                <h3>Search Results </h3>
                <small>{{ isset($detail['count']) ? $detail['count'] : 'No' }} result  were found</small>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6 ">
            <ul class="breadcrumb pull-right">
                <li><a href="{{ URL::to('') }}">Home</a></li>
                <li class="active">Results</li>
            </ul>
        </div>

    </div>
</div>

<div class="container-fluid" style="min-height: 350px;background-color: transparent !important;">
    <form action="{{ url('results') }}" method="get" >
        <div class="row">
            {{--<div class="col-md-10 text-right">Sort By--}}

            {{--<select name="sort" data-placeholder="Sort" class="input-sm auto_submit_form "  >--}}
            {{--<option value=""> Sort </option>--}}
            {{--<option value="Name">Name</option>--}}
            {{--<option value="dist">Distance</option>--}}
            {{--<option value="Fee">Fees</option>--}}
            {{--<option value="Recommendation">Recommendation</option>--}}
            {{--</select>---}}
            {{--</div>--}}
        </div>
        <div class="row">
            <hr/>
            <div class="col-md-12">

                <div class="col-md-2">
                    <span class="" data-toggle="collapse" data-target="#fee"><h5>Consultation Fee <span class="caret"></span></h5></span>
                    <div id="fee">
                        <input type="hidden" name="min" id="minfee" class="auto_submit_form" >
                        <input type="hidden" name="max" id="maxfee" class="auto_submit_form">
                        <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
                        <div id="consultFee"></div>
                    </div>
                    <hr>




                    <span class="" data-toggle="collapse" data-target="#locations"><h5>Locations <span class="caret"></span></h5></span>
                    <ul class="locations" id="locations">
                    @foreach($detail['locations'] as $loc)
                    <label class="checkbox"><input type="checkbox" name="area[]" value="{{ $loc }}">{{ $loc }}</label>
                    @endforeach
                    </ul>
                    </li>
                    </ul>

                    <span class="" data-toggle="collapse" data-target="#availabilty"><h5>Availability <span class="caret"></span></h5></span>
                    <div class="btn-group" data-toggle="buttons" id="availabilty">
                        <div class="input-group">
                            <div id="radioBtn" class="btn-group">
                                <a class="btn btn-xs" data-toggle="day" data-title="Mon">M</a>
                                <a class="btn btn-xs" data-toggle="day" data-title="Tue">T</a>
                                <a class="btn btn-xs" data-toggle="day" data-title="Wed">W</a>
                                <a class="btn btn-xs" data-toggle="day" data-title="Thu">T</a>
                                <a class="btn btn-xs " data-toggle="day" data-title="Fri">F</a>
                                <a class="btn btn-xs" data-toggle="day" data-title="Sat">S</a>
                                <a class="btn btn-xs" data-toggle="day" data-title="Sun">S</a>
                            </div>
                            <input type="hidden" name="day" id="day" class="auto_submit_form" value="{{ $detail['request']['day'] }}">
                        </div>
                    </div>

                </div>
                <div class="col-md-8" style="border-left:1px solid #ECF0F1;">

                    <input type="hidden" name="loc" value="{{ $detail['request']['loc'] }}">
                    <input type="hidden" name="q" value="{{ $detail['request']['q'] }}">
                    <input type="hidden" name="scope" value="{{ $detail['request']['scope'] }}">
                    @if(isset($detail['clinics']))
                        <div class="col-md-12">
                        @foreach($detail['clinics'] as $clinic)
                                <div class="media">
                                    <div class="media-left">
                                        {!! SiteHelpers::showUploadedFile($clinic->Photo,'/uploads/clinics/',100) !!}
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading"><a href="{{ asset('/results/clinic/'.$clinic->ClinicID) }}">{!! $clinic->Name !!} </a><small> {!! $clinic->Address !!} , {!! $clinic->City !!}</small></h4>
                                        <span class="speciality">{!! $clinic->Speciality !!} | <i class="fa fa-thumbs-up"></i> {{  $clinic->Recommendation }}</span>
                                        <div class="photos">
                                            <?php
                                            $clinic->Gallary = $clinic->Gallary!="" ? explode(",",$clinic->Gallary) : array()?>
                                            @foreach($clinic->Gallary as $photo)
                                                {!! \SiteHelpers::showUploadedFile($photo,'/uploads/clinics/gallery/',60,'square') !!}
                                            @endforeach
                                        </div>
                                        <p class="desc-content">{!! $clinic->Description !!}</p>
                                        {{--<div class="book">Book Now</a> </div>--}}
                                    </div>
                                    @if($clinic->isSponsored)
                                        <div class="pull-right"><span class="label label-default" style="font-size: 12px"><i class="fa fa-refresh"> Sponsored</i> </span> </div>
                                    @endif
                                </div>
                        @endforeach
                        </div>
                        <input type="hidden" name="type" value="Clinic">

                        {!! $detail['clinics']->appends($detail['request']->all() )->render() !!}
                    @elseif(isset($detail['doctors']))
                        <div class="col-md-12">
                            @foreach($detail['doctors'] as $doctor)

                                <div class="media">
                                    <div class="media-left">
                                        {{--<img class="media-object img-circle profile-img" src="http://s3.amazonaws.com/37assets/svn/765-default-avatar.png">--}}
                                        {!! SiteHelpers::showUploadedFile(App\Models\Doctor::getDetail($doctor-> UserID)->avatar,'/uploads/users/',100) !!}
                                    </div>
                                    <div class="media-body">
                                        <p></p>
                                        <h4 class="media-heading"><a href="{{ asset('/results/doctor/'.$doctor->DoctorID) }}">{!! SiteHelpers::gridDisplayView($doctor->UserID,'UserID','1:tb_users:id:first_name|last_name') !!} </a> </h4>
                                        <div class="speciality">{!! $doctor->Degree !!} | {!! $doctor->Expertization !!}</div>
                                        <div class="meta">{!! $doctor->Experience !!} Experience | Fees {!! $doctor->Fee !!} | <i class="fa fa-thumbs-up"></i> {{ $doctor->Recommendation }} </div>
                                        {{--<div class="book"><a href="{{ asset('/results/doctor/'.$doctor->DoctorID) }}">Book Now</a> </div>--}}
                                    </div>
                                    @if($doctor->isSponsored)
                                        <div class="pull-right"><span class="label label-default" style="font-size: 12px"><i class="fa fa-refresh"> Sponsored</i> </span> </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="type" value="Doctor">
                        {!! $detail['doctors']->appends($detail['request']->all() )->render() !!}
                    @else
                        <h3>Oops, We've hit a snag</h3>
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">No Results Found!!</h3>
                            </div>
                            <div class="panel-body">
                                <p>Go Back and Try other Search !</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-2">
                    {{--Promotions,social links--}}
                </div>
            </div>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css">
<script>

    $(document).ready(function(){

        var slider = document.getElementById('consultFee');

        $(slider).slider({
            range: true,
            min: 100,
            max: 1500,
            step :10,
            values: [ {{ $detail['request']['min'] or '100' }}, {{ $detail['request']['max'] or '500' }} ],
            slide: function( event, ui ) {
                $( "#amount" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
                $('#minfee').val(ui.values[ 0 ]);
                $('#maxfee').val(ui.values[ 1 ]);
                $("form").submit();

            }
        });
        $( "#amount" ).val( "" + $( "#consultFee" ).slider( "values", 0 ) +
                " - " + $( "#consultFee" ).slider( "values", 1 ) );
        $('#minfee').val($( "#consultFee" ).slider( "values", 0 ));
        $('#maxfee').val($( "#consultFee" ).slider( "values", 1 ));


        var day = $('#day').val().split(',');
        $.each(day, function(index, value){
            $('#radioBtn a[ data-title="' + value + '"').toggleClass('active');
        });

        $('#radioBtn a').on('click', function(){
            var sel = $(this).data('title');
            var tog = $(this).data('toggle');

            var found = jQuery.inArray(sel, day);
            if (found >= 0) {
                // Element was found, remove it.
                day.splice(found, 1);
            } else {
                // Element was not found, add it.
                day.push(sel);
            }

            $('#'+tog).prop('value',day);


            // $('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').toggleClass('Active');
            setTimeout(function(){
                $("form").submit();
            },2000);

        })

        $('.lists > li a').click(function() {
            $(this).parent().find('ul').toggle();
        });

        $('input[type=checkbox]').on('change', function() {
            setTimeout(function(){
                $("form").submit();
            },2000);

        });


        $(".auto_submit_form").change(function() {
            $("form").submit();
        });

        $('')

        $(".desc-content").click(function(){
            this.style.height = 'auto';
        });
    });
</script>