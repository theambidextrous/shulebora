@extends('layouts.parent')

<!-- head -->
@section('head_section')
@include('shared.head')
@stop

<!-- top nav section  -->
@section('topnav_section')
@include('shared.topnav')
@stop

<!-- main navigation -->
@section('main_nav_section')
@include('shared.main_nav')
@stop

<!-- view content -->
@section('content_section')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header info-header">Login to ShuleBora Account</div>
                <div class="card-body">
                    <form class="floating-labels mt-4" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <!-- email -->
                                <div class="form-group mb-4">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" data-toggle="tooltip"
                                        data-placement="bottom" title="your email here" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    <span class="bar"></span>
                                    <label for="email">Email Address</label>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                                <!-- end email -->
                            </div>
                            <div class="col-md-12">
                                <!-- password -->
                                <div class="form-group mb-4">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" data-toggle="tooltip"
                                        data-placement="bottom" title="your password here" value="{{ old('password') }}" required autocomplete="password" autofocus>
                                    <span class="bar"></span>
                                    <label for="password">Password</label>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                                <!-- end password -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-check mb-4">
                                    <input class="material-inputs" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info">
                                    Login Now
                                </button>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

<!-- aside section -->
@section('aside_section')
@include('shared.aside')
@stop

<!-- foot section -->
@section('foot_section')
@include('shared.foot')
@stop
