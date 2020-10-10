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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header info-header">Join ShuleBora Digital</div>
                <div class="card-body">
                    <form class="floating-labels mt-4" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <!-- name -->
                                <div class="form-group mb-4">
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" data-toggle="tooltip"
                                        data-placement="bottom" title="your name e.g. Gregory Juma" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    <span class="bar"></span>
                                    <label for="name">Full Name</label>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                                <!-- end name -->
                                <!-- email -->
                                <div class="form-group mb-4">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" data-toggle="tooltip"
                                        data-placement="bottom" title="your email e.g. gregory.juma@gmail.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
                                        data-placement="bottom" title="choose a nice password" value="{{ old('password') }}" required autocomplete="password" autofocus>
                                    <span class="bar"></span>
                                    <label for="password">Choose Password</label>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                                <!-- end password -->
                                <!-- confirm pass -->
                                <div class="form-group mb-4">
                                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" data-toggle="tooltip"
                                        data-placement="bottom" title="re-enter your password" value="{{ old('password_confirmation') }}" required autocomplete="password_confirmation" autofocus>
                                    <span class="bar"></span>
                                    <label for="password_confirmation">Confirm Password</label>
                                </div>
                                <!-- end confirm pass -->
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-2">
                                <button type="submit" class="btn btn-info">
                                    Join ShuleBora
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
