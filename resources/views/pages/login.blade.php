@extends('layouts.auth')

@section('body')
    <body class="vertical-layout vertical-menu 1-column  bg-full-screen-image blank-page blank-page" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-lg-4 col-md-6 col-10 box-shadow-2 p-0">
                            <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                                <div class="card-header border-0">
                                    <div class="text-center mb-1">
                                        <img src="{{ asset('theme/app-assets/images/logo/logo-square-80x80.png') }}" alt="branding logo">
                                    </div>
                                    <div class="font-large-1  text-center">
                                        Admin Login
                                    </div>
                                </div>
                                <div class="card-content">
                                    @if(Session::has('success_message'))
                                        <div class="alert alert-success">
                                            <button class="close" data-close="alert"></button>
                                            <span> {{Session::get('success_message')}} </span>
                                        </div>
                                    @endif
                                    @if(Session::has('error_message'))
                                        <div class="alert alert-danger">
                                            <button class="close" data-close="alert"></button>
                                            <span> {{Session::get('error_message')}} </span>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <form class="form-horizontal" method="post" action="{{ route('admin-login') }}" >
                                            @csrf
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control round" name="email" placeholder="Your Email Address" required>
                                                <div class="form-control-position">
                                                    <i class="ft-user"></i>
                                                </div>
                                            </fieldset>
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="password" class="form-control round"  name="password" placeholder="Enter Password" required>
                                                <div class="form-control-position">
                                                    <i class="ft-lock"></i>
                                                </div>
                                            </fieldset>
                                            <div class="form-group row">
                                                <div class="col-md-6 col-12 text-center text-sm-left">

                                                </div>
                                            </div>
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1">Login</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>

    </body>

@endsection
