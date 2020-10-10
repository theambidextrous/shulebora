@extends('layouts.parent')

<!-- head -->
@section('head_section')
@include('shared.head')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
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
        <div class="col-lg-5 col-xlg-5 col-md-5">
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
        <div class="col-lg-7 col-xlg-7 col-md-7">
            <div class="card">
                <a class="nav-link active tm-lg" id="pills-timeline-tab" data-toggle="pill" href="#current-month" role="tab" aria-controls="pills-timeline" aria-selected="true"><i class="mdi mdi-chart-areaspline"></i> My Payments</a>
                <div id="accordion" class="custom-accordion mb-4 accordion-class">
                <div class="card-body">
                <div class="table-responsive">
                    <table style="width:100%!important;" id="file_export" class="table table-striped table-bordered display">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Package</th>
                                <th>Cost</th>
                                <th>Paid</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($accounts))
                        @foreach( $accounts as $account )
                            <tr>
                                <td>{{$account['orderid']}}</td>
                                <td>{{ucwords(strtolower(App\Package::find($account['package'])->name))}}</td>
                                <td>KES {{$account['cost']}}</td>
                                <td>KES {{$account['paid_amount']}}</td>
                                <td>{{date('M jS, Y',strtotime($account['created_at']))}}</td>
                            </tr>
                        @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Order Number</th>
                                <th>Package</th>
                                <th>Cost</th>
                                <th>Paid</th>
                                <th>Date</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <!-- end datatable -->
                </div>
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
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/calendar/cal-init.js') }}"></script>
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