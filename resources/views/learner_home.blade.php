    @extends('layouts.parent')

    <!-- head -->
    @section('head_section')
    @include('shared.head')
    <link href="{{asset('assets/libs/fullcalendar/dist/fullcalendar.min.css')}}" rel="stylesheet">
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
                <li class="breadcrumb-item"><a href="{{ route('learner') }}">Shule Bora</a></li>
                <li class="breadcrumb-item active">Student home</li>
            </ol>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    @php($group = Auth::user()->group)
    @php($level = Auth::user()->level)
    @php($subjects = App\Subject::where('form_or_class', $level)->where('is_active', true)->where('is_what', $group)->get()->toArray())
    @php($subjects_count = count($subjects))
    @if( $group == 2 )
        @php($f = App\Form::find($level))
    @elseif($group == 1)
        @php($f = App\Gclass::find($level))
    @endif
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- Row -->
        <div class="row">
            <!-- Column -->
            <div class="col-lg-3 col-xlg-3 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <center class="mt-0"> <img src="{{asset('icons/avatar.png')}}" class="rounded-circle" width="80" />
                            <h4 class="card-title mt-2">{{Auth::user()->name}}({{$f->alias}})</h4>
                            <h6 class="card-subtitle">{{Auth::user()->school}}</h6>
                            <div class="row text-center justify-content-md-center">
                                <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-notebook"></i> <font class="font-medium">{{$subjects_count}}</font></a></div>
                                <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-book-open"></i> <font class="font-medium">{{$f->name}}</font></a></div>
                            </div>
                        </center>
                    </div>
                    <div>
                        <hr>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">Email address </small>
                        <h6>{{Auth::user()->email}}</h6>
                        <small class="text-muted pt-4 db">Phone</small>
                        <h6>{{Auth::user()->phone}}</h6>
                        <small class="text-muted pt-4 db">Address</small>
                        <h6>N/A</h6>
                        <hr>
                        <a class="btn btn-success" href="{{route('profile')}}">Update Profile</a>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-3 col-xlg-3 col-md-3">
                <div class="card">
                <a class="nav-link active tm-lg" id="pills-timeline-tab" data-toggle="pill" href="#current-month" role="tab" aria-controls="pills-timeline" aria-selected="true"><i class="icon-notebook"></i> My Subjects</a>
                <div id="accordion" class="custom-accordion mb-4 accordion-class">
                @if(count($subjects))
                @php($flow=0)
                @foreach($subjects as $_sub )
                    @php($show = 'showj')
                    @if($flow > 0 )
                        @php($show = '')
                    @endif
                    <div class="card mb-1">
                        <div class="card-header accod-header" id="headingOne">
                            <h5 class="m-0">
                                <a class="custom-accordion-title d-block pt-2 pb-2"     data-toggle="collapse" href="#collapseOne{{$_sub['id']}}" aria-expanded="true" aria-controls="collapseOne{{$_sub['id']}}">{{$_sub['name']}}<span class="float-right"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseOne{{$_sub['id']}}" class="collapse {{$show}}" aria-labelledby="headingOne"
                            data-parent="#accordion">
                            <div class="card-body">
                                @php($topics = App\Curriculum::where('subject', $_sub['id'])->orderBy('required_lessons')->get()->toArray())
                                <h4 class="card-title">Topics/Curricula</h4>
                                @if(count($topics))
                                    <ul class="list-style-none">
                                    @foreach( $topics as $atopic )
                                    <li>
                                        <i class="ti-angle-right"></i> {{$atopic['topic']}}
                                        <a class="btn btn-link topic_btn" href="{{route('learner.topic_lesson', ['topic' => $atopic['id']])}}">View Lessons</a>
                                    </li>
                                    @endforeach
                                    </ul>
                                @else
                                No subject listed yet
                                @endif
                            </div>
                        </div>
                    </div> <!-- end card-->
                    @php($flow++)
                @endforeach
                @endif
                </div> <!-- end custom accordions-->
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-6 col-xlg-6 col-md-6">
                <div class="card">
                    <a class="nav-link active tm-lg" id="pills-timeline-tab" data-toggle="pill" href="#current-month" role="tab" aria-controls="pills-timeline" aria-selected="true"><i class="mdi mdi-calendar-clock"></i> Live Class Calendar</a>
                    <div id="accordion" class="custom-accordion mb-4 accordion-class">
                        <!-- cal -->
                        <div class="card-body b-l calender-sidebar">
                            <div id="calendar"></div>
                        </div>
                        <!-- cal end -->
                    </div> <!-- end custom accordions-->
                </div>
            </div>
            <!-- Column -->
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
    <script src="{{asset('assets/extra-libs/taskboard/js/jquery-ui.min.js')}}"></script>
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
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/calendar/cal-init.js') }}"></script>
    <!-- start - This is for export functionality only -->
    <!-- <script src="{{ asset('assets/dt/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/dt/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/dt/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/dt/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/dt/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/dt/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/dt/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/datatable/datatable-advanced.init.js') }}"></script> -->
    @stop