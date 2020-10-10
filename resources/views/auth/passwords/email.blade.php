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
                <div class="card-header info-header">Reset ShuleBora Account Password</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form class="floating-labels mt-4" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <!-- email -->
                                <div class="form-group mb-5">
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
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info">
                                    Send Password Reset Link
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
