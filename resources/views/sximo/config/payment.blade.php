@extends('layouts.app')
@section('content')

    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3><i class="fa fa-money"></i> Payment Settings </h3>
            </div>


            <ul class="breadcrumb">
                <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
                <li><a href="{{ URL::to('config') }}">Payment Settings</a></li>
            </ul>

        </div>
        <div class="page-content-wrapper">
            @if(Session::has('message'))

                {{ Session::get('message') }}

            @endif
            <ul class="parsley-error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <div class="block-content">
                @include('sximo.config.tab')
                <div class="tab-content m-t">
                    <div class="tab-pane active use-padding" id="info">
                        <div class="sbox">
                            <div class="sbox-title">Payment</div>
                            <div class="sbox-content">
                                {!! Form::open(array('url'=>'sximo/config/payment', 'class'=>'form-horizontal row')) !!}
                                <div class="col-sm-6 animated fadeInRight ">

                                    <div class="form-group ">
                                        <label  class=" control-label col-md-4">Trial Days </label>
                                        <div class="col-md-8">
                                            {!! Form::text('trial',CNF_TRIAL,['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label  class="control-label col-md-4">Payment</label>
                                        <div class="col-md-8">
                                            <select class="form-control input-sm" name="payment">
                                                <option value="paypal" @if(CNF_PAYMENT=='paypal')selected="selected"@endif>Paypal</option>
                                                <option value="stripe" @if(CNF_PAYMENT=='stripe')selected="selected"@endif>Stripe</option>
                                                <option value="both" @if(CNF_PAYMENT=='both')selected="selected"@endif>Both</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group ">
                                        <label  class=" control-label col-md-4">Stripe API</label>
                                        <div class="col-md-8">
                                            {!! Form::text('stripe_api',CNF_STRIPE_API_KEY,['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label  class=" control-label col-md-4">Stripe Client ID</label>
                                        <div class="col-md-8">
                                            {!! Form::text('stripe_client_id',CNF_STRIPE_CLIENT_ID,['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>



                                    <div class="form-group ">
                                        <label  class=" control-label col-md-4">Paypal Business Account </label>
                                        <div class="col-md-8">
                                            {!! Form::text('paypal_business',CNF_PAYPAL_BUSINESS,['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label  class=" control-label col-md-4">Paypal Mode</label>
                                        <div class="col-md-8">
                                            <select class="form-control input-sm" name="paypal_mode">
                                                <option value="sandbox" @if(CNF_PAYPAL_MODE=='sandbox')selected="selected"@endif>Sandbox</option>
                                                <option value="live" @if(CNF_PAYPAL_MODE=='live')selected="selected"@endif>Live</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label  class=" control-label col-md-4">Alert Days for Sponsorship</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control input-sm" name="sponsorship_alert_days" value="{{ CNF_SPONSORSHIP_ALERT_DAYS }}">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-8">
                                            <button class="btn btn-primary" type="submit">{{ Lang::get('core.sb_savechanges') }} </button>
                                        </div>
                                    </div>


                                </div>


                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

@stop