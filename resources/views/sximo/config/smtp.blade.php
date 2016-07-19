@extends('layouts.app')
@section('content')

    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                  <h3><i class="fa fa-envelope-square"></i>  SMTP Configuration</h3>
            </div>


            <ul class="breadcrumb">
                <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
                <li><a href="{{ URL::to('config') }}">SMTP Configuration</a></li>
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
                            <div class="sbox-title">SMTP Configuration</div>
                            <div class="sbox-content">
                                {!! Form::open(array('url'=>'sximo/config/smtp', 'class'=>'form-horizontal row')) !!}
                                <div class="col-sm-6 animated fadeInRight ">

                                    <div class="form-group ">
                                        <label  class=" control-label col-md-4">System Mail Address </label>
                                        <div class="col-md-8">
                                            {!! Form::text('smtp_username',SMTP_USERNAME,['class' => 'form-control input-sm', 'required' => 'true' ,'parsley-type'=>'email' ]) !!}
                                        </div>
                                    </div>




                                    <div class="form-group ">
                                        <label  class=" control-label col-md-4">Password for System Mail</label>
                                        <div class="col-md-8">
                                            <input type="password" name="smtp_password" class="form-control input-sm">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label  class=" control-label col-md-4">Mail Driver</label>
                                        <div class="col-md-8">
                                            <input type="text" name="mail_driver" class="form-control input-sm" value="{{ MAIL_DRIVER }}">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label  class=" control-label col-md-4"> Mail Host</label>
                                        <div class="col-md-8">
                                            <input type="text" name="mail_host" class="form-control input-sm" value="{{ MAIL_HOST }}">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label  class=" control-label col-md-4">Mail Port</label>
                                        <div class="col-md-8">
                                            <input type="text" name="mail_port" class="form-control input-sm" value="{{ MAIL_PORT }}">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label  class=" control-label col-md-4">Mail Encryption</label>
                                        <div class="col-md-8">
                                            <input type="text" name="mail_encryption" class="form-control input-sm" value="{{ MAIL_ENCRYPTION }}">
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