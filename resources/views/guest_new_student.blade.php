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

                <div class="card-header info-header">Create ShuleBora Account</div>

                <div class="card-body">

                    <!-- form -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    <form class="floating-labels mt-4" method="POST" action="{{ route('student_create') }}">

                        @csrf

                        <div class="row">

                            <div class="col-md-12">

                                <!-- input -->
                                <div class="form-group mb-4">
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
                                <div class="form-group mb-4">
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" data-toggle="tooltip" data-placement="bottom" title="phone here " value="{{ old('phone') }}" required autocomplete="phone" autofocus="phone">
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
                                <div class="form-group mb-4">
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
                               <!-- end input -->
                               <!-- county -->
                               <div class="form-group mb-4">
                                    <select name="school" id="school" class="form-control select2 no-float" autofocus >
                                        <option value="nn">Select your County</option>
                                        <option value="Turkana">Turkana</option>
                                        <option value="Marsabit">Marsabit</option>
                                        <option value="Mandera">Mandera</option>
                                        <option value="Wajir">Wajir</option>
                                        <option value="West Pokot">West Pokot</option>
                                        <option value="Samburu">Samburu</option>
                                        <option value="Isiolo">Isiolo</option>
                                        <option value="Baringo">Baringo</option>
                                        <option value="Trans Nzoia">Trans Nzoia</option>
                                        <option value="Bungoma">Bungoma</option>
                                        <option value="Garissa">Garissa</option>
                                        <option value="Uasin Gishu">Uasin Gishu</option>
                                        <option value="Kakamega">Kakamega</option>
                                        <option value="Laikipia">Laikipia</option>
                                        <option value="Busia">Busia</option>
                                        <option value="Meru">Meru</option>
                                        <option value="Nandi">Nandi</option>
                                        <option value="Siaya">Siaya</option>
                                        <option value="Nakuru">Nakuru</option>
                                        <option value="Vihiga">Vihiga</option>
                                        <option value="Nyandarua">Nyandarua</option>
                                        <option value="Kericho">Kericho</option>
                                        <option value="Kisumu">Kisumu</option>
                                        <option value="Nyeri">Nyeri</option>
                                        <option value="Tana River">Tana River</option>
                                        <option value="Kitui">Kitui</option>
                                        <option value="Kirinyaga">Kirinyaga</option>
                                        <option value="Embu">Embu</option>
                                        <option value="Homa Bay">Homa Bay</option>
                                        <option value="Bomet">Bomet</option>
                                        <option value="Nyamira">Nyamira</option>
                                        <option value="Narok">Narok</option>
                                        <option value="Kisii">Kisii</option>
                                        <option value="Muranga">Muranga</option>
                                        <option value="Migori">Migori</option>
                                        <option value="Kiambu">Kiambu</option>
                                        <option value="Machakos">Machakos</option>
                                        <option value="Kajiado">Kajiado</option>
                                        <option value="Nairobi">Nairobi</option>
                                        <option value="Makueni">Makueni</option>
                                        <option value="Lamu">Lamu</option>
                                        <option value="Kilifi">Kilifi</option>
                                        <option value="Taita Taveta">Taita Taveta</option>
                                        <option value="Kwale">Kwale</option>
                                        <option value="Mombasa">Mombasa</option>
                                        <option value="Elgeyo Marakwet">Elgeyo Marakwet</option>
                                        <option value="Tharaka Nithi">Tharaka Nithi</option>
                                    </select>
                                </div>
                               <!-- end county -->
                               <div class="form-group mb-4">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" data-toggle="tooltip" data-placement="bottom" title="default password here" value="" required autocomplete="password">
                                    <span class="bar"></span>
                                    <label for="password">Set Password</label>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- end input -->

                                <div class="form-group mb-4">
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

