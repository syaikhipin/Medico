
@extends('layouts.app')
@section('content')

	<script type="text/javascript" src="{{ asset('sximo/js/plugins/chartjs/Chart.min.js') }}"></script>
	<div class="page-content row">
		<div class="page-header">
			<div class="page-title">
				<h3><i class="fa fa-desktop"></i> Dashboard <small> Summary info site </small></h3>
			</div>

		</div>
		<div class="page-content-wrapper">

			@if(Auth::check() && Auth::user()->group_id == 1)

				<section>
					<div class="row m-l-none m-r-none m-t  white-bg shortcut " >
						<div class="col-sm-6 col-md-3 b-r  p-sm ">
							<span class="pull-left m-r-sm text-navy"><i class="fa fa-plus-circle"></i></span>
							<a href="{{ URL::to('sximo/module') }}" class="clear">
				<span class="h3 block m-t-xs"><strong>  {{ Lang::get('core.dash_i_module') }}  </strong>
				</span> <small class="text-muted text-uc">  {{ Lang::get('core.dash_module') }}</small>
							</a>
						</div>
						<div class="col-sm-6 col-md-3 b-r  p-sm">
							<span class="pull-left m-r-sm text-info">	<i class="fa fa-cogs"></i></span>
							<a href="{{ URL::to('sximo/config') }}" class="clear">
				<span class="h3 block m-t-xs"><strong> {{ Lang::get('core.dash_i_setting') }}</strong>
				</span> <small class="text-muted text-uc">   {{ Lang::get('core.dash_setting') }} </small>
							</a>
						</div>
						<div class="col-sm-6 col-md-3 b-r  p-sm">
							<span class="pull-left m-r-sm text-warning">	<i class="fa fa-sitemap"></i></span>
							<a href="{{ URL::to('sximo/menu') }}" class="clear">
								<span class="h3 block m-t-xs"><strong>  {{ Lang::get('core.dash_i_sitemenu') }} </strong></span>
								<small class="text-muted text-uc">  {{ Lang::get('core.dash_sitemenu') }}  </small> </a>
						</div>
						<div class="col-sm-6 col-md-3 b-r  p-sm">
							<span class="pull-left m-r-sm ">	<i class="fa fa-users"></i></span>
							<a href="{{ URL::to('core/users') }}" class="clear">
			<span class="h3 block m-t-xs"><strong> {{ Lang::get('core.dash_i_usergroup') }}</strong>
			</span> <small class="text-muted text-uc">  {{ Lang::get('core.dash_usergroup') }} </small> </a>
						</div>
					</div>
				</section>


				<div class="row m-t">
					<div class="col-md-12">
						<div class="sbox">
							<div class="sbox-title"> <h3> Recent Users <small> ( Last Activity ) </small> </h3> </div>
							<div class="sbox-content">
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive" >
											<table class="table table-striped">
												<tr>
													<th>  </th>
													<th> Users </th>
													<th> Last Activity </th>
												</tr>
												@foreach($online_users as $user)
													<tr>
														<td>  {!! SiteHelpers::showUploadedFile($user->avatar,'/uploads/users/') !!}</td>
														<td>{{ $user->first_name}} {{ $user->last_name}}</td>
														<td> {{ date("Y-m-d H:i:s", $user->last_activity) }}</td>
													</tr>
												@endforeach

											</table>
										</div>
									</div>

								</div>


							</div>
						</div>
					</div>



				</div>
			@elseif(Auth::check() && Auth::user()->group_id == 3)
				<div class="row m-t">
					<div class="col-md-12">
						<div class="sbox">
							<div class="sbox-title">
								<h3> Today's Schedule </h3>
								@if(\App\Models\Doctor::where('UserID',Auth::user()->id)->count()==1)
								<a  class="btn btn-info" style="float: right" href="{!!  url('results/doctor/'.\SiteHelpers::gridDisplayView(\Auth::user()->id,'UserID','1:tb_doctor:UserID:DoctorID') ) !!}">Book Appointment</a>
								@endif
							</div>
							<div class="sbox-content">
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive" >
											@if($schedule->isEmpty())
												No Schedule
											@endif
												@foreach($schedule as $sch)
													<h4 style="text-align:center"> {!! SiteHelpers::gridDisplayView($sch->ClinicID,'ClinicID','1:tb_clinic:ClinicID:Name|Address') !!} </h4>
													<?php
													$t=date('d-m-Y');
													$today= date("D",strtotime($t));
													?>
													<table class="table table-striped table-bordered">
													@foreach($sch->detail as $sch_detail)
														<?php
														if($today==$sch_detail->Day)
															$style=  'style="background-color:yellow"';

														?>

														<?php $morningSlot = explode(',',$sch_detail->morning_slots)  ?>
														<?php if($sch_detail->morning_slots!=''){
															$morningSlot = explode(',',$sch_detail->morning_slots);
															echo '<tr><td>';
														}
														else
															$morningSlot = array();
														?>

														<div class="row" style="margin: 0px;padding: 0px">
														@foreach($morningSlot as $slot)
														<div class="col-sm-4 text-center">
														<?php
														$ap_time = date('Y-m-d H:i:s',strtotime($slot));

														$now = \Camroncade\Timezone\Facades\Timezone::convertFromUTC(date('Y-m-d H:i:s'),\SiteHelpers::gridDisplayView($sch->DoctorID,'DoctorID','1:tb_doctor:DoctorID:timezone'),'Y-m-d H:i:s');

														$n = $now > $ap_time ? 1 :0;
														if($n==0)
														//$n= \App\Models\Appointment::where(['DoctorID' => $sch->DoctorID,'ClinicID' => $sch->ClinicID,'StartAt' =>$ap_time] )->count();
														$n= \DB::table('tb_appointment')
														->where(['DoctorID' => $sch->DoctorID,'ClinicID' => $sch->ClinicID])
														->where('isCancelled',0)
														->where('StartAt' ,$ap_time )
														->orWhere(function ($query) use($ap_time) {
														$query->where('StartAt', '<',$ap_time )
														->where('EndAt', '>', $ap_time);
														})->count();
														?>
														{{--<input class="aptime" type="radio" {!! $n!=0 ? 'disabled' :'' !!} name="appointment_start_time" id = 'slot_{!!$ap_time !!}' value="{!! $ap_time !!}" onchange="alert('aaa')">--}}
														<label for ='slot_{!!$ap_time !!}' class="slot {!! $n!=0 ? 'disabled' :'' !!}">{!! date('h:i A',strtotime($ap_time)) !!}</label>
														</div>
														@endforeach
										</div>
										</td>
										</tr>
										<?php if($sch_detail->afternoon_slots!='')
										{
										$afternoonSlot = explode(',',$sch_detail->afternoon_slots);
										echo '<tr><td>';
										}
										else{
										$afternoonSlot = array();
										}

										?>

										<div class="row" style="margin: 0px;padding: 0px">
										@foreach($afternoonSlot as $slot)

										<div class="col-sm-4 text-center">
										<?php
										$ap_time = date('Y-m-d H:i:s',strtotime($slot));
										$now = \Camroncade\Timezone\Facades\Timezone::convertFromUTC(date('Y-m-d H:i:s'),\SiteHelpers::gridDisplayView($sch->DoctorID,'DoctorID','1:tb_doctor:DoctorID:timezone'),'Y-m-d H:i:s');
										$n = $now > $ap_time ? 1 :0;
										if($n==0)
										//	$n= \App\Models\Appointment::where(['DoctorID' => $sch->DoctorID,'ClinicID' => $sch->ClinicID,'StartAt' => $ap_time] )->count();
										$n= \DB::table('tb_appointment')
										->where(['DoctorID' => $sch->DoctorID,'ClinicID' => $sch->ClinicID])
										->where('isCancelled',0)
										->where('StartAt' ,$ap_time )
										->orWhere(function ($query) use($ap_time) {
										$query->where('StartAt', '<',$ap_time )
										->where('EndAt', '>', $ap_time);
										})->count();
										?>
										{{--<input type="radio" {!! $n!=0 ? 'disabled' :'' !!} name="appointment_start_time" id = 'slot_{!! $ap_time !!}' value="{!! $ap_time !!}">--}}
										<label for ='slot_{!! $ap_time !!}' class="slot {!! $n!=0 ? 'disabled' :'' !!}">{!!  date('h:i A',strtotime($ap_time)) !!}</label>
										</div>
										@endforeach
										</div>
										</td>
										</tr>
										@endforeach
										</table>
										</form>
										@endforeach
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endif

				@if(Auth::check() && (Auth::user()->group_id == 3 || Auth::user()->group_id == 5))

					<div class="row m-t">
						<div class="col-md-12">
							<div class="sbox">
								<div class="sbox-title"> <h3> Today's Appointments </h3> </div>
								<div class="sbox-content">
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive" >
												@if(!empty($appointments))
													<table class="table table-striped table-bordered">
														<tr>
															<th>Patient</th>
															<th>Clinic</th>
															<th>From</th>
															<th>To</th>
															@if(\Auth::user()->group_id==5)
																<th>Action</th>
															@endif
														</tr>
														@foreach($appointments as $appointment)
															<?php
															$today =  \Camroncade\Timezone\Facades\Timezone::convertFromUTC(date('Y-m-d H:i:s'),\SiteHelpers::gridDisplayView($appointment->DoctorID,'DoctorID','1:tb_doctor:DoctorID:timezone'),'Y-m-d'); ?>
															@if(\Auth::user()->group_id==3)
																@if($today == $appointment->CreatedAt)
																<tr>
																	<td>{!! SiteHelpers::gridDisplayView($appointment->PatientID,'PatientID','1:tb_patient:PatientID:first_name|last_name') !!} </td>
																	<td>{!! SiteHelpers::gridDisplayView($appointment->ClinicID,'ClinicID','1:tb_clinic:ClinicID:Name|Address') !!} </td>
																	<td>{!!  date('d-m-Y h:i a',strtotime($appointment->StartAt))  !!}</td>
																	<td>{!!  date('d-m-Y h:i a',strtotime($appointment->EndAt))  !!}</td>
																</tr>
																@endif
															@else
																<tr>
																	<td>{!! SiteHelpers::gridDisplayView($appointment->PatientID,'PatientID','1:tb_patient:PatientID:first_name|last_name') !!} </td>
																	<td>{!! SiteHelpers::gridDisplayView($appointment->ClinicID,'ClinicID','1:tb_clinic:ClinicID:Name|Address') !!} </td>
																	<td>{!!  date('d-m-Y h:i a',strtotime($appointment->StartAt))  !!}</td>
																	<td>{!!  date('d-m-Y h:i a',strtotime($appointment->EndAt))  !!}</td>
																	@if(\Auth::user()->group_id==5)
																		<td><a href="{{ url('appointment/cancel/'.$appointment->AppointmentID) }}" id="cancel_ap" class="label label-danger">Cancel</a> </td>
																	@endif
																</tr>
															@endif
														@endforeach
													</table>
												@else
													No Appointment
												@endif
											</div>
										</div>

									</div>


								</div>
							</div>
						</div>
					</div>
				@endif
		</div>
	</div>
@endsection