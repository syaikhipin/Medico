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
				<li><a href="{{ URL::to('cards?return='.$return) }}">{{ $pageTitle }}</a></li>
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

					{!! Form::open(array('url'=>'cards/save'.$return, 'class'=>'form-horizontal', 'parsley-validate'=>'','novalidate'=>'', 'id' => 'createcard')) !!}
					<div class="col-md-12">
						<fieldset><legend> Cards</legend>
							@if(isset($continue))
								<input type="hidden" name="continue" value="{{ $continue }}">
							@endif
							<div class="form-group hidethis " style="display:none;">
								<label for="Id" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group hidethis " style="display:none;">
								<label for="Card Id" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Card Id', (isset($fields['card_id']['language'])? $fields['card_id']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('card_id', $row['card_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group hidethis " style="display:none;">
								<label for="Cust Id" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Cust Id', (isset($fields['cust_id']['language'])? $fields['cust_id']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('cust_id', $row['cust_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Name" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Name', (isset($fields['name']['language'])? $fields['name']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('name', $row['name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Card Number" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Card Number', (isset($fields['last4']['language'])? $fields['last4']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!!  Form::text('ccn', '', [ 'class' => 'form-control', 'data-stripe' => 'number' ]) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							{{--<div class="form-group  " >--}}
								{{--<label for="Brand" class=" control-label col-md-4 text-left">--}}
									{{--{!! SiteHelpers::activeLang('Brand', (isset($fields['brand']['language'])? $fields['brand']['language'] : array())) !!}--}}
								{{--</label>--}}
								{{--<div class="col-md-6">--}}
									{{--{!! Form::text('brand', $row['brand'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}--}}
								{{--</div>--}}
								{{--<div class="col-md-2">--}}

								{{--</div>--}}
							{{--</div>--}}
							<div class="form-group">
								<label class="col-md-4 control-label" for="expiry-month">Expiration Date</label>
								<div class="col-md-3">
										{!!  Form::selectMonth('month', 'junuary', [ 'class' => 'form-control', 'data-stripe' => 'exp-month' ])!!}
								</div>
								<div class="col-md-3">
									{!! Form::selectRange('year', 2014, 2029, 2015, [ 'class' => 'form-control', 'data-stripe' => 'exp-year' ])!!}
								</div>

							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" for="cvv">Card CVV</label>
								<div class="col-md-6">
									{!! Form::text('cvc', '', [ 'class' => 'form-control', 'data-stripe' => 'cvc' ]) !!}
								</div>
							</div>

							<div class="form-group  " >
								<label for="Default" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Default', (isset($fields['isdefault']['language'])? $fields['isdefault']['language'] : array())) !!}
								</label>
								<div class="col-md-6">

									<?php $isdefault = explode(',',$row['isdefault']);
									$isdefault_opt = array( '1' => 'Yes' ,  '0' => 'No' , ); ?>
									<select name='isdefault' rows='5'   class='select2 '  >
										<?php
										foreach($isdefault_opt as $key=>$val)
										{
											echo "<option  value ='$key' ".($row['isdefault'] == $key ? " selected='selected' " : '' ).">$val</option>";
										}
										?></select>
								</div>
								<div class="col-md-2">

								</div>
							</div> </fieldset>
					</div>




					<div style="clear:both"></div>


					<div class="form-group">
						<label class="col-sm-4 text-right">&nbsp;</label>
						<div class="col-sm-8">
							{{--<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>--}}
							<button type="submit" name="saveplan" id="saveplan" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
							<button type="button" onclick="location.href='{{ URL::to('cards?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
						</div>

					</div>

					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {



			$('.removeCurrentFiles').on('click',function(){
				var removeUrl = $(this).attr('href');
				$.get(removeUrl,function(response){});
				$(this).parent('div').empty();
				return false;
			});



		});
	</script>
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<script>
		Stripe.setPublishableKey('pk_test_W5PgmCzdNeVGnzkDgVJMXzNB');
		jQuery(function($) {
			$('#createcard').submit(function(event) {
				var $form = $(this);
				// Disable the submit button to prevent repeated clicks
				$form.find('#saveplan').prop('disabled', true);
				Stripe.card.createToken($form, stripeResponseHandler);
				// Prevent the form from submitting with the default action
				return false;
			});
		});
		var stripeResponseHandler = function(status, response) {
			var $form = $('#createcard');
			if (response.error) {
				// Show the errors on the form
				$form.find('.payment-errors').css('display','block');
				$form.find('.payment-errors').text(response.error.message);
				$form.find('#saveplan').prop('disabled', false);
			} else {
				// token contains id, last4, and card type
				var token = response.id;
				// Insert the token into the form so it gets submitted to the server
				$form.append($('<input type="hidden" name="stripeToken" />').val(token));
				// and submit
				$form.get(0).submit();
			}
		};
	</script>
@stop