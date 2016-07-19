<style>
    input[type='radio']{
        display: none;
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

</style>



<div class="wrapper-header ">
    <div class=" container">
        <div class="col-sm-6 col-xs-6">
            <div class="page-title">
                {{--<h3> {!! SiteHelpers::gridDisplayView($schedule[0]->ClinicID,'ClinicID','1:tb_clinic:ClinicID:Name') !!} </h3>
                --}}
                <h3>{!! $pageTitle !!}</h3>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6 ">
            <ul class="breadcrumb pull-right">
                <li><a href="{{ URL::to('') }}">Home</a></li>
                <li>Results</li>
                <li class="active">Doctor</li>
            </ul>
        </div>

    </div>
</div>
<div class="container" style="min-height: 350px;">
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <h5><span class="fa fa-thumbs-up"></span> {!! SiteHelpers::gridDisplayView($id,'DoctorID','1:tb_doctor:DoctorID:Recommendation') !!} | <a href="#feedback" style="color: #000000"><span class="fa fa-comments-o"></span> Feedbacks</a></h5>
            </div>
        </div>
    </div>
    <hr/>
    <span class="text text-info">Note: The Schedule is according to {!! \SiteHelpers::gridDisplayView($id,'DoctorID','1:tb_doctor:DoctorID:timezone') !!} timezone. </span>
    <div class="row">
        <div class="col-md-6">
            <h4> At Clinics </h4>
        </div>
        <div class="col-md-12">
            <div class="accordion" id="accordion2">
                @if(!$schedule->isEmpty())
                  @foreach($schedule as $sch)
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="result-card">
                            <div class="media">
                                <div class="media-left">
                                    {!! SiteHelpers::showUploadedFile(SiteHelpers::gridDisplayView($sch->ClinicID,'ClinicID','1:tb_clinic:ClinicID:Photo'),'/uploads/clinics/',100) !!}
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading"><a href="{{ url('results/clinic/'.$sch->ClinicID) }}"> {!! SiteHelpers::gridDisplayView($sch->ClinicID,'ClinicID','1:tb_clinic:ClinicID:name') !!} </a></h4>
                                    <div class="speciality">{!! SiteHelpers::gridDisplayView($sch->ClinicID,'ClinicID','1:tb_clinic:ClinicID:Address') !!} | {!! SiteHelpers::gridDisplayView($sch->ClinicID,'ClinicID','1:tb_clinic:ClinicID:City') !!}</div>
                                    <div class="accordion-group">
                                        <div class="accordion-heading">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#{{ $sch->ClinicID }}">
                                                Book Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div id="{{ $sch->ClinicID }}" class="accordion-body collapse">
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
                @else
                    No Clinic Available
                @endif

                {{--<div class="accordion-group">--}}
                {{--<div class="accordion-heading">--}}
                {{--<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">--}}
                {{--Collapsible Group Item #2--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--<div id="collapseTwo" class="accordion-body collapse">--}}
                {{--<div class="accordion-inner">--}}
                {{--Anim pariatur cliche...--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>

        {{--<div class="col-sm-6">--}}
        {{--<div class="result-card">--}}
        {{--<div class="media">--}}
        {{--<div class="media-left">--}}
        {{--<img class="media-object img-circle profile-img" src="https://d13yacurqjgara.cloudfront.net/users/125288/screenshots/1170957/screen_shot_2013-07-26_at_2.14.38_am.png">--}}
        {{--</div>--}}
        {{--<div class="media-body">--}}
        {{--<h4 class="media-heading">aaaa<small> bbbb , bbb</small></h4>--}}
        {{--<div class="speciality">dddd</div>--}}
        {{--<p>jjhdjfg</p>--}}
        {{--<div class="book"><a href="{{ asset('/results/clinic/') }}">Book Now</a> </div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
    </div>
    <div class="row">
            @include('pages.feedbacklist')
    </div>
</div>

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
    });
</script>