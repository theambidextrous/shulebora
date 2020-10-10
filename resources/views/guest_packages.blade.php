@extends('layouts.parent')



<!-- head -->

@section('head_section')

@include('shared.head')

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

    <div class="row justify-content-center">

        <div class="col-md-9">

            <div class="card" style="background:transparent;">

                <div class="card-header info-header">Select Package</div>

                <div class="card-body">

                    <div class="row">

                    @if(count($packages))

                    @foreach( $packages as $package )

                    <!-- pack card-->

                    <!-- form -->

                    <form class="mt-2 col-md-4" method="POST" action="{{route('order')}}">

                    @csrf

                    <input type="hidden" name="package" value="{{$package['id']}}" id="package"/>

                    <input type="hidden" name="orderid" value="{{$orderid}}" id="orderid"/>

                    <input type="hidden" name="cost" value="{{$package['price']}}" id="price"/>

                    <div class="card text-center">

                        <div class="card-body">

                            <div class="d-flex flex-row">

                                <div class="pl-3">

                                    <h3 class="font-weight-medium">{{$package['name']}}</h3>

                                    <h6>Duration: {{$package['max_usage']}} month(s)</h6>

                                    <button class="btn btn-success"><i class="ti-check"></i>

                                    KES {{number_format($package['price'],0)}}

                                </button>

                                </div>

                            </div>

                            <div class="mt-2 text-center hidjden-class">

                                <ul class="list-style-none">

                                @php($addons = explode(',', $package['addons']))

                                @if(count($addons))

                                @foreach( $addons as $addon )

                                <li>{{ucwords(strtolower($addon))}}<hr style="margin:0px;"></li>

                                @endforeach

                                @endif

                                </ul>

                            </div>

                        </div>

                        <div class="card-body">

                            <p class="text-center aboutscroll">

                                {{$package['description']}}

                            </p>

                            <ul class="list-style-none list-icons d-flex flex-item text-center pt-2">

                                

                            </ul>

                        </div>

                        <div class="card-body">

                            <div class="form-group text-center">

                                <button class="btn btn-info" type="submit">Buy Package</button>

                            </div>

                        </div>

                    </div>

                    <!-- end pack card -->

                    </form>

                    <!-- end form -->

                    @endforeach

                    @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

@stop



<!-- aside section -->

@section('aside_section')

@include('shared.aside')

@stop



<!-- foot section -->

@section('foot_section')

@include('shared.foot')

@stop

