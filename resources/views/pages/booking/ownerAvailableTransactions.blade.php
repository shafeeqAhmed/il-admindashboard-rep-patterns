@extends('layouts.default')
@section('title')
    Onwer Boats Booking List
@endsection

@section('local-style')
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/theme/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <style>
        .bCheckbox {
            -ms-transform: scale(2);
            /* IE */
            -moz-transform: scale(2);
            /* FF */
            -webkit-transform: scale(2);
            /* Safari and Chrome */
            -o-transform: scale(2);
            /* Opera */
            transform: scale(2);
            padding: 10px;
        }

    </style>
    <!-- END: Vendor CSS-->
@endsection

@section('body')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row align-items-center">
                <div class="content-header-left col-md-6 col-12 mb-3">
                    <h3 class="content-header-title">
                        <img src="{{ $user->profile_pic ?? asset('images/no-picture-available.jpg') }}" height="80"
                            width="80" style="border-radius: 50%">
                        {{ $user->first_name }} {{ $user->last_name }} | {{ $user->email }}
                    </h3>
                </div>
                <div class="content-header-right col-md-6 col-11">
                    <div class="breadcrumbs-top float-md-right">
                        <div class="breadcrumb-wrapper mr-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Available Transactions
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
                                        <a href="{{ route('admin.boatOwners.boats', ['uuid' => $user['user_uuid']]) }}"
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
                                    <div class="card-body card-dashboard">
                                        @include('includes.error')
                                        <h3><b><i class="ft-cpu"></i> Bank Account Details:</b></h3>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <h5 class="text-primary font-weight-bold">Bank</h5>
                                                <p class="font-weight-bold text-dark">
                                                    {{ $user->bankAccountDetail->bank_name ?? 'N-A' }}</p>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <h5 class="text-primary font-weight-bold">Billing Address</h5>
                                                <p class="font-weight-bold text-dark">
                                                    {{ $user->bankAccountDetail->billing_address ?? 'N-A' }}</p>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <h5 class="text-primary font-weight-bold">Post Code</h5>
                                                <p class="font-weight-bold text-dark">
                                                    {{ $user->bankAccountDetail->post_code ?? 'N-A' }}</p>
                                            </div>
                                            <div class="form-group col-md-6">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <h5 class="text-primary font-weight-bold">Account Name</h5>
                                                <p class="font-weight-bold text-dark">
                                                    {{ $user->bankAccountDetail->account_name ?? 'N-A' }}</p>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <h5 class="text-primary font-weight-bold">Account Number</h5>
                                                <p class="font-weight-bold text-dark">
                                                    {{ $user->bankAccountDetail->account_number ?? 'N-A' }}</p>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <h5 class="text-primary font-weight-bold">Account IBAN</h5>
                                                <p class="font-weight-bold text-dark">
                                                    {{ $user->bankAccountDetail->iban_account_number ?? 'N-A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <form id="transaction-form" method="post" enctype="multipart/form-data"
                                        action="{{ route('admin.boatOwners.saveAvaileableTransactions') }}">
                                        @csrf
                                        <input type="hidden" name="owner_id" value="{{ $user['id'] }}">
                                        <div class="card-body card-dashboard">
                                            <h3><b>Payment Detail:</b></h3>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label> Recepit Id</label>
                                                    <input type="text" class="form-control" name="receipt_id"
                                                        id="receipt_id" placeholder="Enter receipt id" required />
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label> Recepit Date</label>
                                                    <input type="date" class="form-control" name="receipt_date"
                                                        id="receipt_date" placeholder="Select receipt date" required />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label> Recepit File</label>
                                                    <input type="file" class="form-control" name="receipt_file"
                                                        id="receipt_file" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" name="save" class="btn btn-sm btn-success pull-right ml-2"
                                                        value="true">
                                                        <i class="ft-download"></i> Save & Download
                                                    </button>
                                                    <button type="submit" name="save" class="btn btn-sm btn-primary pull-right"
                                                        value="false">
                                                        <i class="ft-save"></i> Save
                                                    </button>
                                                </div>
                                            </div>

                                            @if (!empty($transactions['boats']))
                                                <h3 class="mt-3">
                                                    <b>Booking Detail:</b>
                                                    <span class="pull-right">SAR <b
                                                            id="total_transaction_amount">0</b></span>
                                                </h3>
                                                @foreach ($transactions['boats'] as $boat)

                                                    <h4 class="mt-2"> <img
                                                            src="{{ $boat['profile_pic'] ?? asset('images/no-picture-available.jpg') }}"
                                                            height="80" width="90"
                                                            style="border-radius: 50%"><b>{{ $boat['name'] }}</b></h4>
                                                    <div class="table-responsive boat-table"
                                                        data-boat-id="{{ $boat['id'] }}">
                                                        <table class="table table-striped table-bordered base-style">
                                                            <thead>
                                                                <tr>
                                                                    <th># Index</th>
                                                                    <th>Booking Short ID</th>
                                                                    <th>Customer Name</th>
                                                                    <th>Booking Date</th>
                                                                    <th>Booking Price</th>
                                                                    <th>Earnings</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if (!empty($boat['bookings']))
                                                                    @foreach ($boat['bookings'] as $key => $booking)
                                                                        <tr>
                                                                            <td>
                                                                                {{ ++$key }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $booking['booking_short_id'] }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $booking['user']['first_name'] }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $booking['created_at_converted'] }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $booking['booking_price'] }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $booking['owner_earning'] }}
                                                                            </td>
                                                                            <td>
                                                                                <input id="{{ $boat['id'] }}-checkbox"
                                                                                    class="bCheckbox" type="checkbox"
                                                                                    data-owner-id="{{ $user['id'] }}"
                                                                                    checked
                                                                                    data-earning="{{ $booking['owner_earning'] }}"
                                                                                    data-booking-id="{{ $booking['id'] }}"
                                                                                    value="{{ $booking['id'] }}"
                                                                                    name="bookings[]" />
                                                                            </td>

                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="7" style="text-align: center">No
                                                                            Booking Found</td>
                                                                    </tr>
                                                                @endif

                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="5">Total Amount</th>
                                                                    <th colspan="2">SAR <span
                                                                            id="{{ $boat['id'] }}-boat-total">0</span>
                                                                    </th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>

                                                @endforeach
                                            @endif
                                        </div>
                                    </form>
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
        $(document).ready(function() {
            calculateAmount();
            $('.bCheckbox').on('change', function() {
                calculateAmount();
            })
        });

        function calculateAmount() {
            var total_earnings = 0;
            $('.boat-table').each(function(ind, val) {
                var boat_id = $(this).attr('data-boat-id');
                let boat_amount = 0;
                $(":checkbox:checked[id^=" + boat_id + "]").each(function() {
                    let each_row_amount = $(this).attr('data-earning');
                    boat_amount = (+boat_amount) + (+each_row_amount);
                });
                $('#' + boat_id + "-boat-total").html(boat_amount);
                total_earnings = (+total_earnings) + (+boat_amount)
                if ((+ind + 1) == $('.boat-table').length) {
                    $('#total_transaction_amount').html(total_earnings);
                }

            });
        }
    </script>
@endsection
