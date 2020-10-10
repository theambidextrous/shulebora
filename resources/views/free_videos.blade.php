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
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- Row -->
        <div class="row">
            <!-- Column -->
            <div class="col-lg-12 col-xlg-12 col-md-12">
                <div class="card">
                <a class="nav-link active tm-lg" id="pills-timeline-tab" data-toggle="pill" href="#current-month" role="tab" aria-controls="pills-timeline" aria-selected="true"><i class="mdi mdi-book-open-page-variant"></i> Free Videos </a>
                <div id="accordion" class="custom-accordion mb-2 mt-3 accordion-class">
                @if(count($lessons))
                @php($flow=0)
                @foreach($lessons as $_sub )
                    @php($show = 'showh')
                    @if($flow > 0 )
                        @php($show = '')
                    @endif
                    @php( $today = date('Y-m-d H') )
                    @php( $topic = App\Curriculum::find($_sub['topic']) )
                    <!-- recorded -->
                    <div class="card mb-1">
                        <div class="card-header accod-header" id="headingOne">
                            <h5 class="m-0">
                                <a class="custom-accordion-title d-block pt-2 pb-2"     data-toggle="collapse" href="#collapseOne{{$_sub['id']}}" aria-expanded="true" aria-controls="collapseOne{{$_sub['id']}}">
                                {{App\Subject::find($topic->subject)->name}} >
                                <span style="">
                                    {{$topic->topic}} >
                                </span>
                                <span style="color: #a2a1a1;">
                                    {{ucwords(strtolower($_sub['sub_topic']))}}
                                </span>
                                <span class="float-right"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseOne{{$_sub['id']}}" class="collapse {{$show}}" aria-labelledby="headingOne"
                            data-parent="#accordion">
                            <div class="card-body">
                                <h6 class="card-subtitle">Introduction</h6>
                                <p class="text-justify">{{ucwords( strtolower($_sub['introduction']) )}}</p>
                                @if($_sub['video_content'] != 'n/a')
                                <video id="my-video" class="video-js vjs-big-play-centered" controls preload="auto" poster="{{asset('icons/shulebora-leaners.jpg')}}" data-setup="{}">
                                    <source src="{{route('finder',['file'=>$_sub['video_content']])}}" type="video/mp4" />
                                    <source src="{{route('finder',['file'=>$_sub['video_content']])}}" type="video/webm" />
                                    <p class="vjs-no-js">
                                        To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                    </p>
                                </video>
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