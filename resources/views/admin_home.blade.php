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
                <li class="breadcrumb-item"><a href="{{ route('school') }}">Shule Bora</a></li>
                <li class="breadcrumb-item active">Admin home</li>
            </ol>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div
                                class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                <i class="mdi mdi-account-multiple"></i>
                            </div>
                            <div class="ml-2 align-self-center">
                                <h3 class="mb-0 font-weight-light">1600</h3>
                                <h5 class="text-muted mb-0">Enrolled Learners</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div
                                class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                <i class="mdi mdi-cellphone-link"></i></div>
                            <div class="ml-2 align-self-center">
                                <h3 class="mb-0 font-weight-light">2000+</h3>
                                <h5 class="text-muted mb-0">Video Content</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div
                                class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                <i class="mdi mdi-web"></i></div>
                            <div class="ml-2 align-self-center">
                                <h3 class="mb-0 font-weight-light">15,000+</h3>
                                <h5 class="text-muted mb-0">Online Lessons</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div
                                class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                <i class="mdi mdi-nature-people"></i></div>
                            <div class="ml-2 align-self-center">
                                <h3 class="mb-0 font-weight-light">10+</h3>
                                <h5 class="text-muted mb-0">Expert Teachers</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
        <!-- Row -->
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
                            <div><h3 class="card-title">Recent Activities</h3></div>
                        </div>
                        <!-- row start -->
                        <div class="row">
                            <div class="col-sm-3 mb-2 mb-sm-0">
                                <div class="nav flex-column nav-pills" id="h-pills-tab" role="tablist"
                                    aria-orientation="horizontal">
                                    <a class="nav-link active show" id="v-pills-home-tab" data-toggle="pill"
                                        href="#v-pills-home" role="tab" aria-controls="v-pills-home"
                                        aria-selected="true">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">Recent Students</span>
                                    </a>
                                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill"
                                        href="#v-pills-profile" role="tab" aria-controls="v-pills-profile"
                                        aria-selected="false">
                                        <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">Recent Lessons</span>
                                    </a>
                                    <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill"
                                        href="#v-pills-settings" role="tab" aria-controls="v-pills-settings"
                                        aria-selected="false">
                                        <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">Recent Teachers</span>
                                    </a>
                                </div>
                            </div> <!-- end col-->
                            <div class="col-sm-9">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel"
                                        aria-labelledby="v-pills-home-tab">
                                        <!-- tab cont -->
                                        <div class="table-responsive">
                                            <table style="height:0px!important;" id="_show_hide_col" class="table table-striped table-bordered "
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Group</th>
                                                        <th>Level</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Tiger Nixon</td>
                                                        <td>Secondary</td>
                                                        <td>F1</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Well Amorny</td>
                                                        <td>Primary</td>
                                                        <td>C6</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- end tab cont -->
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                        aria-labelledby="v-pills-profile-tab">
                                        <!-- tab cont -->
                                        <div class="table-responsive">
                                            <table style="height:0px!important;" id="" class="table table-striped table-bordered display "
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Group</th>
                                                        <th>Level</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Tiger Nixon</td>
                                                        <td>Secondary</td>
                                                        <td>F1</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Well Amorny</td>
                                                        <td>Primary</td>
                                                        <td>C6</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- end tab cont -->
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                                        aria-labelledby="v-pills-settings-tab">
                                        <!-- tab cont -->
                                        <div class="table-responsive">
                                            <table style="height:0px!important;" id="" class="table table-striped table-bordered display "
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Group</th>
                                                        <th>Level</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Tiger Nixon</td>
                                                        <td>Secondary</td>
                                                        <td>F1</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Well Amorny</td>
                                                        <td>Primary</td>
                                                        <td>C6</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- end tab cont -->
                                    </div>
                                </div> <!-- end tab-content-->
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