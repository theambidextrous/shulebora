    @extends('layouts.parent')

    <!-- head -->
    @section('head_section')
    @include('shared.head')
    <link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <style>
        .dataTables_filter{
            display:none!important;
        }
    </style>
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
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">welcome to {{config('app.name')}}</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="#">Shule Bora</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- Row -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card blog-widget">
                    <div class="card-body">
                        <div class="d-flex flex-wrap">
                            <div><h3 class="card-title">Update Profile</h3></div>
                        </div>
                        <!-- row start -->
                        <div class="row">
                            <div class="col-sm-12">
                            <form class="mt-4" enctype="multipart/form-data" method="POST" action="#">
                                @csrf
                                <!-- row bgn -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label for="name">Name </label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" data-toggle="tooltip"
                                                data-placement="bottom" title="name here e.g. Divisibilty test" value="{{ $user['name'] }}" required autocomplete="name">
                                            <span class="bar"></span>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label for="email">Email </label>
                                            <input readonly type="text" name="email" class="form-control @error('email') is-invalid @enderror" id="email" data-toggle="tooltip"
                                                data-placement="bottom" title="email here e.g. Divisibilty test" value="{{ $user['email'] }}" required autocomplete="email">
                                            <span class="bar"></span>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label for="phone">Phone </label>
                                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" data-toggle="tooltip"
                                                data-placement="bottom" title="phone here e.g. Divisibilty test" value="{{ $user['phone'] }}" required autocomplete="phone">
                                            <span class="bar"></span>
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label for="gender">Gender </label>
                                            <input readonly type="text" name="gender" class="form-control @error('gender') is-invalid @enderror" id="gender" data-toggle="tooltip"
                                                data-placement="bottom" title="gender here e.g. Divisibilty test" value="{{ $user['gender'] }}" required autocomplete="gender">
                                            <span class="bar"></span>
                                            @error('gender')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label for="school">School </label>
                                            <input type="text" name="school" class="form-control @error('school') is-invalid @enderror" id="school" data-toggle="tooltip"
                                                data-placement="bottom" title="school here e.g. Divisibilty test" value="{{ $user['school'] }}" required autocomplete="school">
                                            <span class="bar"></span>
                                            @error('school')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                                <div class="form-group text-center">
                                    <a class="btn btn-link" data-dismiss="modal">Back</a>
                                    <button class="btn btn-info" type="button">Update</button>
                                </div>
                            </form>
                            </div> <!-- end col-->
                        </div>
                        <!-- end row-->
                    </div>
                </div>
            </div>
        </div>
        <!-- Row -->
    </div>
    @stop

    <!-- aside section -->
    @section('aside_section')
    @include('shared.aside')
    @stop

    <!-- foot section -->
    @section('foot_section')
    <script src="{{asset('assets/libs/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- apps -->
    <script src="{{ asset('dist/js/app.min.js') }}"></script>
    <script src="{{ asset('dist/js/app.init.horizontal.js') }}"></script>
    <script src="{{ asset('dist/js/app-style-switcher.horizontal.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('dist/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    <!--This page plugins -->
    <script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
    <!-- start - This is for export functionality only -->
    <script src="{{ asset('assets/dt/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/dt/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/dt/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/dt/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/dt/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/dt/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/dt/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/datatable/datatable-advanced.init.js') }}"></script>
    @stop