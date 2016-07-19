<style>
    input[type='radio']{
        display: none !important;
    }

    input[type=radio] + label:before{
        display: none !important;
    }

    input[type=radio] + label{
        padding-left: 0px !important;
    }
    .ui-autocomplete{
        width: 225px !important;
    }
    label.slot{
        cursor: pointer;
        padding: 2px 4px;
        border-radius: 10px;
        color:#7fac5a;
    }
    label.slot:hover{
        background-color: #7fac5a;
        color: white;
    }
    label.slot.disabled{
        background:0;
        text-decoration: line-through;
        color: #818181;
        opacity: 0.4;
        cursor: default;
    }

    .list-group{
        margin-bottom: 0px;
    }


    .widget .panel-body { padding:0px; }
    .widget .list-group { margin-bottom: 0; }
    .widget .panel-title { display:inline }
    .widget .label-info { float: right; }
    .widget li.list-group-item {border-radius: 0;border: 0;border-top: 1px solid #ddd;}
    .widget li.list-group-item:hover { background-color: rgba(86,61,124,.1); }
    .widget .mic-info { color: #666666;font-size: 11px; }
    .widget .action { margin-top:5px; }
    .widget .comment-text { font-size: 12px; }
    .widget .btn-block { border-top-left-radius:0px;border-top-right-radius:0px; }
    .widget .feedback { top:10px }

</style>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h2>
                {!! SiteHelpers::showUploadedFile(SiteHelpers::gridDisplayView($id,'UserID','1:tb_users,tb_doctor:DoctorID:avatar',"id = tb_doctor.UserID"),'/uploads/users/','auto','square',100) !!}
                {!! $pageTitle !!}
            </h2>
        </div>
    </div>
    <div class="row">

        <div class="col-sm-8 col-lg-9">
            <div class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <h5><span class="fa fa-thumbs-up"></span> {!! SiteHelpers::gridDisplayView($id,'DoctorID','1:tb_doctor:DoctorID:Recommendation') !!} | <a href="#feedback" style="color: #000000"><span class="fa fa-comments-o"></span> Feedbacks</a></h5>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span class="text text-info">Note: The Schedule is according to {!! \SiteHelpers::gridDisplayView($id,'DoctorID','1:tb_doctor:DoctorID:timezone') !!} timezone. </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h4>At Clinics</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="accordion" id="accordion2">
                            @if(!$schedule->isEmpty())
                                <div class="card-rows">
                                    @foreach($schedule as $sch)

                                        <div class="card-row">
                                            <div class="card-row-image" style="width:150px;padding: 20px;height: auto">
                                                {!! SiteHelpers::showUploadedFile(SiteHelpers::gridDisplayView($sch->ClinicID,'ClinicID','1:tb_clinic:ClinicID:Photo'),'/uploads/clinics/',100,'square') !!}
                                            </div>


                                            <div class="card-row-body">
                                                <h4 class="card-row-title"><a href="{{ url('result/clinic/'.$sch->ClinicID) }}"> {!! SiteHelpers::gridDisplayView($sch->ClinicID,'ClinicID','1:tb_clinic:ClinicID:name') !!} </a></h4>
                                                <div class="card-row-content">
                                                    <div class="speciality">{!! SiteHelpers::gridDisplayView($sch->ClinicID,'ClinicID','1:tb_clinic:ClinicID:Address') !!} | {!! SiteHelpers::gridDisplayView($sch->ClinicID,'ClinicID','1:tb_clinic:ClinicID:City') !!}</div>
                                                    <div class="accordion-group">
                                                        <div class="accordion-heading">
                                                            @if(SiteHelpers::gridDisplayView($id,'DoctorID','1:tb_doctor,tb_users:DoctorID:active',"id = tb_doctor.UserID")==1)
                                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#{{ $sch->ClinicID }}">
                                                                    Book Now
                                                                </a>
                                                            @else
                                                                Not Currently Available
                                                            @endif
                                                        </div>
                                                    </div>


                                                    <div id="{{ $sch->ClinicID }}" class="accordion-body collapse col-md-12">
                                                        <div class="accordion-inner" style="background-color: #ffffff">
                                                            <div class="table-responsive">
                                                                <form method="post" action="{{ url('appointment/book') }}">
                                                                    <input type="hidden" value="{!! $sch->DoctorID !!}" name="DoctorID">
                                                                    <input type="hidden" value="{!! $sch->ClinicID !!}" name="ClinicID">
                                                                    <input type="hidden" value="{!! $sch->VisitTime !!}" name="VisitTime" id="VisitTime">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                        <tr>
                                                                            <th style="width:20%">Day</th>
                                                                            <th width="80%">Appointments</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php $date = date('d-m-Y'); $x=0; ?>
                                                                        @for($i=0;$i<5;$i++)
                                                                            <tr>
                                                                                <th>{{date('d-m-Y',time()+$x)}}<br/> {!!date("D",strtotime(date('d-m-Y',time()+$x)))!!}</th>
                                                                                <th>

                                                                                    @foreach($sch->scheduledetail as $schedule)
                                                                                        @if($schedule->Day==date("D",strtotime(date('d-m-Y',time()+$x))).'')
                                                                                            <?php if($schedule->morning_slots!=''){
                                                                                                $morningSlot = explode(',',$schedule->morning_slots);
                                                                                            }
                                                                                            else
                                                                                                $morningSlot = array();
                                                                                            ?>
                                                                                            <div class="row" style="margin: 0px;padding: 0px">
                                                                                                @foreach($morningSlot as $slot)
                                                                                                    <div class="col-sm-4 text-center">
                                                                                                        <?php
                                                                                                        $ap_time = date('Y-m-d H:i:s',strtotime(date('Y-m-d',time()+$x).' '.$slot));
                                                                                                        $now = \Camroncade\Timezone\Facades\Timezone::convertFromUTC(date('Y-m-d H:i:s'),\SiteHelpers::gridDisplayView($sch->DoctorID,'DoctorID','1:tb_doctor:DoctorID:timezone'),'Y-m-d H:i:s');
                                                                                                        $n = $now > $ap_time ? 1 :0;
                                                                                                        if($n==0)
                                                                                                            $n= \DB::table('tb_appointment')
                                                                                                                    ->where(['DoctorID' => $sch->DoctorID,'ClinicID' => $sch->ClinicID])
                                                                                                                    ->where('isCancelled',0)
                                                                                                                    ->where('StartAt' ,$ap_time )
                                                                                                                    ->orWhere(function ($query) use($ap_time) {
                                                                                                                        $query->where('StartAt', '<',$ap_time )
                                                                                                                                ->where('EndAt', '>', $ap_time);
                                                                                                                    })->count();

                                                                                                        ?>
                                                                                                        <input type="radio" {!! $n!=0 ? 'disabled' :'' !!} name="appointment_start_time" id = 'slot_{!!$ap_time !!}' value="{!! $ap_time !!}">
                                                                                                        <label for ='slot_{!!$ap_time !!}' class="slot {!! $n!=0 ? 'disabled' :'' !!}">{!! date('h:i A',strtotime($ap_time)) !!}</label>
                                                                                                    </div>
                                                                                                @endforeach
                                                                                            </div>
                                                                                            <?php if($schedule->afternoon_slots!=''){
                                                                                                $afternoonSlot = explode(',',$schedule->afternoon_slots);
                                                                                                echo '<hr/>';
                                                                                            }
                                                                                            else
                                                                                                $afternoonSlot = array();
                                                                                            ?>

                                                                                            <div class="row" style="margin: 0px;padding: 0px">
                                                                                                @foreach($afternoonSlot as $slot)

                                                                                                    <div class="col-sm-4 text-center">
                                                                                                        <?php
                                                                                                        $ap_time = date('Y-m-d H:i:s',strtotime(date('Y-m-d',time()+$x).' '.$slot));
                                                                                                        $now = \Camroncade\Timezone\Facades\Timezone::convertFromUTC(date('Y-m-d H:i:s'),\SiteHelpers::gridDisplayView($sch->DoctorID,'DoctorID','1:tb_doctor:DoctorID:timezone'),'Y-m-d H:i:s');
                                                                                                        $n = $now > $ap_time ? 1 :0;
                                                                                                        if($n==0)
                                                                                                            $n= \DB::table('tb_appointment')
                                                                                                                    ->where(['DoctorID' => $sch->DoctorID,'ClinicID' => $sch->ClinicID])
                                                                                                                    ->where('isCancelled',0)
                                                                                                                    ->where('StartAt' ,$ap_time )
                                                                                                                    ->orWhere(function ($query) use($ap_time) {
                                                                                                                        $query->where('StartAt', '<',$ap_time )
                                                                                                                                ->where('EndAt', '>', $ap_time);
                                                                                                                    })->count();
                                                                                                        ?>
                                                                                                        <input type="radio" {!! $n!=0 ? 'disabled' :'' !!} name="appointment_start_time" id = 'slot_{!! $ap_time !!}' value="{!! $ap_time !!}">
                                                                                                        <label for ='slot_{!! $ap_time !!}' class="slot {!! $n!=0 ? 'disabled' :'' !!}">{!!  date('h:i A',strtotime($ap_time)) !!}</label>
                                                                                                    </div>
                                                                                                @endforeach
                                                                                            </div>

                                                                                        @endif
                                                                                    @endforeach
                                                                                </th>
                                                                            </tr>
                                                                            <?php $x=$x+86400;?>
                                                                        @endfor
                                                                        </tbody>
                                                                    </table>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            @else
                                No Clinic Available
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    @include('pages.feedbacklist')
                </div>
            </div>
        </div>



        <div class="col-sm-4 col-lg-3">
            <div class="sidebar">


                <div class="widget">


                    <div class="background-white p20">
                        <form method="get"  action="{{URL::to('result')}}">
                            <div class="form-group">
                                <label for="">Choose</label>
                                <select id="type" class="form-control" name="type" title="Please Select" data-width="250px"
                                        data-live-search="false">
                                    <option value="Clinic">Clinic</option>
                                    <option value="Doctor">Doctor</option>
                                </select>
                            </div><!-- /.form-group -->

                            <div class="form-group">
                                <label for="">Location</label>
                                <input id="loc" name="loc" class="form-control" type="text" placeholder="Location"
                                       autocomplete="off">
                            </div><!-- /.form-group -->

                            <div class="form-group">
                                <label for="">Keyword</label>

                                <input id="q" name="q" class="form-control ui-autocomplete-input" placeholder="Keyword"
                                       type="text" autocomplete="off"/>
                                <input id="scope" type="hidden" name="scope">
                            </div><!-- /.form-group -->


                            <button class="btn btn-primary btn-block" type="submit">Search</button>
                        </form>
                    </div>
                </div><!-- /.widget -->




            </div><!-- /.sidebar -->
        </div><!-- /.col-* -->
    </div><!-- /.row -->

</div><!-- /.container -->

<script src="{{url('sximo/js/plugins/select2/select2.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

<script>
    $(document).ready(function(){
        $('input[name = "appointment_start_time"]').on('change', function() {

            $(this).closest("form").submit();
        });


        $('#show-more').on('click', function(){
            $('.list-group li:gt(3)').show();
            $('#show-less').removeClass('hidden');
            $('#show-more').addClass('hidden');
        });

        $('#show-less').on('click', function(){
            $('.list-group li:gt(3)').hide();
            $('#show-more').removeClass('hidden');
            $('#show-less').addClass('hidden');
        });

        //Show only four items
        if ( $('.list-group li').length > 4 ) {
            $('.lia-list-standard li:gt(3)').hide();
            $('#show-more').removeClass('hidden');
            $('#show-less').click();
        }

        if ( $('.list-group li').length < 4 ) {
            $('.lia-list-standard li:gt(3)').hide();
            $('#show-more').addClass('hidden');
            $('#show-less').addClass('hidden');
        }

        var geocoder = new google.maps.Geocoder();
        ;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
        }
//Get the latitude and the longitude;
        function successFunction(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            codeLatLng(lat, lng)
        }

        function errorFunction() {
            //    alert("Geocoder failed");
        }

        function codeLatLng(lat, lng) {

            var latlng = new google.maps.LatLng(lat, lng);
            geocoder.geocode({'latLng': latlng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    if (results[1]) {
                        //find country name
                        console.log(results[2]);
                        for (var i = 0; i < results[0].address_components.length; i++) {
                            for (var b = 0; b < results[0].address_components[i].types.length; b++) {

                                //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
                                if (results[0].address_components[i].types[b] == "administrative_area_level_2") {
                                    //this is the object you are looking for
                                    city = results[0].address_components[i];
                                    break;
                                }
                            }
                        }
                        //city data
                        //alert(city.long_name);
                        $.get("/VerifyLocation?city=" + city.long_name, function (data, status) {
                            if (status == "success") {
                                $('#loc').val(data);
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

    });
</script>
