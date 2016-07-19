@extends('layouts.app')

@section('content')

	<div class="page-content row">
		<!-- Page header -->
		<div class="page-header">
			<div class="page-title">
				<h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
			</div>
			<ul class="breadcrumb">
				<li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
				<li><a href="{{ URL::to('doctor?return='.$return) }}">{{ $pageTitle }}</a></li>
				<li class="active">{{ Lang::get('core.addedit') }} </li>
			</ul>

		</div>

		<div class="page-content-wrapper">

			<ul class="parsley-error-list">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
			<div class="sbox animated fadeInRight">
				<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
				<div class="sbox-content">

					{!! Form::open(array('url'=>'doctor/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
					<div class="col-md-12">
						<fieldset><legend> Doctor</legend>

							<div class="form-group hidethis " style="display:none;">
								<label for="DoctorID" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('DoctorID', (isset($fields['DoctorID']['language'])? $fields['DoctorID']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('DoctorID', $row['DoctorID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="UserID" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('UserID', (isset($fields['UserID']['language'])? $fields['UserID']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									<select name='UserID' rows='5' id='UserID' class='select2 ' required  ></select>
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Expertization" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Expertization', (isset($fields['Expertization']['language'])? $fields['Expertization']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('Expertization', $row['Expertization'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Degree" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Degree', (isset($fields['Degree']['language'])? $fields['Degree']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('Degree', $row['Degree'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Experience" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Experience', (isset($fields['Experience']['language'])? $fields['Experience']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('Experience', $row['Experience'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Fee" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Fee', (isset($fields['Fee']['language'])? $fields['Fee']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('Fee', $row['Fee'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>


						</fieldset>
					</div>




					<div style="clear:both"></div>


					<div class="form-group">
						<label class="col-sm-4 text-right">&nbsp;</label>
						<div class="col-sm-8">
							<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>
							<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
							<button type="button" onclick="location.href='{{ URL::to('doctor?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
						</div>

					</div>

					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {


			$("#UserID").jCombo("{{ URL::to('doctor/comboselect?filter=tb_users:id:first_name|last_name') }}",
					{  selected_value : '{{ $row["UserID"] }}' });


			$('.removeCurrentFiles').on('click',function(){
				var removeUrl = $(this).attr('href');
				$.get(removeUrl,function(response){});
				$(this).parent('div').empty();
				return false;
			});

		});
	</script>
@stop