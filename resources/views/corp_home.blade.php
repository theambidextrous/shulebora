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
            <li class="breadcrumb-item"><a href="{{ route('corporate') }}">Shule Bora</a></li>
            <li class="breadcrumb-item active">Corporates</li>
        </ol>
    </div>
</div>
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- Row profile-->
    <div class="row">
        <!-- Column -->
        <!-- <div class="col-lg-4 col-xlg-4 col-md-4">
            <div class="card">
                <div class="card-body">
                    <center class="mt-0"> <img src="{{asset('icons/avatar.png')}}" class="rounded-circle" width="50" />
                        <h4 class="card-title mt-1">{{Auth::user()->school}}</h4>
                        <h6 class="card-subtitle">Corporate Account</h6>
                        <div class="row text-center justify-content-md-center">
                            <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-notebook"></i> <font class="font-medium">{{count($mylessons)}}</font></a></div>
                            <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-book-open"></i> <font class="font-medium">{{count($lessons)}}</font></a></div>
                        </div>
                    </center>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xlg-4 col-md-4">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Contact person </small>
                    <h6>{{Auth::user()->name}}</h6>
                    <small class="text-muted">Email address </small>
                    <h6>{{Auth::user()->email}}</h6>
                    <small class="text-muted pt-4 db">Phone</small>
                    <h6>{{Auth::user()->phone}}</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xlg-4 col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mt-1">My Subscription</h4>
                    <small class="text-muted">Package </small>
                    <h6>Corporate account</h6>
                    <small class="text-muted pt-4 db">Date Subscribed</small>
                    <h6>{{date('d/m/Y', strtotime(Auth::user()->created_at))}}</h6>
                </div>
            </div>
        </div> -->
        <!-- end Column -->
    </div>
    <!-- End Row --profiel -->
    <!-- Row -->
    <div class="row">
        <!-- Column -->
        <div class="col-lg-12 col-xlg-12 col-md-12">
            <div class="card">
            <a class="nav-link active tm-lg" id="pills-timeline-tab" data-toggle="pill" href="#current-month" role="tab" aria-controls="pills-timeline" aria-selected="true"><i class="mdi mdi-shopping"></i> My Purchases</a>
            <div id="accordion" class="custom-accordion mb-4 accordion-class">
                <div class="card-body" style="max-height:150px overflow-y:auto;">
                    @if(count($mylessons))
                    @php($flow=0)
                    @foreach($mylessons as $_mysub )
                        <!-- time strngs -->
                        @php( $today = date('Y-m-d H') )
                        @php( $zoom_time = explode('T', $_mysub['zoom_time']) )
                        @php( $zoom_h = explode(':', $zoom_time[1])[0] )
                        @php( $zoom_day = date('Y-m-d', strtotime($zoom_time[0])) )
                        @php( $str = $zoom_time[0].' '.$zoom_h.':00' )
                        @php( $class_hour = date('Y-m-d H', strtotime($str)) )
                        @php( $new_class_h = date('Y-m-d H', strtotime('+3 hours', strtotime($class_hour.':00'))) )
                        <!-- end time strings -->
                        
                        @php($string_  = explode('T',$_mysub['zoom_time']))
                        @if( $today > $new_class_h )
                            <h6>{{$_mysub['subjects']}} > <b>Expired!</b></h6>
                            <small class="text-muted">{{$_mysub['topics']}}</small><br>
                            <small class="text-muted">Time: {{date('M jS, Y', strtotime($string_[0]))}} {{date('h:i a', strtotime($string_[1]))}}</small><br>
                            <hr>
                        @else
                            <h6><a href="#"> Corporate Session </a> > <a href="#">{{$_mysub['subjects']}}</a> >  {{$_mysub['topics']}}</h6>
                            <small style="font-size:15px;color:#d33!important;" class="text-muted">Time: {{date('M jS, Y', strtotime($string_[0]))}} {{date('h:i a', strtotime($string_[1]))}}</small><br>
                            <a style="background-color:#d33!important;border:solid 1px #d33;" class="btn btn-danger" target="_blank" href="{{$_mysub['zoom_link']}}">click here when it is time</a>
                            <hr>
                        @endif
                        @php($flow++)
                    @endforeach
                    @endif
                </div>
            </div> <!-- end custom accordions-->
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-12 col-xlg-12 col-md-12">
            <div class="card">
                <a class="nav-link active tm-lg" id="pills-timeline-tab" data-toggle="pill" href="#current-month" role="tab" aria-controls="pills-timeline" aria-selected="true"><i class="mdi mdi-calendar-clock"></i> Upcoming Live Sessions</a>
                <div id="accordion" class="custom-accordion mb-4 accordion-class">
                    <div class="card-body">
                        @if(count($lessons))
                        @php($flow=0)
                        @foreach($lessons as $_sub )
                            <!-- time strngs -->
                            @php( $today = date('Y-m-d H') )
                            @php( $zoom_time = explode('T', $_sub['zoom_time']) )
                            @php( $zoom_h = explode(':', $zoom_time[1])[0] )
                            @php( $zoom_day = date('Y-m-d', strtotime($zoom_time[0])) )
                            @php( $str = $zoom_time[0].' '.$zoom_h.':00' )
                            @php( $class_hour = date('Y-m-d H', strtotime($str)) )
                            @php( $new_class_h = date('Y-m-d H', strtotime('+3 hours', strtotime($class_hour.':00'))) )
                            <!-- end time strings -->
                            @if( $today > $new_class_h )
                                <!-- do nothing -->
                            @else
                                @php($string_  = explode('T',$_sub['zoom_time']))
                                <form method="post" action="{{route('corp_order')}}">
                                @csrf
                                <h6><a href="#"> Corporate Session </a> > <a href="#">{{trim($_sub['subjects'])}}</a> >  {{trim($_sub['topics'])}}</h6>
                                <input type="hidden" name="orderid" value="{{session('order')}}"/>
                                <input type="hidden" name="lesson" value="{{$_sub['id']}}"/>
                                <input type="hidden" name="cost" value="{{floor($_sub['price'])}}"/>
                                <small class="text-muted">Session: {{$_sub['subjects']}}</small><br>
                                <small class="text-muted">Time: {{date('M jS, Y', strtotime($string_[0]))}} {{date('h:i a', strtotime($string_[1]))}}</small><br>
                                <h6>KES {{floor($_sub['price'])}}</h6><br>
                                <button class="btn btn-info" type="submit">Buy this</button>
                                <hr>
                                </form>
                            @endif
                            @php($flow++)
                        @endforeach
                        @endif
                    </div>
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
<!-- <script src="{{ asset('dist/js/pages/calendar/cal-init.js') }}"></script> -->
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