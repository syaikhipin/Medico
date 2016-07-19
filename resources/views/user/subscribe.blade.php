@extends('layouts.login')

@section('content')

    <div class="sbox ">
        <div class="sbox-title">

            <h3 >{{ CNF_APPNAME }} <small> {{ CNF_APPDESC }} </small></h3>

        </div>
        <div class="sbox-content">
            <div class="text-center  fadeInDown delayp1">
                <img src="{{ asset('sximo/images/logo-black.svg')}}" width="70" height="70" />
            </div>

            @if(Session::has('message'))
                {!! Session::get('message') !!}
            @endif
            <ul class="parsley-error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>


            <div class="tab-content" >
                <div class="tab-pane active m-t" id="tab-sign-in">


                        <div class="form-group has-feedback fadeInLeft delayp1">
                           <select class="form-control" name="payemnt_method" id="payemnt_method">
                               <option value="0">Select</option>
                               @if(CNF_PAYMENT=='both'||CNF_PAYMENT=='paypal')
                               <option value="1">Paypal</option>
                               @endif
                               @if(CNF_PAYMENT=='both'||CNF_PAYMENT=='stripe')
                               <option value="2">Stripe</option>
                               @endif
                           </select>

                        </div>
                    <div class="payments">
                    <div class="stripe" id="p2" style="display: none;">
                    {!! Form::open(array('url'=>'user/subscribe', 'class'=>'form-signup','id' => 'subscribe-form')) !!}
                        <div class="payment-errors alert alert-danger" style="display: none;"></div>

                        <div class="form-group has-feedback">
                    <label>{{ Lang::get('core.subscription') }}	 </label>
                            {{--{!! Form::select('subscription',[ 'month' => '(Monthly) 10$ per month', 'year' => '(Yearly) 100$ per month' ], 'month', array('class'=>'form-control','required'=>'' )) !!}
                            --}}
                            <select name="subscription" class="form-control">
                                @foreach($plans as $plan)
                                    <option value="sbplan{{$plan->id}}">{{ $plan->amount }}$ per {{$plan->duration}} Month</option>
                        @endforeach
                    </select>
                    </div>
                    <input type="hidden" value="{{$userid}}" name="userid">
                    <div class="form-group has-feedback">
                    <label>Credit card number</label>
                    {!!  Form::text('ccn', '', [ 'class' => 'form-control', 'data-stripe' => 'number' ]) !!}
                    </div>

                    <div class="form-group has-feedback">
                    {!!  Form::label( 'cvc', 'CVC number') !!}
                    {!! Form::text('cvc', '', [ 'class' => 'form-control', 'data-stripe' => 'cvc' ]) !!}
                    </div>

                    <div class="form-group has-feedback">
                    {!!  Form::label( 'expiration', 'Expiration') !!}
                    <div class="col-md-12 form-group">
                    <div class="col-md-6">
                    {!!  Form::selectMonth('month', 'junuary', [ 'class' => 'form-control', 'data-stripe' => 'exp-month' ])!!}
                    </div>
                    <div class="col-md-6">
                    {!! Form::selectRange('year', 2014, 2029, 2015, [ 'class' => 'form-control', 'data-stripe' => 'exp-year' ])!!}
                    </div>
                    </div>
                    </div>

                    <div class="row form-actions">
                         <div class="col-sm-12">
                                <button id='signup' type="submit" style="width:100%;" class="btn btn-primary pull-right"> {{ Lang::get('core.subscribe') }}	</button>
                         </div>
                    </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="2" id="p1" style="display: none;">
                        @if(CNF_PAYPAL_MODE=='sandbox')
                            {!! Form::open(array('url'=> 'https://www.sandbox.paypal.com/cgi-bin/webscr', 'class'=>'form-signup','method' =>'post','target'=> '_top')) !!}
                        @else
                            {!! Form::open(array('url'=> 'https://www.paypal.com/cgi-bin/webscr', 'class'=>'form-signup','method' =>'post','target'=> '_top')) !!}
                        @endif
                        <input type="hidden" name="cmd" value="_xclick-subscriptions">
                        <input type="hidden" name="business" value="{{ CNF_PAYPAL_BUSINESS }}">
                        <input type="hidden" name="lc" value="US">
                        <input type="hidden" name="item_name" value="subscription">
                        <input type="hidden" name="item_number" value="subscribe_alpha">
                        <input type="hidden" name="no_note" value="1">
                        <input type="hidden" name="no_shipping" value="2">
                        <input type="hidden" name="rm" value="1">
                        <input type="hidden" name="return" value="http://107.170.186.189/user/login">
                        <input type="hidden" name="cancel_return" value="http://107.170.186.189/user/register">
                        <input type="hidden" name="src" value="1">
                        <input type="hidden" name="currency_code" value="USD">
                        <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHosted">

                        <input type="hidden" name="on0" value="plan">
                        <div class="form-group has-feedback">
                        <select id="os0" name="os0" class="form-control">
                            <option selected="selected">Select</option>
                        {{--<option id=value="Monthly Plan">Monthly Plan : $29.00 USD/monthly</option>--}}
                        {{--<option value="1 Year Plan">1 Year Plan : $17.00 USD/monthly</option>--}}
                        {{--<option value="2 Year Plan">2 Year Plan : $13.00 USD/monthly</option>--}}
                            @foreach($plans as $plan)
                                <option data-amount="{{ $plan->amount }}" data-period="{{ $plan->duration  }}" id="{{ $plan->id }}" value="{{ $plan->name }}"> ${{ $plan->amount }} USD per {{ $plan->duration }} Month</option>
                            @endforeach
                        </select>
                        </div>
                            {{ \DB::table('tb_sub_plans')->where('id','=',1 )->pluck('amount')}}
                        <input type="hidden" name="currency_code" value="USD">
                        <input type="hidden" id="a3" name="a3" value="">
                        <input type="hidden" id="p3" name="p3" value="">
                        <input type="hidden" id="t3" name="t3" value="">
                        {{--<input type="hidden" name="option_select0" value="Monthly Plan">--}}
                        {{--<input type="hidden" name="option_amount0" value="29.00">--}}
                        {{--<input type="hidden" name="option_period0" value="M">--}}
                        {{--<input type="hidden" name="option_frequency0" value="1">--}}
                        {{--<input type="hidden" name="option_select1" value="1 Year Plan">--}}
                        {{--<input type="hidden" name="option_amount1" value="204.00">--}}
                        {{--<input type="hidden" name="option_period1" value="Y">--}}
                        {{--<input type="hidden" name="option_frequency1" value="1">--}}
                        {{--<input type="hidden" name="option_select2" value="2 Year Plan">--}}
                        {{--<input type="hidden" name="option_amount2" value="312.00">--}}
                        {{--<input type="hidden" name="option_period2" value="Y">--}}
                        {{--<input type="hidden" name="option_frequency2" value="2">--}}



                            {{--@foreach($plans as $plan)--}}

                                {{--<input type="hidden" name="option_select{{$count}}" value="{{ $plan->name }}">--}}
                                {{--<input type="hidden" name="option_amount{{$count}}" value="{{ $plan->amount }}">--}}
                                {{--<input type="hidden" name="option_period{{$count}}" value="M">--}}
                                {{--<input type="hidden" name="option_frequency{{$count}}" value="{{ $plan->duration }}">--}}

                            {{--@endforeach--}}
                        <input type="hidden" name="option_index" value="2">
                        <input type="hidden" name="custom" value="{{json_encode(array('user_id' => $userid))}}">
                        <div class="row form-actions">
                            <div class="col-sm-12">
                                <button id='signup' type="submit" style="width:100%;" class="btn btn-primary pull-right"> {{ Lang::get('core.subscribe') }}	</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    </div>


                </div>


            </div>

        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#or').click(function(){
                $('#fr').toggle();
            });

            $('#payemnt_method').on('change',function(){
                    $('.payments').children().hide();
                   console.log('#p'+this.selectedIndex);
                    $('#p'+this.selectedIndex).show();
            });

            $('select#os0').on('change',function(){
                var opt= this.options[this.selectedIndex];

                    $("#a3").val($(opt).attr('data-amount'));
                    $("#p3").val($(opt).attr('data-period'));
                    $("#t3").val('M');
            });
        });
    </script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script>
    Stripe.setPublishableKey('pk_test_W5PgmCzdNeVGnzkDgVJMXzNB');
    jQuery(function($) {
    $('#subscribe-form').submit(function(event) {
    var $form = $(this);
    // Disable the submit button to prevent repeated clicks
    $form.find('#signup').prop('disabled', true);
    Stripe.card.createToken($form, stripeResponseHandler);
    // Prevent the form from submitting with the default action
    return false;
    });
    });
    var stripeResponseHandler = function(status, response) {
    var $form = $('#subscribe-form');
    if (response.error) {
    // Show the errors on the form
    $form.find('.payment-errors').css('display','block');
    $form.find('.payment-errors').text(response.error.message);
    $form.find('#signup').prop('disabled', false);
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