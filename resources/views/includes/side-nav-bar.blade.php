<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true"
     data-img="../../../app-assets/images/backgrounds/02.jpg">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand"
                                            href="{{route('index')}}"><img
                        class="brand-logo" alt="Boatek logo"
                        src="{{ asset('theme/app-assets/images/logo/logo.png') }}"/>
                    <h3 class="brand-text">Boatek</h3>
                </a></li>
            <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
        </ul>
    </div>
    <div class="navigation-background"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item">
                <a href="{{ route('admin.dashboard') }}"><i class="la la-home"></i>
                    <span class="menu-title" data-i18n="">
                        Dashboard
                    </span>
                </a>
            </li>
            <li class=" nav-item">
                <a href="{{route('admin.boatTypes.index')}}"><i class="la la-bars"></i>
                    <span class="menu-title" data-i18n="">
                        Boat Type
                    </span>
                </a>
            </li>
            <li class=" nav-item">
                <a href="#">
                    <i class="ft-calendar"></i>
                    <span class="menu-title" data-i18n="">
                        Booking
                    </span>
                </a>
                <ul class="menu-content">
                    <li class="navigation-divider"></li>
                    <li>
                        <a class="menu-item" href="{{route('admin.bookings.index',['type'=>'pending'])}}">Pending</a>
                    </li>
                    <li>
                        <a class="menu-item"
                           href="{{route('admin.bookings.index',['type'=>'confirmed'])}}">Confirmed</a>
                    </li>
                    <li>
                        <a class="menu-item"
                           href="{{route('admin.bookings.index',['type'=>'completed'])}}">Completed</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{route('admin.bookings.index',['type'=>'cancelled'])}}">Cancelled</a>
                    </li>
                </ul>
            </li>

            <li class=" nav-item">
                <a href="#">
                    <i class="ft-users"></i>
                    <span class="menu-title" data-i18n="">
                        Boat Owner
                    </span>
                </a>
                <ul class="menu-content">
                    <li class="navigation-divider"></li>
                    <li>
                        <a class="menu-item" href="{{route('admin.boatOwners.index',['type'=>''])}}">All</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{route('admin.boatOwners.index',['type'=>'active'])}}">Active</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{route('admin.boatOwners.index',['type'=>'blocked'])}}">Blocked</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{route('admin.boatOwners.index',['type'=>'deleted'])}}">Deleted</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{route('admin.boatOwners.index',['type'=>'not_verified'])}}">Not Verified</a>
                    </li>

                </ul>
            </li>
            <li class=" nav-item">
                <a href="#">
                    <i class="ft-users"></i>
                    <span class="menu-title" data-i18n="">
                        Customers
                    </span>
                </a>
                <ul class="menu-content">
                    <li class="navigation-divider"></li>
                    <li>
                        <a class="menu-item" href="{{route('admin.customers.index',['type'=>''])}}">All</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{route('admin.customers.index',['type'=>'active'])}}">Active</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{route('admin.customers.index',['type'=>'blocked'])}}">Blocked</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{route('admin.customers.index',['type'=>'deleted'])}}">Deleted</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{route('admin.customers.index',['type'=>'not_verified'])}}">Not Verified</a>
                    </li>

                </ul>
            </li></li>
            <li class=" nav-item">
                <a href="#">
                    <i class="ft-share-2"></i>
                    <span class="menu-title" data-i18n="">
                        Posts
                    </span>
                </a>
                <ul class="menu-content">
                    <li class="navigation-divider"></li>
                    <li>
                        <a class="menu-item" href="{{route('admin.posts.index',['type'=>'reported'])}}">Reported</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{route('admin.posts.index',['type'=>'blocked'])}}">Blocked</a>
                    </li>

                </ul>
            </li></li>
            <li class=" nav-item">
                <a href="#">
                    <i class="la la-dollar"></i>
                    <span class="menu-title" data-i18n="">
                        Revenue
                    </span>
                </a>
                <ul class="menu-content">
                    <li class="navigation-divider"></li>
                    <li>
                        <a class="menu-item" href="{{route('admin.revenues.earning',['type'=>'blocked'])}}">Earning</a>
                    </li>
                </ul>
            </li></li>
            <li class=" nav-item">
                <a href="#">
                    <i class="ft-settings"></i>
                    <span class="menu-title" data-i18n="">
                        Settings
                    </span>
                </a>
                <ul class="menu-content">
                    <li class="navigation-divider"></li>
                    <li>
                        <a class="menu-item" href="{{route('admin.messageCodes')}}">Message Codes</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{route('admin.settings.index')}}">System Settings</a>
                    </li>

                </ul>
            </li></li>

        </ul>
    </div>
</div>
