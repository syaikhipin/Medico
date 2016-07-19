
<div class=" container">
    <div class="row">

        <ul class="breadcrumb">
            <li><a href="{{ URL::to('') }}">Home</a></li>
            <li>Results</li>
            <li class="active">Book Appointment</li>

        </ul>
    </div>



    <div class="row">
        <form  class="form" method="post" action="{!! url('appointment/confirm') !!}">
            <div class="col-md-6 col-sm-12">
                <div class="cards-row">

                            <div class="card-row">
                                <div class="card-row-inner">

                                  <div class="card-row-body">
                                        <h2 class="card-row-title">{!! SiteHelpers::gridDisplayView($DoctorID,'DoctorID','1:tb_doctor,tb_users:DoctorID:first_name|last_name',"id = tb_doctor.UserID") !!}</h2>
                                        <div class="card-row-content">


                                            <div class="speciality">   {!! SiteHelpers::gridDisplayView($ClinicID,'ClinicID','1:tb_clinic:ClinicID:Name|Address') !!}</div>
                                            <span class="fa fa-clock-o"> {!! date('d-m-Y h:i a',strtotime($ap_StartAt)) !!}</span>
                                        </div><!-- /.card-row-content -->
                                    </div><!-- /.card-row-body -->


                                </div><!-- /.card-row-inner -->
                            </div><!-- /.card-row -->
                    </div>

            </div>
            <input name="DoctorID" type="hidden" id="DoctorID" readonly class="form-control input-sm"  value="{!! $DoctorID !!}" />
            <input name="ClinicID" type="hidden" id="ClinicID" readonly class="form-control input-sm"  value="{!! $ClinicID !!}" />
            <input type="hidden" name="StartAt" id="StartAt" value="{!! $ap_StartAt !!}">
            <input type="hidden" name="EndAt" id="EndAt" value="{!! $ap_EndAt !!}">
            <div class=" col-sm-12 col-xs-12 col-md-6" style="margin-top: 20px">
                <div class="form-group col-sm-12">
                    <div class="row">
                    <label class="control-label col-sm-8">Select Patient</label></div>
                    <div class="row">
                    <div class="col-md-8">
                        <select name='PatientID' rows='5' id='PatientID' class='form-control select2'>
                        <option value="">-- Please Select --</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient->PatientID }}">{{ $patient->first_name }}{{ $patient->last_name }} </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-4">
                        <a href="{{ url('patients/update?continue=book') }}">Add New Patient</a>
                    </div>
                    </div>
                </div>

                <div class="form-group col-sm-12">

                    <div class="col-md-8">
                        <button class="btn btn-success  pull-right" type="submit">Confirm</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery.jCombo.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('sximo/js/plugins/select2/select2.min.js') }}"></script>

