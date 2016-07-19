@extends('layouts.app')

@section('content')


	<div class="page-content row">
		<!-- Page header -->
		<div class="page-header">
			<div class="page-title">
				<h3> Account  <small>View Detail My Info</small></h3>
				@if(Auth::user()->payment_method=='stripe')
					<a href="/user/cancel" id="cancel" class="btn btn-danger pull-right">Cancel Subscription</a>
				@endif
			</div>

			<ul class="breadcrumb">
				<li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
				<li class="active">Account</li>
			</ul>
		</div>

		<div class="page-content-wrapper m-t">
			@if(Session::has('message'))
				{!! Session::get('message') !!}
			@endif
			<ul>
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
			<ul class="nav nav-tabs" >
				<li class="active"><a href="#info" data-toggle="tab"> {{ Lang::get('core.personalinfo') }} </a></li>
				<li ><a href="#pass" data-toggle="tab">{{ Lang::get('core.changepassword') }} </a></li>
				@if(\Session::get('gid')==3)
					<li ><a href="#profile" data-toggle="tab">Doctor Profile </a></li>
					@if(\SiteHelpers::gridDisplayView(\Auth::user()->id,'UserID','1:tb_doctor:UserID:isSponsored')==0)
					<li ><a href="#sponsor" data-toggle="tab"> Get Sponsored in Search </a></li>
					@endif
					@endif
			</ul>

			<div class="tab-content">
				<div class="tab-pane active m-t" id="info">
					{!! Form::open(array('url'=>'user/saveprofile/', 'class'=>'form-horizontal ' ,'files' => true)) !!}
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4"> Username </label>
						<div class="col-md-8">
							<input name="username" type="text" id="username" disabled="disabled" class="form-control input-sm" required  value="{{ $info->username }}" />
						</div>
					</div>
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.email') }} </label>
						<div class="col-md-8">
							<input name="email" type="text" id="email"  class="form-control input-sm" value="{{ $info->email }}" />
						</div>
					</div>

					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.firstname') }} </label>
						<div class="col-md-8">
							<input name="first_name" type="text" id="first_name" class="form-control input-sm" required value="{{ $info->first_name }}" />
						</div>
					</div>

					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.lastname') }} </label>
						<div class="col-md-8">
							<input name="last_name" type="text" id="last_name" class="form-control input-sm" required value="{{ $info->last_name }}" />
						</div>
					</div>

					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4"> Contact Number</label>
						<div class="col-md-8">
							<input name="contactNo" type="text" id="contactNo" class="form-control input-sm"  value="{{ $info->contactNo }}" />
						</div>
					</div>

					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4"> City</label>
						<div class="col-md-8">
							<input name="City" type="text" id="City" class="form-control input-sm"  value="{{ $info->City }}" />
						</div>
					</div>

					<div class="form-group  " >
						<label for="ipt" class=" control-label col-md-4 text-right"> Avatar </label>
						<div class="col-md-8">
							<div class="fileinput fileinput-new" data-provides="fileinput">
			  <span class="btn btn-primary btn-file">
			  	<span class="fileinput-new">Upload Avatar Image</span><span class="fileinput-exists">Change</span>
					<input type="file" name="avatar">
				</span>
								<span class="fileinput-filename"></span>
								<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
							</div>
							<br />
							Image Dimension 80 x 80 px <br />
							{!! SiteHelpers::showUploadedFile($info->avatar,'/uploads/users/') !!}

						</div>
					</div>

					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
						<div class="col-md-8">
							<button class="btn btn-success" type="submit"> {{ Lang::get('core.sb_savechanges') }}</button>
						</div>
					</div>

					{!! Form::close() !!}
				</div>

				<div class="tab-pane  m-t" id="pass">
					{!! Form::open(array('url'=>'user/savepassword/', 'class'=>'form-horizontal ')) !!}

					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.newpassword') }} </label>
						<div class="col-md-8">
							<input name="password" type="password" id="password" class="form-control input-sm" value="" />
						</div>
					</div>

					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.conewpassword') }}  </label>
						<div class="col-md-8">
							<input name="password_confirmation" type="password" id="password_confirmation" class="form-control input-sm" value="" />
						</div>
					</div>


					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
						<div class="col-md-8">
							<button class="btn btn-danger" type="submit"> {{ Lang::get('core.sb_savechanges') }} </button>
						</div>
					</div>
					{!! Form::close() !!}
				</div>

				<div class="tab-pane  m-t" id="profile">

					@if(\Session::get('gid')=='3')
						{!! Form::open(array('url'=>'user/savedoctorinfo/', 'class'=>'form-horizontal ')) !!}

						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4"> Expertization </label>
							<div class="col-md-8">
								{!! Form::select('Expertization[]',$expertizes,$profile['Expertization'], ['id'=> 'Expertization','class'=>'select2','multiple'])!!}
							</div>
						</div>

						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4"> Degree </label>
							<div class="col-md-8">
								<input name="Degree" type="text" id="Degree" class="form-control input-sm" value="{{ $profile['Degree'] }}" />
							</div>
						</div>

						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4"> Experience </label>
							<div class="col-md-8">
								<input name="Experience" type="text" id="Experience" class="form-control input-sm" value="{{ $profile['Experience'] }}" placeholder="1 year" />
							</div>
						</div>

						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4"> Fee </label>
							<div class="col-md-8">
								<input name="Fee" type="text" id="Fee" class="form-control input-sm" value="{{ $profile['Fee'] }}" />
							</div>
						</div>

						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4"> Timezone </label>
							<div class="col-md-8">
								{!!  Camroncade\Timezone\Facades\Timezone::selectForm($profile['timezone'],'Select a timezone',array('class' => 'select2', 'name' => 'timezone')) !!}
							</div>
						</div>




						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
							<div class="col-md-8">
								<button class="btn btn-success" type="submit"> {{ Lang::get('core.sb_savechanges') }} </button>
							</div>
						</div>
						{!! Form::close() !!}
						{{--@elseif(\Session::get('gid')=='5')--}}
						{{--{!! Form::open(array('url'=>'user/savepatientinfo/', 'class'=>'form-horizontal ')) !!}--}}

						{{--<div class="form-group">--}}
						{{--<label for="ipt" class=" control-label col-md-4"> Birthdate </label>--}}
						{{--<div class="col-md-4">--}}
						{{--<div class="input-group m-b">--}}
						{{--{!! Form::text('BirthDate', $profile['BirthDate'],array('class'=>'form-control date')) !!}--}}
						{{--<span class="input-group-addon"><i class="fa fa-calendar"></i></span>--}}
						{{--</div>--}}
						{{--</div>--}}
						{{--</div>--}}

						{{--<div class="form-group">--}}
						{{--<label for="ipt" class=" control-label col-md-4">Blood Group </label>--}}
						{{--<div class="col-md-4">--}}
						<?php
						//$BloodGroup = explode(',',$profile['BloodGroup']);
						//		$BloodGroup_opt = array( 'A+' => 'A+' ,  'A-' => 'A-' ,  'B+' => 'B+' ,  'B-' => 'B-' ,  'AB+' => 'AB+' ,  'AB-' => 'AB-' ,  'O+' => 'O+' ,  'O-' => 'O-' , );
						?>
						{{--<select name='BloodGroup' rows='5'   class='select2 '  >--}}
						<?php
						//foreach($BloodGroup_opt as $key=>$val)
						//{
						//	echo "<option  value ='$key' ".($profile['BloodGroup'] == $key ? " selected='selected' " : '' ).">$val</option>";
						//}
						?>
						{{--</select>--}}
						{{--</div>--}}
						{{--</div>--}}

						{{--<div class="form-group">--}}
						{{--<label for="ipt" class=" control-label col-md-4">Issue </label>--}}
						{{--<div class="col-md-6">--}}

						{{--<textarea name="LatestIssue" id="LatestIssue" value="{{ $profile["LatestIssue"] }} " class="form-control" ></textarea>--}}
						{{--</div>--}}
						{{--</div>--}}

						{{--<div class="form-group">--}}
						{{--<label for="ipt" class=" control-label col-md-4">&nbsp;</label>--}}
						{{--<div class="col-md-8">--}}
						{{--<button class="btn btn-success " type="submit"> {{ Lang::get('core.sb_savechanges') }} </button>--}}
						{{--</div>--}}
						{{--</div>--}}
						{{--{!! Form::close() !!}--}}
					@endif
				</div>
				@if(\Auth::user()->group_id == 3 )
					<div class="tab-pane  m-t" id="sponsor">

						{!! Form::open(array('url'=> url('doctor/sponsor/'.\SiteHelpers::gridDisplayView(\Auth::user()->id,'UserID','1:tb_doctor:UserID:DoctorID')), 'class'=>'form-horizontal','method' =>'post')) !!}
						<div class="payment-errors alert alert-danger" style="display: none;"></div>
						<input type="hidden" value="{{ \Auth::user()->id }}" name="userid">
						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4"> Plan </label>
							<div class="col-md-8">
								<select name="plan" class="select2">
									<option value="">--Select--</option>
									@foreach($plans as $plan)
									<option value="{{$plan->id}}">{{ $plan->amount }}$ per {{$plan->duration}} Month</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4"> Choose Card </label>
							<div class="col-md-8">
								<select name="card" class="select2">
									<option value="">--Select--</option>
									@foreach($cards as $card)
									<option value="{{ $card->id }}">{{ $card->last4 }}</option>
									@endforeach
								</select>
							</div>
						</div>




						<div class="form-group">
							<div class="col-md-4"></div>
							<div class="col-md-8">
								<a href="{{ url('cards/update?continue=user/profile') }}">Add Card</a>
							</div>
						</div>


						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
							<div class="col-md-8">
								<button class="btn btn-success" type="submit"> Confirm </button>
							</div>
						</div>
						{!! Form::close() !!}



					</div>
			</div>
			@endif



		</div>
	</div>

	</div>

	<script>
		$(document).ready(function(){
			$('a#cancel').click(function(event){
				$.ajax({url:this.href, success: function(result){
					if(result=='false'){
						notyMessageError("There are some error.");
					}
					else{
						notyMessage("Subscription cancelled successfully");
					}

				}});
				event.preventDefault();
			})

			$('#Expertization').select2({
						width :'98%',
						tags: true,
					}
			);
		});



	</script>

@endsection