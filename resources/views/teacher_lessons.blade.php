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
                <li class="breadcrumb-item"><a href="{{ route('teacher') }}">Teacher</a></li>
                <li class="breadcrumb-item active">My Lessons</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <div class="d-flex mt-2 justify-content-end">
                <div class="d-flex mr-3 ml-2">
                    <div class="chart-text mr-2">
                        <a href="#" data-toggle="modal" data-target="#new_lesson_modal" class="btn btn-info"><i class="mdi mdi-library-plus"></i> Add Lesson</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- Row -->
        <div class="row">
            <!-- Column -->
            <!--  -->
            <div class="col-lg-12 col-md-12">
                <div class="card blog-widget">
                    <div class="card-body">
                        <div class="d-flex flex-wrap">
                            <div><h3 class="card-title">{{$p_title??'My Lessons'}}</h3></div>
                        </div>
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
                            <!-- datatable -->
                            <div class="card">
                            <div class="card-body" style="width:100%!important;">
                                <h6 class="card-subtitle">List of my lessons  across all of my subjects</a></h6>
                                <div class="table-responsive">
                                    <table style="width:100%!important;" id="file_export" class="table table-striped table-bordered display">
                                        <thead>
                                            <tr>
                                                <th>Topic</th>
                                                <th>Type</th>
                                                <th>Sub topic</th>
                                                <th>File Upload</th>
                                                <th>Video Upload</th>
                                                <th>Zoom Link</th>
                                                <th>Zoom Time</th>
                                                <th>Kind</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($lessons))
                                            @foreach( $lessons as $lesson)
                                            @php($kind = ($lesson['is_paid']==1)?'PAID':'FREE')
                                            @php($file_c = route('admin.securefinder', ['file'=>$lesson['file_content']]) )
                                            @php($video_c = route('admin.securefinder', ['file'=>$lesson['video_content']]) )
                                            @php($audio_c = route('admin.securefinder', ['file'=>$lesson['audio_content']]) )
                                            <tr>
                                                <td>{{App\Curriculum::find($lesson['topic'])->topic}}</td>
                                                <td>{{$lesson['type']}}</td>
                                                <td>{{$lesson['sub_topic']}}</td>
                                                <td>
                                                    @if($lesson['file_content'] == 'n/a')
                                                    {{$lesson['file_content']}}
                                                    @else
                                                    <a href="{{$file_c}}" target="_blank">view</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($lesson['video_content'] == 'n/a')
                                                    {{$lesson['video_content']}}
                                                    @else
                                                    <a href="{{$video_c}}" target="_blank">view</a>
                                                    @endif
                                                </td>
                                                <td>{{$lesson['zoom_link']}}</td>
                                                <td>{{$lesson['zoom_time']}}</td>
                                                <td>{{$kind}}</td>
                                                <td><a href="{{ url('shule/bora/teacher/lessons/' . $lesson['id']) }}" class="btn btn-link"><i class="mdi mdi-table-edit"></i>Manage</a></td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Topic</th>
                                                <th>Type</th>
                                                <th>Sub topic</th>
                                                <th>File Upload</th>
                                                <th>Video Upload</th>
                                                <th>Zoom Link</th>
                                                <th>Zoom Time</th>
                                                <th>Kind</th>
                                                <th>Manage</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            <!-- end datatable -->
                            </div>
                            </div><!-- inner card  -->
                        </div>
                        <!-- end row-->
                    </div>
                </div>
            </div>
        </div>
        <!-- Signup modal content -->
        <div id="new_lesson_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center mt-2 mb-4">
                            <a href="{{route('school')}}" class="text-success">
                                <span>
                                    <img src="{{asset('icons/shulebora-logo.png')}}" alt="" height="60">
                                </span>
                            </a>
                        </div>
                        <form class="mt-4" enctype="multipart/form-data" method="POST" action="{{ route('teacher.add_lesson') }}">
                            @csrf
                            <!-- row bgn -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <select name="topic" id="topic" class="form-control select2 no-float" autofocus >
                                            <option value="nn">Select Topic</option>
                                            @if(count($topics))
                                            @foreach($topics as $tpc)
                                            <option value="{{$tpc['id']}}">{{$tpc['topic']}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <select name="type" id="type" class="form-control select2 no-float" autofocus >
                                            <option value="nn">Select Lesson Type</option>
                                            <option value="LIVE">Live</option>
                                            <option value="RECORDED">Recorded</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="sub_topic">Sub topic </label>
                                        <input type="text" name="sub_topic" class="form-control @error('sub_topic') is-invalid @enderror" id="sub_topic" data-toggle="tooltip"
                                            data-placement="bottom" title="sub_topic here e.g. Divisibilty test" value="{{ old('sub_topic') }}" required autocomplete="sub_topic">
                                        <span class="bar"></span>
                                        @error('sub_topic')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-2">
                                        <select name="category" id="category" class="form-control select2 no-float" autofocus >
                                            <option value="nn">Select Category</option>
                                            <option value="PAID">Paid</option>
                                            <option value="FREE">Free</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="introduction">Lesson Introduction </label>
                                        <input type="text" name="introduction" class="form-control @error('introduction') is-invalid @enderror" id="introduction" data-toggle="tooltip"
                                            data-placement="bottom" title="introduction here e.g. Divisibilty test" value="{{ old('introduction') }}" required autocomplete="introduction">
                                        <span class="bar"></span>
                                        @error('introduction')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label for="file_content">Upload file(pdf/ppt/doc) </label>
                                        <input type="file" name="file_content" class="form-control @error('file_content') is-invalid @enderror" id="file_content" data-toggle="tooltip"
                                            data-placement="bottom" title="file_content here" value="{{ old('file_content') }}"  autocomplete="file_content">
                                        <span class="bar"></span>
                                        @error('file_content')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="audio_content" value="n/a"/>
                                     <div class="form-group mb-4">
                                        <label for="video_content">Upload video(mp4,avi,mp3) </label>
                                        <input type="file" name="video_content" class="form-control @error('video_content') is-invalid @enderror" id="video_content" data-toggle="tooltip"
                                            data-placement="bottom" title="video_content here" value="{{ old('video_content') }}"  autocomplete="video_content">
                                        <span class="bar"></span>
                                        @error('video_content')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label for="zoom_link">Zoom Link(for live lesson) </label>
                                        <input type="text" name="zoom_link" class="form-control @error('zoom_link') is-invalid @enderror" id="zoom_link" data-toggle="tooltip"
                                            data-placement="bottom" title="zoom link here" value="{{ old('zoom_link') }}" placeholder="only if class type is live">
                                        <span class="bar"></span>
                                        @error('zoom_link')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="zoom_time">Zoom Lesson Time </label>
                                        <input type="datetime-local" name="zoom_time" class="form-control @error('zoom_time') is-invalid @enderror" id="zoom_time" data-toggle="tooltip"
                                            data-placement="bottom" title="zoom time here" value="{{ old('zoom_time') }}">
                                        <span class="bar"></span>
                                        @error('zoom_time')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="zoom_help_note">Zoom Note to learners </label>
                                        <input type="datetime" name="zoom_help_note" class="form-control @error('zoom_help_note') is-invalid @enderror" id="zoom_help_note" data-toggle="tooltip"
                                            data-placement="bottom" title="zoom note here" value="{{ old('zoom_help_note') }}" placeholder="for live lessons">
                                        <span class="bar"></span>
                                        @error('zoom_help_note')
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
                                <button class="btn btn-info" type="submit">Create</button>
                            </div>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- end -->
        <!-- ====================================================================== -->
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