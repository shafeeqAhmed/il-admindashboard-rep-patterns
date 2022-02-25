@extends('layouts.default')
@section('title')
    Dashboard
@endsection

@section('body')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">

            <div class="content-body">
                <!-- Minimal statistics section start -->
                <section id="minimal-statistics">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.posts.index',['type'=>'reported'])}}">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="align-self-top">
                                                    <i class="icon-share icon-opacity primary font-large-4"></i>
                                                </div>
                                                <div class="media-body text-right align-self-bottom mt-3">
                                                    <span class="d-block mb-1 font-medium-1">Reported Post</span>
                                                    <h1 class="info mb-0">
                                                        {{ $data['reported_posts'] }}
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.posts.index',['type'=>'blocked'])}}">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="align-self-top">
                                                    <i class="icon-share icon-opacity primary font-large-4"></i>
                                                </div>
                                                <div class="media-body text-right align-self-bottom mt-3">
                                                    <span class="d-block mb-1 font-medium-1">Blocked Post</span>
                                                    <h1 class="info mb-0">
                                                        {{ $data['blocked_posts'] }}
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.boats.index',['type'=>'approved'])}}">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="align-self-top">
                                                    <i class="la-life-saver la icon-opacity primary font-large-4"></i>
                                                </div>
                                                <div class="media-body text-right align-self-bottom mt-3">
                                                    <span class="d-block mb-1 font-medium-1">Approved Boats</span>
                                                    <h1 class="info mb-0">
                                                        {{ $data['approved_boats'] }}
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.boats.index',['type'=>'unapproved'])}}">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="align-self-top">
                                                    <i class="la-life-saver la icon-opacity primary font-large-4"></i>
                                                </div>
                                                <div class="media-body text-right align-self-bottom mt-3">
                                                    <span class="d-block mb-1 font-medium-1">Un Approved Boats</span>
                                                    <h1 class="info mb-0">
                                                        {{ $data['un_approved_boats'] }}
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.boatOwners.index',['type'=>'active'])}}">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="align-self-top">
                                                    <i class="icon-users icon-opacity info font-large-4"></i>
                                                </div>
                                                <div class="media-body text-right align-self-bottom mt-3">
                                                    <span class="d-block mb-1 font-medium-1">Active Owners</span>
                                                    <h1 class="info mb-0">
                                                        {{ $data['active_boaters'] }}
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.boatOwners.index',['type'=>'not_verified'])}}">
                                <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-top">
                                                <i class="icon-users icon-opacity info font-large-4"></i>
                                            </div>
                                            <div class="media-body text-right align-self-bottom mt-3">
                                                <span class="d-block mb-1 font-medium-1">Not Verified Boaters</span>
                                                <h1 class="info mb-0">
                                                    {{ $data['not_verified_boaters'] }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.boatOwners.index',['type'=>'blocked'])}}">
                                <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-top">
                                                <i class="icon-users icon-opacity info font-large-4"></i>
                                            </div>
                                            <div class="media-body text-right align-self-bottom mt-3">
                                                <span class="d-block mb-1 font-medium-1">Blocked Owners</span>
                                                <h1 class="info mb-0">
                                                    {{ $data['blocked_boaters'] }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.boatOwners.index',['type'=>'deleted'])}}">
                                <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-top">
                                                <i class="icon-users icon-opacity info font-large-4"></i>
                                            </div>
                                            <div class="media-body text-right align-self-bottom mt-3">
                                                <span class="d-block mb-1 font-medium-1">Deleted Owners</span>
                                                <h1 class="info mb-0">
                                                    {{ $data['deleted_boaters'] }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.customers.index',['type'=>'active'])}}">
                                <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-top">
                                                <i class="icon-users icon-opacity danger font-large-4"></i>
                                            </div>
                                            <div class="media-body text-right align-self-bottom mt-3">
                                                <span class="d-block mb-1 font-medium-1">Active Customers</span>
                                                <h1 class="info mb-0">
                                                    {{ $data['active_customers'] }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.customers.index',['type'=>'not_verified'])}}">
                                <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-top">
                                                <i class="icon-users icon-opacity danger font-large-4"></i>
                                            </div>
                                            <div class="media-body text-right align-self-bottom mt-3">
                                                <span class="d-block mb-1 font-medium-1">Not Verified Customers</span>
                                                <h1 class="info mb-0">
                                                    {{ $data['not_verified_customers'] }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.customers.index',['type'=>'blocked'])}}">
                                <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-top">
                                                <i class="icon-users icon-opacity danger font-large-4"></i>
                                            </div>
                                            <div class="media-body text-right align-self-bottom mt-3">
                                                <span class="d-block mb-1 font-medium-1">Blocked Customers</span>
                                                <h1 class="info mb-0">
                                                    {{ $data['blocked_customers'] }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.customers.index',['type'=>'deleted'])}}">
                                <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-top">
                                                <i class="icon-users icon-opacity danger font-large-4"></i>
                                            </div>
                                            <div class="media-body text-right align-self-bottom mt-3">
                                                <span class="d-block mb-1 font-medium-1">Deleted Owners</span>
                                                <h1 class="info mb-0">
                                                    {{ $data['deleted_customers'] }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.bookings.index',['type'=>'pending'])}}">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="align-self-top">
                                                    <i class="icon-book-open icon-opacity success font-large-4"></i>
                                                </div>
                                                <div class="media-body text-right align-self-bottom mt-3">
                                                    <span class="d-block mb-1 font-medium-1">Pending Booking</span>
                                                    <h1 class="info mb-0">
                                                        {{ $data['pending_booking'] }}
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.bookings.index',['type'=>'confirmed'])}}">
                                <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-top">
                                                <i class="icon-book-open icon-opacity success font-large-4"></i>
                                            </div>
                                            <div class="media-body text-right align-self-bottom mt-3">
                                                <span class="d-block mb-1 font-medium-1">Confirmed Booking</span>
                                                <h1 class="info mb-0">
                                                    {{ $data['not_verified_customers'] }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.bookings.index',['type'=>'completed'])}}">
                                <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-top">
                                                <i class="icon-book-open icon-opacity success font-large-4"></i>
                                            </div>
                                            <div class="media-body text-right align-self-bottom mt-3">
                                                <span class="d-block mb-1 font-medium-1">Complete Booking</span>
                                                <h1 class="info mb-0">
                                                    {{ $data['completed_booking'] }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <a href="{{route('admin.bookings.index',['type'=>'cancelled'])}}">
                                <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-top">
                                                <i class="icon-book-open icon-opacity success font-large-4"></i>
                                            </div>
                                            <div class="media-body text-right align-self-bottom mt-3">
                                                <span class="d-block mb-1 font-medium-1">Cancel Booking</span>
                                                <h1 class="info mb-0">
                                                    {{ $data['cancelled_booking'] }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>

                </section>
                <!-- // Minimal statistics section end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
