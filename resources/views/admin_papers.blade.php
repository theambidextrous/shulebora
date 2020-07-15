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
                <li class="breadcrumb-item active">Revision Papers</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <div class="d-flex mt-2 justify-content-end">
                <div class="d-flex mr-3 ml-2">
                    <div class="chart-text mr-2">
                        <a href="#" data-toggle="modal" data-target="#new_paper_modal" class="btn btn-info"><i class="mdi mdi-library-plus"></i> Add Paper</a>
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
            <!-- <div class="col-lg-3 col-md-12">
                <div class="card blog-widget">
                    <div class="card-body">
                        <h4 class="card-title">Quick Links</h4>
                        <div class="list-group"> 
                            <a href="javascript:void(0)" class="list-group-item active">Navigation</a>
                            <a href="{{ route('admin.students') }}" class="list-group-item">All Students</a>
                            <a href="{{ route('admin.forms') }}" class="list-group-item">All Forms</a>
                            <a href="{{ route('admin.classes') }}" class="list-group-item">All Classes</a>
                            <a href="{{ route('admin.groups') }}" class="list-group-item">Level Groups</a>
                            <a href="{{ route('admin.subjects') }}" class="list-group-item">All Subjects</a>
                            <a href="{{ route('admin.teachers') }}" class="list-group-item">All Teachers</a>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-lg-12 col-md-12">
                <div class="card blog-widget">
                    <div class="card-body">
                        <div class="d-flex flex-wrap">
                            <div><h3 class="card-title">{{$p_title??'All Revision Papers'}}</h3></div>
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
                                <h6 class="card-subtitle">List of all Revision papers accross all subjects</a></h6>
                                <div class="table-responsive">
                                    <table style="width:100%!important;" id="file_export" class="table table-striped table-bordered display">
                                        <thead>
                                            <tr>
                                                <th>Teacher</th>
                                                <th>Subject</th>
                                                <th>Title</th>
                                                <th>Paper</th>
                                                <th>Paid/Free</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($papers))
                                            @foreach( $papers as $paper)
                                            @php($kind = ($paper['is_paid']==1)?'PAID':'FREE')
                                            @php($file_c = route('admin.securefinder', ['file'=>$paper['file_content']]) )
                                            <tr>
                                                <td>{{App\User::find($paper['created_by'])->name}}</td>
                                                <td>{{App\Subject::find($paper['subject'])->name}}</td>
                                                <td>{{$paper['title']}}</td>
                                                <td>
                                                    @if($paper['file_content'] == 'n/a')
                                                    {{$paper['file_content']}}
                                                    @else
                                                    <a href="{{$file_c}}" target="_blank">view</a>
                                                    @endif
                                                </td>
                                                <td>{{$kind}}</td>
                                                <td><a href="{{ url('shule/bora/admin/papers/' . $paper['id']) }}" class="btn btn-link"><i class="mdi mdi-table-edit"></i>Manage</a></td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Teacher</th>
                                                <th>Subject</th>
                                                <th>Title</th>
                                                <th>Paper</th>
                                                <th>Paid/Free</th>
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
        <!-- Row -->
        <!-- ===================================================================== -->
        <!-- page_modals -->
        <!-- Signup modal content -->
        <div id="new_paper_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <form class="floating-labels mt-4" enctype="multipart/form-data" method="POST" action="{{ route('admin.add_paper') }}">
                            @csrf
                            <!-- row bgn -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" data-toggle="tooltip" data-placement="bottom" title="title here e.g. " value="{{ old('name') }}" required autocomplete="title" autofocus="title">
                                        <span class="bar"></span>
                                        <label for="title">Paper Name</label>
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <select name="subject" id="subject" class="form-control select2 no-float" autofocus >
                                            <option value="nn">Select Subject</option>
                                            @if(count($subjects))
                                            @foreach($subjects as $tpc)
                                            <option value="{{$tpc['id']}}">{{$tpc['name']}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                     <div class="form-group mb-2">
                                        <select name="teacher" id="teacher" class="form-control select2 no-float" autofocus >
                                            <option value="nn">Select Teacher</option>
                                            @if(count($teachers))
                                            @foreach($teachers as $trc)
                                            <option value="{{$trc['id']}}">{{$trc['name']}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <select name="category" id="category" class="form-control select2 no-float" autofocus >
                                            <option value="nn">Select Category</option>
                                            <option value="PAID">Paid</option>
                                            <option value="FREE">Free</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-5">
                                        <!-- <label for="file_content">Upload revision paper file here </label> -->
                                        <input type="file" name="file_content" id="file_content" class="form-control" autofocus="file_content"/>
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
    @stop