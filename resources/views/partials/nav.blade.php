<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{url('/home')}}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{url('assets/images/whelson_logo.png')}}" alt="" height="32">
                                </span>
                    <span class="logo-lg">
                                    <img src="{{url('assets/images/top_logo_small.png')}}" alt="" height="30">
                                </span>
                </a>

                <a href="{{url('/home')}}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{url('assets/images/whelson_logo.png')}}" alt="" height="32">
                                </span>
                    <span class="logo-lg">
                                    <img src="{{url('assets/images/top_logo_small.png')}}" alt="" height="30">
                                </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="mdi mdi-backburger"></i>
            </button>



        </div>
        @guest
            <li><a class="nav-link" href="{{ route('login') }}">{{ trans('titles.login') }}</a></li>
            <li><a class="nav-link" href="{{ route('register') }}">{{ trans('titles.register') }}</a></li>
        @else
            <div class="d-flex">

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-flag-dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-18px mdi-bell-outline"></i>
                        <span class="badge badge-pill badge-warning">{{auth()->user()->unreadNotifications->count()}}</span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            @include('partials.notification.'.snake_case(class_basename($notification->type)))

                        @empty
                            <li class="dropdown-item notify-item" >
                                <a href="#">All caught up here, Great!</a>
                            </li>
                            {{--<li class="dropdown-item notify-item"><a href="#">{{$notification->data['data']}}</a></li>--}}
                            {{--<li class="dropdown-item notify-item"><a href="#">{{snake_case(class_basename($notification->type))}}</a></li>--}}
                        @endforelse
                    <!-- item-->
                        {{--<a href="javascript:void(0);" class="dropdown-item notify-item">
                            <img src="assets/images/flags/germany.jpg" alt="user-image" class="mr-2" height="12"><span class="align-middle">German</span>
                        </a>--}}
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        @if ((Auth::User()->profile) && Auth::user()->profile->avatar_status == 1)
                            <img class="rounded-circle header-profile-user" src="{{ url(Auth::user()->profile->avatar) }}" alt="{{ Auth::user()->name }}" >
                        @else
                            <img class="rounded-circle header-profile-user" src="{{url('assets/images/users/avatar-1.jpg')}}" alt="">
                        @endif
                        <span class="d-none d-sm-inline-block ml-1">{{ Auth::user()->name }} - {{ Auth::user()->paynumber }} <span class="caret"></span></span>
                        <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <!-- item-->
                        <a class="dropdown-item {{ Request::is('profile/'.Auth::user()->name, 'profile/'.Auth::user()->name . '/edit') ? 'active' : null }}" href="{{ url('/profile/'.Auth::user()->name) }}"><i class="mdi mdi-face-profile font-size-16 align-middle mr-1"></i> Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout font-size-16 align-middle mr-1"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>

            </div>
        @endguest
    </div>

</header>
@role('admin')
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title text-info">Menu</li>

                <li>
                    <a href="{{url('/home')}}" class="waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-desktop-mac-dashboard"></i></div>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-gas-station-outline"></i></div>
                        <span>Fuel Requests</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/frequests/create')}}">Create Request</a></li>
                        <li><a href="{{url('/currentrequests')}}">Current Requests</a></li>
                        <li><a href="{{url('/myfuelrequests')}}">My Requests</a></li>
                        <li><a href="{{url('/manage-requests')}}">Manage Requests</a></li>
                        <li><a href="{{url('/approved-requests')}}">Approved Requests</a></li>
                        <li><a href="{{url('/pending-requests')}}">Pending Requests</a></li>
                        <li><a href="{{url('/frequests')}}">All Requests</a></li>
                        <li><a href="{{url('/frequests/deleted')}}">Deleted Requests</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-chart-pie"></i></div>
                        <span>Allocations</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/allocations')}}"> Users </a></li>
                        <li><a href="{{url('/executive-allocations')}}">Directors </a></li>
                        <li><a href="{{url('/executive-allocations-previous')}}">Directors Previous Allocation </a></li>
                        <li><a href="{{url('/myallocations')}}">My Allocations </a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-chart-pie"></i></div>
                        <span>Non Allocations</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/non_allocations')}}"> Users </a></li>
                        <li><a href="{{url('/mynon_allocations')}}">My Allocations </a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-fuel"></i></div>
                        <span>Fuel Issue</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/mytransactions')}}">My Transactions</a></li>
                        <li><a href="{{url('/current-trans')}}">Current Transactions</a></li>
                        <li><a href="{{url('/transactions')}}">All Transactions</a></li>
                        <li><a href="{{url('/mycashsales')}}">My Cash Sales</a></li>
                        <li><a href="{{url('/current-cash')}}">Current Cash Sales</a></li>
                        <li><a href="{{url('/cashsales')}}">All Cash Sales</a></li>
                        <li><a href="{{url('/mystockissues')}}">My Stock Issues</a></li>
                        <li><a href="{{url('/current-stock')}}">Current Stock Issues</a></li>
                        <li><a href="{{url('/stockissues')}}">All Stock Issues</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-account-group"></i></div>
                        <span>Users</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/users')}}">Users</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-receipt"></i></div>
                        <span>Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('allocations-report')}}">Individual Allocation</a></li>
                        <li><a href="{{url('all-allocations-report')}}">All Allocations</a></li>
                        <li><a href="{{url('allocations-balances')}}">Allocation Balances</a></li>
                        <li><a href="{{url('cash-sale-report')}}">Cash Sales</a></li>
                        <li><a href="{{url('stock-issue-report')}}">Stock Issues</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-sitemap"></i></div>
                        <span>Entities</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/departments')}}">Departments</a></li>
                        <li><a href="{{url('/jobtitles')}}">Job Titles</a></li>
                    </ul>
                </li>

                <li class="menu-title text-info">Service Station</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-package"></i></div>
                        <span>Fuel Batches</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('batches')}}">Fuel Batches</a></li>
                        <li><a href="{{url('batches/create')}}">Add Batch</a></li>
                        <li><a href="{{url('batches/deleted')}}">Deleted Batches</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-account-circle"></i></div>
                        <span>Clients</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('clients')}}">Clients</a></li>
                        <li><a href="{{url('clients/create')}}">Add Client</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-package-variant"></i></div>
                        <span>Containers</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('containers')}}">Containers</a></li>
                        <li><a href="{{url('containers/create')}}">Add Container</a></li>
                        <li><a href="{{url('active-containers')}}">Active Containers</a></li>
                        <li><a href="{{url('empty-containers')}}">Empty Containers</a></li>
                        <li><a href="{{url('containers/deleted')}}">Deleted Containers</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-archive"></i></div>
                        <span>Container Transactions</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('container_transactions')}}">Transactions</a></li>
                        <li><a href="{{url('container_transactions/create')}}">Create Transaction</a></li>
                        <li><a href="{{url('deleted-containers')}}">Deleted Transactions</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-cash-usd-outline"></i></div>
                        <span>Invoices</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('invoices')}}">Invoices</a></li>
                        <li><a href="{{url('invoices/create')}}">Create Invoice</a></li>
                        <li><a href="{{url('invoices/deleted')}}">Deleted Invoices</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-coin-outline"></i></div>
                        <span>Quotations</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('quotations')}}">Quotations</a></li>
                        <li><a href="{{url('quotations/create')}}">Create Quotation</a></li>
                        <li><a href="{{url('quotations/deleted')}}">Deleted Quotations</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-receipt"></i></div>
                        <span>Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('invoices-report')}}">Invoices </a></li>
                        <li><a href="{{url('containers-report')}}">Containers</a></li>
                        <li><a href="{{url('container-transaction-report')}}">Container Transaction</a></li>
                    </ul>
                </li>

                <li class="menu-title text-info">System Settings</li>

                <li>
                    <a href="{{url('/fuelsettings')}}" class=" waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-chart-pie"></i></div>
                        <span>Fuel Settings</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-account-supervisor"></i></div>
                        <span>Admin</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a class="dropdown-item {{ (Request::is('roles') || Request::is('permissions')) ? 'active' : null }}" href="{{ route('laravelroles::roles.index') }}">
                                {!! trans('titles.laravelroles') !!}
                            </a>
                        </li>
                        <li><a class="dropdown-item {{ Request::is('logs') ? 'active' : null }}" href="{{ url('/logs') }}">
                                {!! trans('titles.adminLogs') !!}
                            </a></li>
                        <li><a class="dropdown-item {{ Request::is('activity') ? 'active' : null }}" href="{{ url('/activity') }}">
                                {!! trans('titles.adminActivity') !!}
                            </a></li>
                        <li><a class="dropdown-item {{ Request::is('phpinfo') ? 'active' : null }}" href="{{ url('/phpinfo') }}">
                                {!! trans('titles.adminPHP') !!}
                            </a></li>
                        <li><a class="dropdown-item {{ Request::is('routes') ? 'active' : null }}" href="{{ url('/routes') }}">
                                {!! trans('titles.adminRoutes') !!}
                            </a></li>
                        <li><a class="dropdown-item {{ Request::is('active-users') ? 'active' : null }}" href="{{ url('/active-users') }}">
                                {!! trans('titles.activeUsers') !!}
                            </a></li>
                        <li><a class="dropdown-item {{ Request::is('blocker') ? 'active' : null }}" href="{{ route('laravelblocker::blocker.index') }}">
                                {!! trans('titles.laravelBlocker') !!}
                            </a></li>
                    </ul>
                </li>

            </ul>

        </div>
        <!-- Sidebar -->
    </div>
</div>
@endrole

@role('manager')
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title text-info">Menu</li>

                <li>
                    <a href="{{url('/home')}}" class="waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-desktop-mac-dashboard"></i></div>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-gas-station-outline"></i></div>
                        <span>Fuel Requests</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/frequests/create')}}">Create Request</a></li>
                        <li><a href="{{url('/currentrequests')}}">Current Requests</a></li>
                        <li><a href="{{url('/myfuelrequests')}}">My Requests</a></li>
                        <li><a href="{{url('/manage-requests')}}">Manage Requests</a></li>
                        <li><a href="{{url('/approved-requests')}}">Approved Requests</a></li>
                        <li><a href="{{url('/pending-requests')}}">Pending Requests</a></li>
                        <li><a href="{{url('/frequests')}}">All Requests</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-chart-pie"></i></div>
                        <span>Allocations</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/allocations')}}"> Users </a></li>
                        <li><a href="{{url('/executive-allocations')}}">Directors </a></li>
                        <li><a href="{{url('/executive-allocations-previous')}}">Directors Previous Allocation </a></li>
                        <li><a href="{{url('/myallocations')}}">My Allocations </a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-chart-pie"></i></div>
                        <span>Non Allocations</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/non_allocations')}}"> Users </a></li>
                        <li><a href="{{url('/mynon_allocations')}}">My Allocations </a></li>
                    </ul>
                </li>


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-fuel"></i></div>
                        <span>Fuel Issue</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/mytransactions')}}">My Transactions</a></li>
                        <li><a href="{{url('/transactions')}}">Transactions</a></li>
                        <li><a href="{{url('/mycashsales')}}">My Cash Sales</a></li>
                        <li><a href="{{url('/cashsales')}}">Cash Sales</a></li>
                        <li><a href="{{url('/mystockissues')}}">My Stock Issues</a></li>
                        <li><a href="{{url('/stockissues')}}">Stock Issues</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-account-group"></i></div>
                        <span>Users</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/users')}}">Users</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-receipt"></i></div>
                        <span>Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('allocations-report')}}">Individual Allocation </a></li>
                        <li><a href="{{url('all-allocations-report')}}">All Allocation</a></li>
                        <li><a href="{{url('allocations-balances')}}">Allocation Balances</a></li>
                        <li><a href="{{url('cash-sale-report')}}">Cash Sales</a></li>
                        <li><a href="{{url('stock-issue-report')}}">Stock Issues</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-sitemap"></i></div>
                        <span>Entities</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/departments')}}">Departments</a></li>
                        <li><a href="{{url('/jobtitles')}}">Job Titles</a></li>
                    </ul>
                </li>

                <li class="menu-title text-info">Service Station</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-package"></i></div>
                        <span>Fuel Batches</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('batches')}}">Fuel Batches</a></li>
                        <li><a href="{{url('batches/create')}}">Add Batch</a></li>
                        <li><a href="{{url('batches/deleted')}}">Deleted Batches</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-account-circle"></i></div>
                        <span>Clients</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('clients')}}">Clients</a></li>
                        <li><a href="{{url('clients/create')}}">Add Client</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-package-variant"></i></div>
                        <span>Containers</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('containers')}}">Containers</a></li>
                        <li><a href="{{url('containers/create')}}">Add Container</a></li>
                        <li><a href="{{url('active-containers')}}">Active Containers</a></li>
                        <li><a href="{{url('empty-containers')}}">Empty Containers</a></li>
                        <li><a href="{{url('containers/deleted')}}">Deleted Containers</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-archive"></i></div>
                        <span>Container Transactions</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('container_transactions')}}">Transactions</a></li>
                        <li><a href="{{url('container_transactions/create')}}">Create Transaction</a></li>
                        <li><a href="{{url('deleted-containers')}}">Deleted Transactions</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-cash-usd-outline"></i></div>
                        <span>Invoices</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('invoices')}}">Invoices</a></li>
                        <li><a href="{{url('invoices/create')}}">Create Invoice</a></li>
                        <li><a href="{{url('invoices/deleted')}}">Deleted Invoices</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-coin-outline"></i></div>
                        <span>Quotations</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('quotations')}}">Quotations</a></li>
                        <li><a href="{{url('quotations/create')}}">Create Quotation</a></li>
                        <li><a href="{{url('quotations/deleted')}}">Deleted Quotations</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-receipt"></i></div>
                        <span>Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('invoices-report')}}">Invoices </a></li>
                        <li><a href="{{url('containers-report')}}">Containers</a></li>
                        <li><a href="{{url('container-transaction-report')}}">Container Transaction</a></li>
                    </ul>
                </li>


                <li class="menu-title text-info">System Settings</li>

                <li>
                    <a href="{{url('/fuelsettings')}}" class=" waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-chart-pie"></i></div>
                        <span>Fuel Settings</span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- Sidebar -->
    </div>
</div>
@endrole

@role('diesel')
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title text-info">Menu</li>

                <li>
                    <a href="{{url('/home')}}" class="waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-desktop-mac-dashboard"></i></div>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-gas-station-outline"></i></div>
                        <span>Fuel Requests</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/frequests/create')}}">Create Request</a></li>
                        <li><a href="{{url('/currentrequests')}}">Current Requests</a></li>
                        <li><a href="{{url('/myfuelrequests')}}">My Requests</a></li>
                        <li><a href="{{url('/approved-requests')}}">Approved Requests</a></li>
                        <li><a href="{{url('/pending-requests')}}">Pending Requests</a></li>
                        <li><a href="{{url('/frequests')}}">All Requests</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-chart-pie"></i></div>
                        <span>Allocations</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/allocations')}}"> Users </a></li>
                        <li><a href="{{url('/executive-allocations')}}">Directors </a></li>
                        <li><a href="{{url('/executive-allocations-previous')}}">Directors Previous Allocation </a></li>
                        <li><a href="{{url('/myallocations')}}">My Allocations </a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-chart-pie"></i></div>
                        <span>Non Allocations</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/non_allocations')}}"> Users </a></li>
                        <li><a href="{{url('/mynon_allocations')}}">My Allocations </a></li>
                    </ul>
                </li>


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-fuel"></i></div>
                        <span>Fuel Issue</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/current-trans')}}">Current Transactions</a></li>
                        <li><a href="{{url('/transactions')}}">All Transactions</a></li>
                        <li><a href="{{url('/current-cash')}}">Current Cash Sales</a></li>
                        <li><a href="{{url('/cashsales')}}">All Cash Sales</a></li>
                        <li><a href="{{url('/current-stock')}}">Current Stock Issues</a></li>
                        <li><a href="{{url('/stockissues')}}">All Stock Issues</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-account-group"></i></div>
                        <span>Users</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/users')}}">Users</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-receipt"></i></div>
                        <span>Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('allocations-report')}}">Individual Allocation</a></li>
                        <li><a href="{{url('all-allocations-report')}}">All Allocation</a></li>
                        <li><a href="{{url('allocations-balances')}}">Allocation Balances</a></li>
                        <li><a href="{{url('cash-sale-report')}}">Cash Sales</a></li>
                        <li><a href="{{url('stock-issue-report')}}">Stock Issues</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-sitemap"></i></div>
                        <span>Entities</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/departments')}}">Departments</a></li>
                        <li><a href="{{url('/jobtitles')}}">Job Titles</a></li>
                    </ul>
                </li>

            </ul>

        </div>
        <!-- Sidebar -->
    </div>
</div>
@endrole

@role('user')
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li>
                    <a href="{{url('/home')}}" class="waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-desktop-mac-dashboard"></i></div>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-gas-station-outline"></i></div>
                        <span>Fuel Requests</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/frequests/create')}}">Create Request</a></li>
                        <li><a href="{{url('/myfuelrequests')}}">Requests</a></li>
                    </ul>
                </li>

                @if(auth()->user()->allocation != 'Non-allocation')
                    <li>
                        <a href="{{url('/myallocations')}}" class=" waves-effect">
                            <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-chart-pie"></i></div>
                            <span>Allocations</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="mdi mdi-fuel"></i></div>
                        <span>Fuel Issue</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('/mytransactions')}}">Transactions</a></li>
                        <li><a href="{{url('/mycashsales')}}">Cash Sales</a></li>
                        <li><a href="{{url('/mystockissues')}}">Stock Issues</a></li>
                    </ul>
                </li>

            </ul>

        </div>
        <!-- Sidebar -->
    </div>
</div>
@endrole
