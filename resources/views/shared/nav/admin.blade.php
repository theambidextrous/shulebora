<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Home</span></li>
<li class="sidebar-item">
    <a class="sidebar-link waves-effect waves-dark" href="{{ route('school') }}" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a>
</li>

<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Accounts</span></li>
<li class="sidebar-item">
    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-chart-areaspline"></i><span class="hide-menu">Accounts</span></a>
    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin.packages') }}"><i class="mdi mdi-collage"></i><span class="hide-menu">Package Types</span></a></li>
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin.payments') }}"><i class="mdi mdi-collage"></i><span class="hide-menu">All Payments</span></a></li>
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin.failed_payments') }}"><i class="mdi mdi-collage"></i><span class="hide-menu">Failed Payments</span></a></li>
    </ul>
</li>

<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">People</span></li>
<li class="sidebar-item">
    <a class="sidebar-link has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">People</span></a>
    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin.teachers') }}" aria-expanded="false"><i class="mdi mdi-collage"></i><span class="hide-menu">All Teachers</span></a></li>
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin.students') }}" aria-expanded="false"><i class="mdi mdi-collage"></i><span class="hide-menu">All Students</span></a></li>
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin.high_student') }}" aria-expanded="false"><i class="mdi mdi-collage"></i><span class="hide-menu">High School Students</span></a></li>
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin.prim_student') }}" aria-expanded="false"><i class="mdi mdi-receipt"></i><span class="hide-menu">Primary Students</span></a></li>
    </ul>
</li>

<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Subjects</span></li>
<li class="sidebar-item">
    <a class="sidebar-link has-arrow waves-effect waves-dark" href="{{ route('admin.subjects') }}" aria-expanded="false"><i class="mdi mdi-book-open-page-variant"></i><span class="hide-menu">Subjects</span></a>
    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin.subjects') }}" aria-expanded="false"><i class="mdi mdi-collage"></i><span class="hide-menu">All</span></a></li>
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin.high_subject') }}" aria-expanded="false"><i class="mdi mdi-collage"></i><span class="hide-menu">High School</span></a></li>
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin.prim_subject') }}" aria-expanded="false"><i class="mdi mdi-receipt"></i><span class="hide-menu">Primary School</span></a></li>
    </ul>
</li>

<!-- <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Timetables</span></li>
<li class="sidebar-item">
    <a class="sidebar-link has-arrow waves-effect waves-dark" href="{{ route('admin.timetables') }}" aria-expanded="false"><i class="mdi mdi-timetable"></i><span class="hide-menu">Timetables</span></a>
    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin.timetables') }}" aria-expanded="false"><i class="mdi mdi-collage"></i><span class="hide-menu">All timetables</span></a></li>
    </ul>
</li> -->

<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Lessons</span></li>
<li class="sidebar-item">
    <a class="sidebar-link waves-effect waves-dark" href="{{route('admin.lessons')}}" aria-expanded="false"><i class="mdi mdi-book-multiple"></i><span class="hide-menu">Lessons </span></a>
</li>

<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Corporate Sessions</span></li>
<li class="sidebar-item">
    <a class="sidebar-link waves-effect waves-dark" href="{{route('admin.csessions')}}" aria-expanded="false"><i class="mdi mdi-webcam"></i><span class="hide-menu">Corporate Sessions </span></a>
</li>

<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Assessments</span></li>
<li class="sidebar-item">
    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-calendar-question"></i><span class="hide-menu">Assessments</span></a>
    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{route('admin.assignments')}}"><i class="mdi mdi-collage"></i><span class="hide-menu">Topical Questions</span></a></li>
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{route('admin.papers')}}"><i class="mdi mdi-collage"></i><span class="hide-menu">Revison Papers</span></a></li>
    </ul>
</li>