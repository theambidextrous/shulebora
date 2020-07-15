    @extends('layouts.parent')

    <!-- head -->
    @section('head_section')
    @include('shared.head')
    <link href="{{asset('css/video-js.css')}}" rel="stylesheet" />
    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <script src="{{asset('js/videojs-ie8.min.js')}}"></script>
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
                <li class="breadcrumb-item"><a href="{{ route('learner') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('learner') }}">{{$topic['topic']}}</a></li>
                <li class="breadcrumb-item active">Lessons</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <div class="d-flex mt-2 justify-content-end">
                <div class="d-flex ml-2">
                    <div class="chart-text mr-2">
                        <a class="btn btn-info btn-lg" href="{{route('learner')}}"><i class="mdi mdi-skip-backward"> </i> Go Back Subject List</a>
                    </div>
                </div>
            </div>
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
            <div class="col-lg-12 col-xlg-12 col-md-12">
                <div class="card">
                <a class="nav-link active tm-lg" id="pills-timeline-tab" data-toggle="pill" href="#current-month" role="tab" aria-controls="pills-timeline" aria-selected="true"><i class="mdi mdi-book-open-page-variant"></i> {{ucwords(strtolower($topic['topic']))}}</a>
                <div id="accordion" class="custom-accordion mb-2 mt-3 accordion-class">
                @if(count($lessons))
                @php($flow=0)
                @foreach($lessons as $_sub )
                    @php($show = 'showh')
                    @if($flow > 0 )
                        @php($show = '')
                    @endif
                    @php( $today = date('Y-m-d H') )
                    @if($_sub['type'] == 'LIVE')
                    @php( $zoom_time = explode('T', $_sub['zoom_time']) )
                    @php( $zoom_h = explode(':', $zoom_time[1])[0] )
                    @php( $zoom_day = date('Y-m-d', strtotime($zoom_time[0])) )
                    @php( $str = $zoom_time[0].' '.$zoom_h.':00' )
                    @php( $class_hour = date('Y-m-d H', strtotime($str)) )
                    @php( $new_class_h = date('Y-m-d H', strtotime('+3 hours', strtotime($class_hour.':00'))) )
                    @php( $time = date('h:i a', strtotime($zoom_time[1])) )
                    <!-- live -->
                    <div class="card mb-1">
                        <div class="card-header accod-header" id="headingOne">
                            <h5 class="m-0">
                                <a class="custom-accordion-title d-block pt-2 pb-2"     data-toggle="collapse" href="#collapseOne{{$_sub['id']}}" aria-expanded="true" aria-controls="collapseOne{{$_sub['id']}}">{{ucwords(strtolower($_sub['sub_topic']))}}
                                
                                @if( $today > $new_class_h )
                                <span class="live-class"><i class="mdi mdi-webcam"></i>Live Class</span>
                                <span class="live-class-time-expired"><i class="mdi mdi-clock-end"></i>expired</span>
                                @else
                                <span class="live-class"><i class="mdi mdi-webcam"></i>Live Class</span>
                                <span class="live-class-time"><i class="mdi mdi-clock-out"></i>Get ready</span>
                                @endif
                                <span class="float-right"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseOne{{$_sub['id']}}" class="collapse {{$show}}" aria-labelledby="headingOne"
                            data-parent="#accordion">
                            <div class="card-body">
                                <h6 class="card-subtitle">Live Class Time</h6>
                                <p class="text-justify">
                                    {{date('M jS, Y', strtotime($zoom_time[0]))}} At
                                    {{date('h:i a', strtotime($zoom_time[1]))}}
                                </p>
                                <h6 class="card-subtitle">A note from teacher </h6>
                                <p class="text-justify">{{$_sub['zoom_help_note']}}</p>
                                
                                <h6 class="card-subtitle">Live Class Link</h6>
                                <p class="text-justify"> click on the link <a target="_blank" href="{{$_sub['zoom_link']}}">{{$_sub['zoom_link']}}</a></p>
                           </div>
                        </div>
                    </div> <!-- end card-->
                    @else
                    <!-- recorded -->
                    <div class="card mb-1">
                        <div class="card-header accod-header" id="headingOne">
                            <h5 class="m-0">
                                <a class="custom-accordion-title d-block pt-2 pb-2"     data-toggle="collapse" href="#collapseOne{{$_sub['id']}}" aria-expanded="true" aria-controls="collapseOne{{$_sub['id']}}">{{ucwords(strtolower($_sub['sub_topic']))}}<span class="float-right"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseOne{{$_sub['id']}}" class="collapse {{$show}}" aria-labelledby="headingOne"
                            data-parent="#accordion">
                            <div class="card-body">
                                <h6 class="card-subtitle">Introduction</h6>
                                <p class="text-justify">{{ucwords( strtolower($_sub['introduction']) )}}</p>
                                @if($_sub['file_content'] != 'n/a')
                                <h6 class="card-subtitle">PDF/WORD notes</h6>
                                <p class="text-justify">
                                    <a href="{{route('admin.securefinder',['file'=>$_sub['file_content']])}}" target="_blank" download><i class="mdi mdi-file-pdf"></i> Download</a>
                                </p>
                                @endif
                                @if($_sub['video_content'] != 'n/a')
                                <video id="my-video" class="video-js vjs-big-play-centered" controls preload="auto" poster="{{asset('icons/shulebora-leaners.jpg')}}" data-setup="{}">
                                    <source src="{{route('admin.securefinder',['file'=>$_sub['video_content']])}}" type="video/mp4" />
                                    <source src="{{route('admin.securefinder',['file'=>$_sub['video_content']])}}" type="video/webm" />
                                    <p class="vjs-no-js">
                                        To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                    </p>
                                </video>
                                @endif
                                @if($_sub['audio_content'] != 'n/a')
                                <h6 class="card-subtitle">Watch video</h6>
                                <p class="text-justify">
                                    <a href="{{route('admin.securefinder',['file'=>$_sub['audio_content']])}}" target="_blank" download><i class="mdi mdi-file-video"></i> Download</a>
                                </p>
                                @endif
                           </div>
                        </div>
                    </div> <!-- end card-->
                    @endif
                    @php($flow++)
                @endforeach
                @endif
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
    <script src="{{asset('js/video.js')}}"></script>
    <!-- <script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script> -->
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