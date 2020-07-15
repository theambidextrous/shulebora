<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    @yield('head_section')
    <style>
        .info-header{
            color: #1e88e5;
            background-color: #eaeaea;
            border:solid 1px #eaeaea;
            font-size:18px;
            font-weight:600;
            text-align:center;
        }
        .hidden-class{
            display:none;
        }
        .btn-link{
            color:#1e88e5!important;
        }
        .logo-bar{
            /* margin-left: -8%;
            background-color:#fff!important; */
        }
        .tm-lg{
            font-size:18px;
        }
        .page-titles > .col-md-5 > .text-themecolor{
            color:#dd3333!important;
            margin-bottom:10px!important;
        }
        .topic_btn{
            color: white;
            width: 130px;
            padding: 5px 0;
            border-radius: 8px;
        }
        .accordion-class{
            margin: 0 15px 0 15px;
        }
        .live-class{
            color: orange;
            padding-left: 3%;
            font-size: 14px;
            /* font-weight: 100; */
        }
        .live-class-time{
            color: #1e49e5;
            font-size: 16px;
            font-weight: 100;
        }
        .live-class-time-expired{
            color: #acacaf;
            font-size: 13px;
            font-weight: 100;
            font-style: italic;
        }
        .accod-header{
            padding: 2px 8px!important;
        }
        .card{
            width:100%!important;
        }
        .select2,.select2-container,.select2-container--default,.select2-container--below,.select2-container--focus{
            width:100%!important;
        }
        .modal-h2{
            color:#1e88e5!important;
        }
        .form-control[readonly] {
            padding: 10px 10px 10px 10px !important;
            border-radius: 4px!important;
        }
        .no-float{
            padding: 0 0 0 0!important;
        }
        .auth-links{
            margin-right: 5px;
            line-height: 30px!important;
            height: 34px!important;
            padding: 0 .75rem!important;
            font-size: 19px!important;
            color: #1e88e5!important;
            background-color: #fff!important;
            border-color: #fff!important;
            text-align: center!important;
            vertical-align: middle!important;
            cursor: pointer!important;
            user-select: none!important;
            border: 1px solid transparent!important;
            border-radius: 4px!important;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
    </style>
</head>
<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            @yield('topnav_section')
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        @yield('main_nav_section')
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- Start Container fluid  -->
            @yield('content_section')
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer">
                © {{date('Y')}} {{config('app.name')}}
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
         <!-- ============================================================== -->
        <!-- End Wrapper -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- customizer Panel -->
        <!-- ============================================================== -->
    @yield('aside_section')
    <div class="chat-windows"></div>
    <!-- ============================================================== -->
    @yield('foot_section')
</body>

</html>