    @extends('layouts.parent')



    <!-- head -->

    @section('head_section')

    @include('shared.head')

    <link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">

    <style>

        .dataTables_filter{
            display:none!important;
        }
        .q{
            color:#000;
            margin-bottom:20px;
        }
        .img{
            max-width:100%!important;
            border: solid 1px #fff;
            border-radius:20px;
            margin-bottom: 20px;
        }
        .lht{
            color:#1e88e5!important;  
        }
        .fnavs{
            font-size:13px;
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

                <li class="breadcrumb-item active">My Subjects</li>

            </ol>

        </div>

        <div class="col-md-7 col-12 align-self-center d-none d-md-block">

            <div class="d-flex mt-2 justify-content-end">

                <div class="d-flex mr-3 ml-2">

                    <div class="chart-text mr-2">

                        <a href="#" data-toggle="modal" data-target="#new_none_modal" class="btn btn-info"><i class="mdi mdi-library-plus"></i> Add Subject</a>

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

            <div class="col-lg-3 col-md-12">

                <div class="card blog-widget">

                    <div class="card-body">

                        <h4 class="card-title">Quick Links</h4>

                        <div class="list-group"> 

                            <a href="javascript:void(0)" class="list-group-item">Navigation</a>

                             <!-- semi-nav -->

                             <a href="{{ route('teacher.lessons') }}" class="list-group-item">My Lessons</a>

                            <a href="{{ route('teacher.subjects') }}" class="list-group-item active">My Subjects</a>

                            <a href="{{ route('teacher.assignments') }}" class="list-group-item">Topical Questions</a>

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
                            <div><h3 class="card-title">{{$subject}} Forums</h3></div>
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
                                <h6 class="card-subtitle">All questions asked by students across {{$subject}} topics</a></h6>
                                @if(count($forums))
                                @foreach( $forums as $_forum )
                                    <div class="jumbotron">
                                        <h3 class="card-title" style="margin-bottom:40px;"><span class="q"><i class="mdi mdi-forum"> </i> {{$_forum['question']}}</span></h3>
                                    @if(!is_null($_forum['q_image']))
                                        <img class="img-responsive img" src="{{route('admin.securefinder', ['file' => $_forum['q_image']])}}" alt="question image"/>
                                    @endif
                                        <span class="btn btn-default btn-lg fnavs"><i class="mdi mdi-account lht"></i> By {{ucwords(strtolower(explode(' ', App\User::find($_forum['asked_by'])->name)[0]))}} </span>
                                        
                                        <span class="btn btn-default btn-lg fnavs"><i class="mdi mdi-clock lht"></i> {{date('M jS, Y', strtotime($_forum['created_at']))}}</span>

                                        <span class="btn btn-default btn-lg fnavs"><i class="mdi mdi-book lht"></i> {{ucwords(strtolower(App\Curriculum::find($_forum['topic'])->topic))}}</span>

                                        <!-- <span data-toggle="modal" data-target="#new_lesson_modal"  -->
                                        <span onclick="modalOpener({{$_forum['id']}})"
                                        class="btn btn-primary btn-lg pull-right"><i class="glyphicon glyphicon-envelope"></i> Post Answer</span>
                                    </div>
                                    <hr>
                                @endforeach
                                @else
                                No questions asked yet. Check back later
                                @endif
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
                        <form class="mt-4" enctype="multipart/form-data" method="POST" action="{{ route('teacher.fanswer') }}">
                            @csrf
                            <!-- row bgn -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label for="answer">Your Answer</label>
                                        <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" id="answer"></textarea>
                                        <span class="bar"></span>
                                        @error('answer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="a_image">Upload Image if any</label>
                                        <input type="file" name="a_image" class="form-control" id="a_image">
                                        <input type="hidden" name="question_id" id="question_id"/>
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
    <script>
    $( document ).ready(function() {
        modalOpener = function (forumid){
            $("#question_id").val(forumid);
            $("#new_lesson_modal").modal("show");
        }
    });
    </script>
    @stop