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
                <div class="card-header info-header">Change ShuleBora Account Password</div>
                <div class="card-body">
                    <form class="floating-labels mt-4" method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row">
                            <div class="col-md-12">
                                <!-- email -->
                                <div class="form-group mb-5">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" data-toggle="tooltip"
                                        data-placement="bottom" title="your email"  value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
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
                                <div class="form-group mb-5">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" data-toggle="tooltip"
                                        data-placement="bottom" title="new password here" value="{{ old('password') }}" required autocomplete="new-password" autofocus>
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
                            <div class="col-md-12">
                                <!-- cpassword -->
                                <div class="form-group mb-5">
                                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" data-toggle="tooltip"
                                        data-placement="bottom" title="new password here" value="{{ old('password_confirmation') }}" required autocomplete="new-password" autofocus>
                                    <span class="bar"></span>
                                    <label for="password_confirmation">Confirm Password</label>
                                </div>
                                <!-- end cpassword -->
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info">
                                    Reset Password
                                </button>
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
