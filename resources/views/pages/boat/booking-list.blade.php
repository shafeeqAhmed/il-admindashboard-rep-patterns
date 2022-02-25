@extends('layouts.default')
@section('title')
    Booking List
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
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title">
                        Boat Booking List
                    </h3>
                </div>
                <div class="content-header-right col-md-8 col-12">
                    <div class="breadcrumbs-top float-md-right">
                        <div class="breadcrumb-wrapper mr-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item text-white">
                                    Approved Boat
                                </li>
                                <li class="breadcrumb-item text-white">
                                    Boat Detail
                                </li>
                                <li class="breadcrumb-item active">
                                    Booking List
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
                                                        <th></th>
                                                        <th># Index</th>
                                                        <th>Short ID</th>
                                                        <th>Boat Name</th>
                                                        <th>Customer Name</th>
                                                        <th>Renter Name</th>
                                                        <th>Start DateTime</th>
                                                        <th>End DateTime</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($boat_bookings as $key => $record)
                                                        <tr>
                                                            <td>
                                                                <input class="count_check" type="checkbox"
                                                                    name="count_check" id="count_check">
                                                                <input class="payment_received" type="hidden"
                                                                    name="payment_received" id="payment_received"
                                                                    value="{{ $record['payment_received'] }}">
                                                                <input class="booking_boat_user_id" type="hidden"
                                                                    name="booking_boat_user_id" id="booking_boat_user_id"
                                                                    value="{{ $record['user']['id'] }}">
                                                            </td>
                                                            <td>
                                                                {{ ++$key }}
                                                            </td>
                                                            <td>
                                                                {{ $record['booking_short_id'] }}
                                                            </td>
                                                            <td>
                                                                {{ $record['boat']['name'] . ' - ' ?? '' }}
                                                                {{ $record['boat']['number'] ?? '' }}
                                                            </td>
                                                            <td>
                                                                {{ $record['user']['first_name'] }}
                                                            </td>
                                                            <td>
                                                                {{ $record['boat']['user']['first_name'] ?? '' }}
                                                            </td>
                                                            <td>
                                                                {{ convertTimeStampToDateTime($record['start_date_time']) }}
                                                            </td>
                                                            <td>
                                                                {{ convertTimeStampToDateTime($record['end_date_time']) }}
                                                            </td>
                                                            <td>
                                                                {{ $record['status'] }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th># Index</th>
                                                        <th>Short ID</th>
                                                        <th>Boat Name</th>
                                                        <th>Customer Name</th>
                                                        <th>Renter Name</th>
                                                        <th>Start DateTime</th>
                                                        <th>End DateTime</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="mt-1">
                                            <form action="{{ route('admin.boatBooking.withdraw') }}" method="POST">
                                                <table class="table table-striped table-bordered base-style">
                                                    @csrf
                                                    <tr>
                                                        <input type="hidden" id="user_id" name="user_id" value="0" />
                                                        <input type="hidden" id="amount" name="amount" value="0" />
                                                        <td id="total_payment_amount">
                                                            0
                                                        </td>
                                                        <th>Total Amount</th>
                                                        <td>
                                                            <button type="submit" id="transfer_amount_btn" disabled
                                                                class="btn btn-bg-gradient-x-blue-cyan">
                                                                Transfer Amount
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>
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
    <script>
        $('.count_check').change(function() {
            let amount = Number($.trim($(this).siblings(".payment_received").val()))
            let amount_total_count = Number($.trim($("#total_payment_amount").text()))
            let user_id = $(this).siblings(".booking_boat_user_id").val()

            if (this.checked) {
                amount_total_count += Number(amount);
                $("#total_payment_amount").text(amount_total_count.toFixed(2))
                $("#transfer_amount_btn").attr('disabled', false)
                $("#user_id").val(user_id)
                $("#amount").val(amount_total_count.toFixed(2))
            } else {
                amount_total_count -= Number(amount);
                $("#total_payment_amount").text(amount_total_count.toFixed(2))
                $("#amount").val(amount_total_count.toFixed(2))
                $("#transfer_amount_btn").attr('disabled', true)
                $("#user_id").val(user_id)
            }
        });
    </script>
@endsection
