@extends('layouts.login')

@section('content')
<div class="sbox">
	<div class="sbox-title">
			
				<h3 >{{ CNF_APPNAME }}</h3>
				
	</div>
	<div class="sbox-content">
	<div class="text-center   fadeInDown delayp1">
		@if(file_exists(public_path().'/sximo/images/'.CNF_FRONT_LOGO) && CNF_FRONT_LOGO !='')
			<img src="{{ asset('sximo/images/'.CNF_FRONT_LOGO)}}" alt="{{ CNF_APPNAME }}" width="70" height="70" />
		@else
			<img src="{{ asset('sximo/images/logo.svg')}}" alt="{{ CNF_APPNAME }}" width="70" height="70" />
		@endif
	</div>		
 {!! Form::open(array('url'=>'user/create', 'class'=>'form-signup')) !!}
	    	@if(Session::has('message'))
				{!! Session::get('message') !!}
			@endif
		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
		<ul class="nav nav-tabs" >
			<li ><a href="{{ url('user/login') }}" >  {{ Lang::get('core.signin') }} </a></li>
			{{--<li ><a href="{{ url('user/login#tab-forgot') }}"> {{ Lang::get('core.forgotpassword') }} </a></li>--}}
			@if(CNF_REGIST =='true')
				<li class="active"><a href="" >  {{ Lang::get('core.signup') }} </a></li>
			@endif

		</ul>

		<div class="form-group has-feedback">
		<label>{{ Lang::get('core.firstname') }}	 </label>
	  {!! Form::text('firstname', null, array('class'=>'form-control', 'placeholder'=>'First Name' ,'required'=>'' )) !!}
		<i class="icon-users form-control-feedback"></i>
	</div>
	
	<div class="form-group has-feedback">
		<label>{{ Lang::get('core.lastname') }}	 </label>
	 {!! Form::text('lastname', null, array('class'=>'form-control', 'placeholder'=>'Last Name','required'=>'')) !!}
		<i class="icon-users form-control-feedback"></i>
	</div>
	
	<div class="form-group has-feedback">
		<label>{{ Lang::get('core.email') }}	 </label>
	 {!! Form::text('email', null, array('class'=>'form-control', 'placeholder'=>'Email Address','required'=>'email')) !!}
		<i class="icon-envelop form-control-feedback"></i>
	</div>
	
	<div class="form-group has-feedback">
		<label>{{ Lang::get('core.password') }}	</label>
	 {!! Form::password('password', array('class'=>'form-control', 'placeholder'=>'Password','required'=>'')) !!}
		<i class="icon-lock form-control-feedback"></i>
	</div>
	
	<div class="form-group has-feedback">
		<label>{{ Lang::get('core.repassword') }}	</label>
	 {!! Form::password('password_confirmation', array('class'=>'form-control', 'placeholder'=>'Confirm Password','required'=>'')) !!}
		<i class="icon-lock form-control-feedback"></i>
	</div>

	<div class="form-group has-feedback">
			<label> Doctor/User</label>
			<select name="type" id="type" class="form-control">
				<option>--Please Select--</option>
				<option value="3">Doctor</option>
				<option value="5">User</option>
			</select>
	</div>
    @if(CNF_RECAPTCHA =='true') 
    <div class="form-group has-feedback  animated fadeInLeft delayp1">
        <label class="text-left"> Are u human ? </label>    
        <br />
        {!! captcha_img() !!} <br /><br />
        <input type="text" name="captcha" placeholder="Type Security Code" class="form-control" required/>

        <div class="clr"></div>
    </div>
    @endif						

      <div class="row form-actions">
        <div class="col-sm-12">
          <button type="submit" style="width:100%;" class="btn btn-primary pull-right"><i class="icon-user-plus"></i> {{ Lang::get('core.signup') }}	</button>
       </div>
      </div>
	  <p style="padding:10px 0" class="text-center">
	  <a href="{{ URL::to('user/login')}}"> {{ Lang::get('core.signin') }}  </a> | <a href="{{ URL::to('')}}"> {{ Lang::get('core.backtosite') }}  </a> 
   		</p>
 {!! Form::close() !!}
 </div>
</div> 
@stop
