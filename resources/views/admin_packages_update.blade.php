    @extends('layouts.parent')

    <!-- head -->
    @section('head_section')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="shule bora digital">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/images/favicon.png')}}">
    <title>{{$title ?? config('app.name')}}</title>
    <link rel="canonical" href="{{url('/')}}" />
    <!-- This page plugin CSS -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/select2/dist/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <!-- Custom CSS -->
    <link href="{{asset('dist/css/style.min.css')}}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
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
                <li class="breadcrumb-item"><a href="{{ route('school') }}">Shule Bora</a></li>
                <li class="breadcrumb-item"><a href="{{ route('school') }}">Admin</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.packages') }}">Packages</a></li>
                <li class="breadcrumb-item active">{{$package['name']}}</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <div class="d-flex mt-2 justify-content-end">
                <div class="d-flex mr-3 ml-2">
                    <div class="chart-text mr-2">
                        <a href="{{ route('admin.packages') }}" class="btn btn-info"><i class="mdi mdi-library-plus"></i> Packages</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-lg-3 col-md-12">
                <div class="card blog-widget">
                    <div class="card-body">
                        <h4 class="card-title">Quick Links</h4>
                        <div class="list-group"> 
                            <a href="javascript:void(0)" class="list-group-item active">Navigation</a>
                             <!-- semi-nav -->
                            <a href="{{ route('admin.students') }}" class="list-group-item">All Students</a>
                            <a href="{{ route('admin.forms') }}" class="list-group-item">All Forms</a>
                            <a href="{{ route('admin.classes') }}" class="list-group-item">All Classes</a>
                            <a href="{{ route('admin.groups') }}" class="list-group-item">Level Groups</a>
                            <a href="{{ route('admin.subjects') }}" class="list-group-item">All Subjects</a>
                            <a href="{{ route('admin.teachers') }}" class="list-group-item">All Teachers</a>
                            <!-- end semi-nav -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="card blog-widget">
                    <div class="card-body">
                        <div class="d-flex flex-wrap">
                            <div><h3 class="card-title">Manage Packages</h3></div>
                        </div>
                        @if ( Session::has('flag') && Session::get('flag') == 1)
                            <div class="alert alert-success">{{Session::get('msg')}}</div>
                        @elseif ( Session::has('flag') && Session::get('flag') == 2)
                            <div class="alert alert-warning">{{Session::get('msg')}}</div>
                        @elseif ( Session::has('flag') && Session::get('flag') == 3)
                            <div class="alert alert-danger">{{Session::get('msg')}}</div>
                        @endif
                        @if(isset($flag)&&$flag==1)
                            <div class="alert alert-success">{{$msg}}</div>
                        @elseif(isset($flag)&&$flag==2)
                            <div class="alert alert-warning">{{$msg}}</div>
                        @elseif(isset($flag)&&$flag==3)
                            <div class="alert alert-danger">{{$msg}}</div>
                        @endif
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <!-- row start -->
                        <div class="row">
                            <div class="card">
                            <div class="card-body" style="width:100%!important;">
                                <!-- tabs -->
                                <ul class="nav nav-tabs mb-3">
                                    <li class="nav-item">
                                        <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                            <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Edit Package</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#profile" data-toggle="tab" aria-expanded="true" class="nav-link">
                                            <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Manage Access</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link">
                                            <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Stats</span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane show active" id="home">
                                        <!-- form -->
                                        <br>
                                        <form class="floating-labels mt-4" enctype="multipart/form-data" method="POST" action="{{ route('admin.update_package',['id'=>$package['id']]) }}">
                                            @csrf
                                            <!-- row bgn -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4">
                                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" data-toggle="tooltip" data-placement="bottom" title="name here e.g. " value="{{ $package['name'] }}" required autocomplete="name" autofocus="name">
                                                        <span class="bar"></span>
                                                        <label for="name">Package Name</label>
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4">
                                                        <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" id="description" data-toggle="tooltip" data-placement="bottom" title="description here e.g. " value="{{ $package['description'] }}" required autocomplete="description" autofocus="description">
                                                        <span class="bar"></span>
                                                        <label for="description">Package Description</label>
                                                        @error('description')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4">
                                                        <input type="text" name="addons" class="form-control @error('addons') is-invalid @enderror" id="addons" data-toggle="tooltip" data-placement="bottom" title="addons here e.g. " value="{{ $package['addons'] }}" required autocomplete="addons" autofocus="addons">
                                                        <span class="bar"></span>
                                                        <label for="addons">Package Addons(comma list)</label>
                                                        @error('addons')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4">
                                                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" id="price" data-toggle="tooltip" data-placement="bottom" title="price here e.g. " value="{{ $package['price'] }}" required autocomplete="price" autofocus="price">
                                                        <span class="bar"></span>
                                                        <label for="price">Package Price</label>
                                                        @error('price')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4">
                                                        <input type="number" name="max_usage" class="form-control @error('max_usage') is-invalid @enderror" id="max_usage" data-toggle="tooltip" data-placement="bottom" title="max usage here e.g. " value="{{ $package['max_usage'] }}" required autocomplete="max_usage" autofocus="max_usage">
                                                        <span class="bar"></span>
                                                        <label for="max_usage">Package Maximum Users</label>
                                                        @error('max_usage')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end row -->
                                            <div class="form-group text-center">
                                                <button class="btn btn-info" type="submit">Save changes</button>
                                            </div>
                                        </form>
                                        <!-- end form -->
                                    </div>
                                    <div class="tab-pane" id="profile">
                                        <!-- form -->
                                        <br>
                                       
                                        <!-- end form -->
                                    </div>
                                    <div class="tab-pane" id="settings">
                                    </div>
                                </div>
                                <!-- tabs -->
                            </div>
                            </div><!-- inner card  -->
                        </div>
                        <!-- end row-->
                    </div>
                </div>
            </div>
        </div>
        <!-- Row -->
        <!-- ===================================================================== -->
        <!-- page_modals -->
        <!-- end -->
        <!-- ====================================================================== -->
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
    <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/forms/select2/select2.init.js') }}"></script>
    <!-- start - This is for export functionality only -->
    <script src="{{ asset('assets/dt/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/dt/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/dt/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/dt/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/dt/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/dt/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/dt/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/datatable/datatable-advanced.init.js') }}"></script>
    <script src="{{asset('assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script>
    $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
    var radioswitch = function() {
        var bt = function() {
            $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioState")
            }), $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck")
            }), $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck", !1)
            })
        };
        return {
            init: function() {
                bt()
            }
        }
    }();
    $(document).ready(function() {
        radioswitch.init()
    });
    </script>
    @stop