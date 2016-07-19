<style>
    .desc-content{
        height: 1.5em;
        padding: 2px; /* adjust to taste */
        overflow: hidden
    }
    .photos{
        margin: 3px 0px;
    }

    #radioBtn .active {
        color: #3276b1;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.5) !important;
    }

    .ui-autocomplete
    {
        width: 240px !important;
        border: 2px solid #E9E9E9 !important;
        border-top: 0px;
    }

    .ui-slider-range{
        background: none !important;
        background-color: #007264 !important; ;
    }
    #radioBtn .btn{
        font-size: 15px;
        padding: 7px 10px;
    }
</style>

<div class="container">
    <div class="row">
        {{--<div class="document-title">--}}
        <ul class="breadcrumb">
            <li><a href="{{ URL::to('') }}">Home</a></li>
            <li class="active">Results</li>
        </ul>
        {{--</div><!-- /.document-title -->--}}
    </div>


    <div class="col-sm-8 col-lg-9">
        <div class="content">
            <form class="filter" method="get" action="{{URL::to('result')}}">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <select id="type" class="form-control" name="type" title="Please Select" data-width="250px"
                                    data-live-search="false">
                                <option value="Clinic" @if($detail['request']['type']=='Clinic') selected="selected" @endif>Clinic</option>
                                <option value="Doctor" @if($detail['request']['type']=='Doctor') selected="selected" @endif>Doctor</option>
                            </select>
                        </div><!-- /.form-group -->
                    </div><!-- /.col-* -->

                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <input id="loc" name="loc" class="form-control" type="text" placeholder="Location"
                                   autocomplete="off" value="{{ $detail['request']['loc']  }}">
                        </div><!-- /.form-group -->
                    </div><!-- /.col-* -->

                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">

                            <input id="q" name="q" class="form-control ui-autocomplete-input" placeholder="Keyword"
                                   type="text" autocomplete="off" value="{{ $detail['request']['q'] }}"/>
                            <input id="scope" type="hidden" name="scope" value="{{ $detail['request']['scope'] }}">
                        </div><!-- /.form-group -->
                    </div><!-- /.col-* -->
                </div><!-- /.row -->

                <hr>

                <div class="row">
                    {{--<div class="col-sm-8">--}}
                    {{--/.filter-actions -->--}}
                    {{--</div><!-- /.col-* -->--}}

                    <div class="col-sm-4 pull-right">
                        <button type="submit" class="btn btn-primary">Redefine Search Result</button>
                    </div><!-- /.col-* -->
                </div><!-- /.row -->
            </form>


            <div class="cards-row">
                @if(isset($detail['clinics']) && $detail['count']>0 )

                    @foreach($detail['clinics'] as $clinic)
                        <div class="card-row">
                            <div class="card-row-inner">
                                <div class="card-row-image" data-background-image="{{ url('/uploads/clinics/'.$clinic->Photo) }}">
                                    @if($clinic->isSponsored)
                                        <div class="card-row-label">Sponsored</div><!-- /.card-row-label -->
                                    @endif
                                    {{--<div class="card-row-price">$100 / night</div><!-- -->--}}
                                </div><!-- /.card-row-image -->

                                <div class="card-row-body">
                                    <h2 class="card-row-title"><a href="{{ url('/result/clinic/'.$clinic->ClinicID) }}">{!! $clinic->Name !!} </a></h2>
                                    <small> {!! $clinic->Address !!} , {!! $clinic->City !!}</small>
                                    <div class="card-row-content">

                                        {{--<p class="desc-content">{!! $clinic->Description !!}</p>--}}
                                        <div class="photos">
                                            <?php
                                            $clinic->Gallary = $clinic->Gallary!="" ? explode(",",$clinic->Gallary) : array()?>
                                            @foreach($clinic->Gallary as $photo)
                                                {!! \SiteHelpers::showUploadedFile($photo,'/uploads/clinics/gallery/',60,'square') !!}
                                            @endforeach
                                        </div>
                                    </div><!-- /.card-row-content -->
                                </div><!-- /.card-row-body -->

                                <div class="card-row-properties">
                                    <dl>

                                        <dd></dd><dt> <span class="fa fa-thumbs-up"></span> {{ $clinic->Recommendation }}</dt>
                                        <dd></dd>
                                        <dt><span class="speciality">{!! $clinic->Speciality !!} </span></dt>
                                        {{--<dd>Rating</dd>--}}
                                        {{--<dt>--}}
                                        {{--<div class="card-row-rating">--}}
                                        {{--<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>--}}
                                        {{--</div><!-- /.card-row-rating -->--}}
                                        {{--</dt>--}}
                                    </dl>
                                </div><!-- /.card-row-properties -->
                            </div><!-- /.card-row-inner -->
                        </div><!-- /.card-row -->
                    @endforeach
                    <input type="hidden" name="type" value="Clinic">
                @elseif(isset($detail['doctors']) &&  $detail['count']>0)
                    @foreach($detail['doctors'] as $doctor)
                        <div class="card-row">
                            <div class="card-row-inner">
                                <div class="card-row-image" data-background-image="{{ url('uploads/users/'.App\Models\Doctor::getDetail($doctor-> UserID)->avatar) }}">
                                    @if($doctor->isSponsored)
                                        <div class="card-row-label">Sponsored</div><!-- /.card-row-label -->
                                    @endif
                                    {{--<div class="card-row-price">$100 / night</div><!-- -->--}}
                                </div><!-- /.card-row-image -->

                                <div class="card-row-body">
                                    <h2 class="card-row-title"><a href="{{ url('/result/doctor/'.$doctor->DoctorID) }}">{!! SiteHelpers::gridDisplayView($doctor->UserID,'UserID','1:tb_users:id:first_name|last_name') !!} </a></h2>
                                    <div class="card-row-content">
                                        <div class="speciality">{!! $doctor->Degree !!} | {!! $doctor->Expertization !!}</div>

                                    </div><!-- /.card-row-content -->
                                </div><!-- /.card-row-body -->

                                <div class="card-row-properties">
                                    <dl>

                                        <dd>Experience</dd><dt>{{ $doctor->Experience }}</dt>
                                        <dd>Fee</dd><dt>{{ $doctor->Fee }}</dt>
                                        <dd>Recommendation</dd>
                                        <dt><span class="fa fa-thumbs-up"></span> {{ $doctor->Recommendation }}</dt>

                                    </dl>
                                </div><!-- /.card-row-properties -->
                            </div><!-- /.card-row-inner -->
                        </div>
                    @endforeach

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

            </div><!-- /.cards-row -->












            <div class="pager">
                @if(isset($detail['clinics']))
                    {!! $detail['clinics']->appends($detail['request']->all() )->render() !!}
                @else
                    @if(isset($detail['doctors']))
                        {!! $detail['doctors']->appends($detail['request']->all() )->render() !!}
                    @endif
                @endif
            </div><!-- /.pagination -->


        </div><!-- /.content -->
    </div><!-- /.col-* -->

    <div class="col-sm-4 col-lg-3">
        <div class="sidebar">

            <div class="widget">
                <form action="{{ url('result') }}" method="get" >
                    @if($detail['request']['type']=='Clinic')
                    <input type="hidden" name="type" value="Clinic">
                    @else
                        <input type="hidden" name="type" value="Doctor">
                    @endif

                    <h2 class="widgettitle">Filter</h2>

                    <div class="background-white p20">
                        <span class="" data-toggle="collapse" data-target="#fee"><h5>Consultation Fee <span class="caret"></span></h5></span>
                        <div id="fee">
                            <input type="hidden" name="loc" value="{{ $detail['request']['loc'] }}">
                            <input type="hidden" name="q" value="{{ $detail['request']['q'] }}">
                            <input type="hidden" name="scope" value="{{ $detail['request']['scope'] }}">
                            <input type="hidden" name="min" id="minfee" class="auto_submit_form" >
                            <input type="hidden" name="max" id="maxfee" class="auto_submit_form">
                            <input type="text" id="amount" readonly style="border:0; color:#007264; font-weight:bold;">
                            <div id="consultFee"></div>
                        </div>
                        <hr>




                        <span class="" data-toggle="collapse" data-target="#locations"><h5>Locations <span class="caret"></span></h5></span>
                        <ul class="locations" id="locations">
                            @if(isset($detail['locations']))
                                @foreach($detail['locations'] as $key=>$loc)
                                    @if($loc!="")
                                        <div class="checkbox">
                                            <input type="checkbox" name="area[]" id="area[{{ $key }}]" value="{{ $loc }}"  @if( isset($detail['request']['area']) && in_array($loc,$detail['request']['area'])) checked="checked" @endif><label for="area[{{ $key }}]">{{ $loc }}</label>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
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
                </form>
            </div><!-- /.widget -->




        </div><!-- /.sidebar -->
    </div><!-- /.col-* -->

</div><!-- /.container -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css">
<script src="{{url('sximo/js/plugins/select2/select2.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script>
    $(document).ready(function () {
        var geocoder = new google.maps.Geocoder();;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
        }
//Get the latitude and the longitude;
        function successFunction(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            codeLatLng(lat, lng)
        }

        function errorFunction(){
//            alert("Geocoder failed");
        }

        function codeLatLng(lat, lng) {

            var latlng = new google.maps.LatLng(lat, lng);
            geocoder.geocode({'latLng': latlng}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    if (results[1]) {
                        //find country name
                        console.log(results[2]);
                        for (var i=0; i<results[0].address_components.length; i++) {
                            for (var b=0;b<results[0].address_components[i].types.length;b++) {

                                //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
                                if (results[0].address_components[i].types[b] == "administrative_area_level_2") {
                                    //this is the object you are looking for
                                    city= results[0].address_components[i];
                                    break;
                                }
                            }
                        }
                        //city data

                        $.get("/VerifyLocation?city="+city.long_name, function(data, status){
                            if(status=="success"){
//                                if($('#loc').val()=="")
//                                    $('#loc').val(data);

                            }
                        });

                    }
                }
            });
        }

        var location = $('#loc').val();
        var type = $('#type').val();
        $('#type').change(function () {
            type = $(this).val();

        });


        $("#q").on('input', function () {
//
            var location = $("#loc").val();
            var val = this.value;
//            console.log(encodeURI("/search/autocomplete?type="+ type + '&term=' + val + '&location=' + location ));

            $("#q").autocomplete({
                minLength: 2,
                source: encodeURI("/search/autocomplete?type=" + type + '&term=' + val + '&location=' + location),
                focus: function (event, ui) {
                    $("#q").val(ui.item.value);
                    return false;
                },
                select: function (event, ui) {
                    $("#q").val(ui.item.value);
                    $("#scope").val(ui.item.type);
                    return false;
                }
            })
                    .autocomplete("instance")._renderItem = function (ul, item) {


                return $("<li>")
                        .append("<span class='value left'>" + item.value + "</span> <span class='keyword label-inverse' style='float: right !important;'>" + item.type + "</span></a>")
                        .appendTo(ul);
            };
        });

        $("#loc").on('input', function () {
            var value = this.value;
            $("#loc").autocomplete({
                minLength: 1,
                source: encodeURI("/search/location?type=" + type),
                focus: function (event, ui) {
                    $("#loc").val(ui.item.value);
                    return false;
                },
                select: function (event, ui) {
                    $("#loc").val(ui.item.value);
                    return false;
                }
            })
                    .autocomplete("instance")._renderItem = function (ul, item) {
                return $("<li>")
                        .append("<span>" + item.value + "</span>")
                        .appendTo(ul);
            };
        });




        $("#q").bind('change', function () {

            var v = this.value;
            var xyz = $('#suggestion option').filter(function () {
                return this.value == v;
            }).data('value');
            if (xyz != 'undefined') {
                $('#scope').val(xyz);
            }


        });

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



        $(".desc-content").click(function(){
            this.style.height = 'auto';
        });

        if($('#type').val()==""){
            $('#type').select('Doctor');
        }
    });
</script>

