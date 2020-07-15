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
        <div class="col-md-9">
            <div class="card">
                <div class="card-header info-header">Create ShuleBora Account</div>
                <div class="card-body">
                    <!-- form -->
                    <form class="floating-labels mt-4" method="POST" action="{{ route('student_create') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <!-- input -->
                                <div class="form-group mb-5">
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" data-toggle="tooltip"
                                        data-placement="bottom" title="Name here e.g. Gregory Juma" value="{{ old('name') }}" required autocomplete="name" autofocus="name">
                                    <span class="bar"></span>
                                    <label for="name">Full Name</label>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- end input -->
                                <!-- input -->
                                <div class="form-group mb-5">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" data-toggle="tooltip"
                                        data-placement="bottom" title="email here " value="{{ old('email') }}" required autocomplete="email" autofocus="email">
                                    <span class="bar"></span>
                                    <label for="email">Email Address</label>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- end input -->
                                <!-- input -->
                                <div class="form-group mb-5">
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" data-toggle="tooltip"
                                        data-placement="bottom" title="phone here " value="{{ old('phone') }}" required autocomplete="phone" autofocus="phone">
                                    <span class="bar"></span>
                                    <label for="phone">Phone Number</label>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- end input -->
                                <!-- input -->
                                <div class="form-group mb-5">
                                    <select name="gender" id="gender" class="form-control select2 no-float" autofocus >
                                        <option value="nn">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <!-- end input -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-5">
                                    <select name="class_form" id="class_form" class="form-control select2 no-float" autofocus >
                                        <option value="nn">Select Class/Form</option>
                                        @if(count($forms))
                                        @foreach($forms as $frm)
                                        <option value="is_h~{{$frm['id']}}">{{$frm['name']}}</option>
                                        @endforeach
                                        @endif
                                        @if(count($classes))
                                        @foreach($classes as $cls)
                                        <option value="is_p~{{$cls['id']}}">{{$cls['name']}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <!-- input -->
                                <div class="form-group mb-5">
                                    <input type="text" name="school" class="form-control @error('school') is-invalid @enderror" id="school" data-toggle="tooltip"
                                        data-placement="bottom" title="your current school" value="{{ old('school') }}" required autocomplete="school" autofocus="school">
                                    <span class="bar"></span>
                                    <label for="school">School Name</label>
                                    @error('school')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- end input -->
                                <!-- input -->
                                <div class="form-group mb-5">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" data-toggle="tooltip"
                                        data-placement="bottom" title="default password here" value="" required autocomplete="password">
                                    <span class="bar"></span>
                                    <label for="password">Set Password</label>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- end input -->
                                <div class="form-group mb-5">
                                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" data-toggle="tooltip"
                                        data-placement="bottom" title="default password_confirmation here" value="" required autocomplete="password_confirmation">
                                    <span class="bar"></span>
                                    <label for="password_confirmation">Confirm Password</label>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-info" type="submit">Create Account</button>
                        </div>
                    </form>
                    <!-- end form -->
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
