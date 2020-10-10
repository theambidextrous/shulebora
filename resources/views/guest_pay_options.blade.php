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

        <div class="col-md-5">

            <div class="card" style="background:transparent;">

                <div class="card-header info-header">Payment Method</div>

                <div class="card-body">

                    <div id="data"></div>

                    <div style="display:none;" id="action"><a href="{{route('learner')}}" class="btn btn-info">Click here to Complete</a></div>

                    <br><br>

                    @if(session('mpesa'))

                    <div class="alert alert-info">Dear customer, A payment notification has been send to your phone. Just enter PIN to complete order.</div>
                        <!--<div class="alert alert-info">{{session('mpesa.b')}}</div>

                        <div class="alert alert-info">{{session('mpesa.c')}}</div> -->

                    @endif

                    @if(session('instructions'))

                        <!-- <div class="alert alert-info">{!! session('instructions') !!}</div> -->

                    @endif

                    @if(isset($error))

                        <div class="alert alert-danger">{{$error}}</div>

                    @endif

                    <div class="row">

                    @if(session('full_order'))

                        @php($order = session('full_order'))

                    <!-- pack card-->

                    <!-- form -->

                    <form class="mt-2 col-md-12" method="POST" action="{{route('pay')}}">

                    @csrf

                    <input type="hidden" name="orderid" value="{{$order['orderid']}}" id="orderid"/>

                    <input type="hidden" name="cost" value="{{$order['cost']}}" id="cost"/>

                    <div class="card text-center">

                        <div class="card-body">

                            <p class="text-center aboutscroll">

                                <label><img src="{{asset('icons/mpesa.png')}}" alt="mpesa"/></label><br>

                                <label>Enter your Mpesa phone</label>

                                <input required type="number" placeholder="0705040302" name="phone" class="form-control mt-2" id="phone"/>

                            </p>

                        </div>

                        <div class="card-body">

                            <div class="form-group text-center">

                                <button class="btn btn-info" type="submit">Pay Now</button>

                            </div>

                        </div>

                    </div>

                    <!-- end pack card -->

                    </form>

                    <!-- end form -->

                    @else

                    <p class="text-center">Payment timed out

                    <a style="padding-left:4px;" href="{{route('buy')}}">  Try again Here</a></p>

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

<script>

    $(document).ready(function(){

        s_event = function(){

            var source = new EventSource("<?=route('status_check', ['order' => session('order')])?>");

            source.onmessage = function(event){

                res = JSON.parse(event.data)

                $('#data').html(res.data);

                if(res.status === 0){

                    $('#action').show();

                }

            }

        }

        setTimeout(() => {

            s_event()

        }, 15000);//240000

    });

</script>

@stop

