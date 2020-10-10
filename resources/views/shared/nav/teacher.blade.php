<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Home</span></li>
<li class="sidebar-item">
    <a class="sidebar-link waves-effect waves-dark" href="{{ route('teacher') }}" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a>
</li>

<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Lessons</span></li>
<li class="sidebar-item">
    <a class="sidebar-link has-arrow waves-effect waves-dark" href="{{ route('teacher.lessons') }}" aria-expanded="false"><i class="mdi mdi-book-open-page-variant"></i><span class="hide-menu">Lessons</span></a>
    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('teacher.lessons') }}" aria-expanded="false"><i class="mdi mdi-collage"></i><span class="hide-menu">My Lessons</span></a></li>
    </ul>
</li>

<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Subjects</span></li>
<li class="sidebar-item">
    <a class="sidebar-link has-arrow waves-effect waves-dark" href="{{ route('teacher.subjects') }}" aria-expanded="false"><i class="mdi mdi-book-open-page-variant"></i><span class="hide-menu">Subjects</span></a>
    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{ route('teacher.subjects') }}" aria-expanded="false"><i class="mdi mdi-collage"></i><span class="hide-menu">My Subjects</span></a></li>
    </ul>
</li>

<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Assessments</span></li>
<li class="sidebar-item">
    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-calendar-question"></i><span class="hide-menu">Assessments</span></a>
    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{route('teacher.assignments')}}"><i class="mdi mdi-collage"></i><span class="hide-menu">Topical Questions</span></a></li>
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark" href="{{route('teacher.papers')}}"><i class="mdi mdi-collage"></i><span class="hide-menu">Revison Papers</span></a></li>
    </ul>
</li>