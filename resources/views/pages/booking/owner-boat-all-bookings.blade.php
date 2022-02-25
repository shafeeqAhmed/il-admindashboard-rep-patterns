@extends('layouts.default')
@section('title')
    {{ $type == 'transfered' ? 'Transferred Bookings | Boatek' : '' }}
    {{ $type == 'all' ? 'All Bookings | Boatek' : '' }}
@endsection

@section('local-style')
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/theme/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <!-- END: Vendor CSS-->
@endsection

@section('body')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row align-items-center">
                <div class="content-header-left col-md-6 col-12 mb-4">
                    <h3 class="content-header-title">
                        <img src="{{ $records['user']->profile_pic ?? asset('images/no-picture-available.jpg') }}" height="80" width="80" style="border-radius: 50%">
                        {{ $records['user']->first_name }} {{ $records['user']->last_name }} |
                        {{ $records['user']->email }}
                    </h3>
                </div>
                <div class="content-header-right col-md-6 col-10">
                    <div class="breadcrumbs-top float-md-right">
                        <div class="breadcrumb-wrapper mr-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    {{ $type == 'transfered' ? 'Transferred Bookings' : '' }}
                                    {{ $type == 'all' ? 'All Bookings' : '' }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Base style table -->
                <section id="base-style">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <a href="{{ route('admin.boatOwners.boats', ['uuid' => $records['user']->user_uuid]) }}"
                                            type="button" class="btn btn-bg-gradient-x-purple-blue">
                                            Back
                                        </a>
                                    </h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard">
                                        @include('includes.error')
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered base-style">
                                                <thead>
                                                    <tr>
                                                        <th># Index</th>
                                                        <th>Booking Short ID</th>
                                                        <th>Customer Name</th>
                                                        <th>Booking Price</th>
                                                        <th>Payment Received</th>
                                                        <th>Transaction Charges</th>
                                                        <th>Boatek Fee</th>
                                                        <th>Refund Amount</th>
                                                        <th>Status</th>
                                                        <th>Transfer</th>
                                                        <th>Refunded</th>
                                                        <th>Booking Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($records['bookings'] as $key => $record)
                                                        <tr>
                                                            <td>
                                                                {{ ++$key }}
                                                            </td>
                                                            <td>
                                                                {{ $record['booking_short_id'] }}
                                                            </td>
                                                            <td>
                                                                {{ $record['user']['first_name'] }}
                                                            </td>
                                                            <td>
                                                                {{ $record['booking_price'] }}
                                                            </td>
                                                            <td>
                                                                {{ $record['payment_received'] }}
                                                            </td>
                                                            <td>
                                                                {{ $record['transaction_charges'] ?? 0 }}
                                                            </td>
                                                            <td>
                                                                {{ $record['boatek_fee'] ?? 0 }}
                                                            </td>
                                                            <td>
                                                                {{ $record['refund_amount'] }}
                                                            </td>
                                                            <td>
                                                                {{ $record['status'] }}
                                                            </td>
                                                            <td>
                                                                {{ $record['is_transferred'] == 0 ? 'No' : 'Yes' }}
                                                            </td>
                                                            <td>
                                                                {{ $record['is_refund'] == 0 ? 'No' : 'Yes' }}
                                                            </td>
                                                            <td>
                                                                {{ $record['created_at_converted'] }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th># Index</th>
                                                        <th>Booking Short ID</th>
                                                        <th>Customer Name</th>
                                                        <th>Booking Price</th>
                                                        <th>Payment Received</th>
                                                        <th>Transaction Charges</th>
                                                        <th>Boatek Fee</th>
                                                        <th>Refund Amount</th>
                                                        <th>Status</th>
                                                        <th>Transfer</th>
                                                        <th>Refunded</th>
                                                        <th>Booking Date</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Base style table -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

@section('local-script')
    <!-- BEGIN: Page JS-->
    <script src="{{ asset('public/theme/app-assets/js/scripts/tables/datatables/datatable-styling.js') }}"
        type="text/javascript"></script>
    <!-- END: Page JS-->
@endsection
