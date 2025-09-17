                    
<!-- Sidebar -->
<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="content-header">
        <!-- Logo -->
        <a class="fw-semibold text-dual" href="{{ url('/home') }}">
            <span class="smini-visible">
                <i class="fa fa-circle-notch text-primary"></i>
            </span>
            <span class="smini-hide fs-5 tracking-wider">{{ config('app.name') }}</span>
        </a>
        <!-- END Logo -->

        <!-- Extra -->
        <div>

            <!-- Close Sidebar, Visible only on mobile screens -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout" data-action="sidebar_close"
                href="javascript:void(0)">
                <i class="fa fa-fw fa-times"></i>
            </a>
            <!-- END Close Sidebar -->
        </div>
        <!-- END Extra -->
    </div>
    <!-- END Side Header -->

    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
            <ul class="nav-main">
               {{-- -------------------------Survey Management------------------------- --}}
                <li class="nav-main-item {{ isset($activeMenu) && (in_array($activeMenu, ['questions', 'responses','submit-responses'])) ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa fa-question-circle"></i>
                        <span class="nav-main-link-name">Survey Management</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ isset($activeMenu) && (in_array($activeMenu, ['questions'])) ? 'active' : '' }}" href="{{ route('questions.index') }}">
                                <span class="nav-main-link-name">Questions</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ isset($activeMenu) && (in_array($activeMenu, ['responses'])) ? 'active' : '' }}" href="{{ route('responses.index') }}">
                                <span class="nav-main-link-name">Responses</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ isset($activeMenu) && (in_array($activeMenu, ['submit-responses'])) ? 'active' : '' }}" href="{{ route('responses.create') }}">
                                <span class="nav-main-link-name">Submit Response</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- ------------------------------End Survey Management-------------------------- --}}


            </ul>
        </div>
        <!-- END Side Navigation -->
    </div>
    <!-- END Sidebar Scrolling -->
</nav>
<!-- END Sidebar -->
