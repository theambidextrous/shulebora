@if( Auth::user())
<aside class="left-sidebar
@if(Auth::user()->is_learner && !Auth::user()->is_paid )
hidden-class
@endif
">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- User nav-->
                @if(Auth::user()->is_admin)
                @include('shared.nav.admin')
                @endif

                @if(Auth::user()->is_teacher)
                @include('shared.nav.teacher')
                @endif

                @if(Auth::user()->is_cop)
                @include('shared.nav.corporate')
                @endif

                @if(Auth::user()->is_learner)
                @include('shared.nav.learner')
                @endif
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
@endif