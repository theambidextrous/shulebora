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
                <li class="breadcrumb-item"><a href="{{ route('teacher') }}">Shule Bora</a></li>
                <li class="breadcrumb-item active">Teacher home</li>
            </ol>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
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
                             <a href="{{ route('teacher.lessons') }}" class="list-group-item">My Lessons</a>
                            <a href="{{ route('teacher.subjects') }}" class="list-group-item">My Subjects</a>
                            <a href="{{ route('teacher.assignments') }}" class="list-group-item">Assignments</a>
                            <a href="{{ route('teacher.papers') }}" class="list-group-item">Revision Papers</a>
                            <!-- end semi-nav -->

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="card blog-widget">
                    <div class="card-body">
                        <div class="d-flex flex-wrap">
                            <div><h3 class="card-title">Recent Lessons</h3></div>
                        </div>
                        <!-- row start -->
                        <div class="row">
                            <div class="col-sm-12">
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