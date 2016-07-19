<div class="header-wrapper background-black-light">
    <div class="container">
        <div class="header-inner">
            <div class="header-logo">
                <a href="{{ url('') }}">
                    @if(file_exists(public_path().'/sximo/images/'.CNF_FRONT_LOGO) && CNF_FRONT_LOGO !='')
                        <img src="{{ asset('sximo/images/'.CNF_FRONT_LOGO)}}" alt="{{ CNF_APPNAME }}" height="50px" />
                    @else
                        <img src="{{ asset('sximo/images/logo.svg')}}" alt="{{ CNF_APPNAME }}" height="50px" />
                    @endif

                    <span>{{ CNF_APPNAME }}</span>
                </a>

            </div><!-- /.header-logo -->


            <div class="header-content">

                <div class="header-bottom">
                    <div class="header-action">
                        {{--<a href="listing-submit.html" class="header-action-inner" title="Add Listing" data-toggle="tooltip" data-placement="bottom">--}}
                            {{--<i class="fa fa-plus"></i>--}}
                        {{--</a><!-- /.header-action-inner -->--}}
                    </div><!-- /.header-action -->

                    <ul class="header-nav-primary nav nav-pills collapse navbar-collapse">

                        <li >
                            <a href="#"> Account<i class="fa fa-cogs fa-2"></i></a>
                            <ul class="sub-menu">
                                @if(!Auth::check())
                                    <li><a href="{{ URL::to('user/login') }}"><span class="fa fa-sign-in"></span> {{ Lang::get('core.signin') }}</a></li>
                                    <li><a href="{{ URL::to('user/register') }}"><i class="fa fa-user"></i> {{ Lang::get('core.signup') }}</a></li>
                                @else
                                    <li><a href="{{ URL::to('user/profile') }}"><span class="fa fa-user"></span> {{ Lang::get('core.m_myaccount') }}</a></li>
                                    <li><a href="{{ URL::to('dashboard') }}"><span class="fa fa-desktop"></span> {{ Lang::get('core.m_dashboard') }}</a></li>
                                    <li><a href="{{ URL::to('user/logout') }}"><span class="fa  fa-sign-out"></span> {{ Lang::get('core.m_logout') }}</a></li>
                                @endif
                            </ul>
                        </li>
                    </ul>

                    <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".header-nav-primary">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                </div><!-- /.header-bottom -->
            </div><!-- /.header-content -->
        </div><!-- /.header-inner -->
    </div><!-- /.container -->
</div><!-- /.header-wrapper -->
