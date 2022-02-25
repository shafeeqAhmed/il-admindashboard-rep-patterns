@extends('layouts.default')
@section('title')
    Dashboard
@endsection

@section('local-style')
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/theme/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <style>
        .w-color {
            color: white !important;
        }
    </style>
    <!-- END: Vendor CSS-->
@endsection

@section('body')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">
                        <img src="{{$user->profile_pic  ?? asset('images/no-picture-available.jpg')}}" height="80" width="80" style="border-radius: 50%">
                        {{$user->first_name}} {{$user->last_name}} | {{$user->email}}
                    </h3>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="breadcrumbs-top float-md-right">
                        <div class="breadcrumb-wrapper mr-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Owner Detail
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
                                        <a href="{{ route('admin.boatOwners.index') }}" type="button"
                                            class="btn btn-bg-gradient-x-purple-blue">
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

                                        <div class="row">
                                            <div class="col-xl-3 col-lg-6 col-md-12">
                                                <a
                                                    href="{{ route('admin.ownerBoatBookings.all', ['uuid' => $user['user_uuid'], 'type' => 'all']) }}">
                                                    <div class="card bg-primary">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <div class="media d-flex">
                                                                    <div class="align-self-top">
                                                                        <i
                                                                            class="icon-book-open icon-opacity w-color font-large-4"></i>
                                                                    </div>
                                                                    <div
                                                                        class="media-body text-right align-self-bottom mt-3">
                                                                        <span class="d-block mb-1 font-medium-1 w-color">All
                                                                            Transactions</span>
                                                                        <h1 class="mb-0 w-color">
                                                                            SAR {{ $stats['all'] }}
                                                                        </h1>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-xl-3 col-lg-6 col-md-12">
                                                <a
                                                    href="{{ route('admin.ownerBoatBookings.all', ['uuid' => $user['user_uuid'], 'type' => 'pending']) }}">
                                                    <div class="card bg-warning">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <div class="media d-flex">
                                                                    <div class="align-self-top">
                                                                        <i
                                                                            class="icon-book-open icon-opacity w-color font-large-4"></i>
                                                                    </div>
                                                                    <div
                                                                        class="media-body text-right align-self-bottom mt-3">
                                                                        <span class="d-block mb-1 font-medium-1 w-color">Pending Balance</span>
                                                                        <h1 class="mb-0 w-color">
                                                                            SAR {{ $stats['pending'] }}
                                                                        </h1>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-xl-3 col-lg-6 col-md-12">
                                                <a href="{{ route('admin.boatOwners.availableTransactions', ['uuid' => $user['user_uuid']]) }}">
                                                    <div class="card bg-info">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <div class="media d-flex">
                                                                    <div class="align-self-top">
                                                                        <i
                                                                            class="icon-book-open icon-opacity font-large-4 w-color"></i>
                                                                    </div>
                                                                    <div
                                                                        class="media-body text-right align-self-bottom mt-3">
                                                                        <span
                                                                            class="d-block mb-1 font-medium-1 w-color">Available
                                                                            Balance</span>
                                                                        <h1 class="mb-0 w-color">
                                                                            SAR {{ $stats['available'] }}
                                                                        </h1>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-xl-3 col-lg-6 col-md-12">
                                                <a
                                                    href="{{ route('admin.ownerBoatBookings.all', ['uuid' => $user['user_uuid'], 'type' => 'transfered']) }}">
                                                    <div class="card bg-success">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <div class="media d-flex">
                                                                    <div class="align-self-top">
                                                                        <i
                                                                            class="icon-book-open icon-opacity font-large-4 w-color"></i>
                                                                    </div>
                                                                    <div
                                                                        class="media-body text-right align-self-bottom mt-3">
                                                                        <span
                                                                            class="d-block mb-1 font-medium-1 w-color">Transferred
                                                                            Balance</span>
                                                                        <h1 class="info mb-0 w-color">
                                                                            SAR {{ $stats['transferred'] }}
                                                                        </h1>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <h4><b>Transferred Payments:</b></h4>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered base-style">
                                                <thead>
                                                    <tr>
                                                        <th># Index</th>
                                                        <th>Receipt Id</th>
                                                        <th>Receipt</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($transferred_payments as $payment)
                                                        <tr>
                                                            <td>{{ $loop->index + 1 }}</td>
                                                            <td>{{ $payment['receipt_id'] }}</td>
                                                            <td>
                                                                @if ($payment['receipt_url'] != '')
                                                                    <a href="{{ $payment['receipt_url'] }}"
                                                                        target="_blank">
                                                                        <i class="ft-file"></i> <b>Open</b>
                                                                    </a>
                                                                @else
                                                                    N-A
                                                                @endif
                                                            </td>
                                                            <td>{{ $payment['amount'] }}</td>
                                                            @php
                                                                $converted_date = \Carbon\Carbon::parse(date('d-m-Y h:i a', strtotime($payment['created_at'])) . ' ' . 'UTC')
                                                                    ->tz('Asia/Riyadh')
                                                                    ->format('d-m-Y h:i a');
                                                            @endphp
                                                            <td>
                                                                {{ $converted_date }}
                                                            </td>
                                                            <td>
                                                                <a type="button"
                                                                    href="{{ route('admin.transferPayments.details', ['uuid' => $payment['withdraw_uuid']]) }}"
                                                                    class="btn btn-outline-primary btn-sm">
                                                                    View detail
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <th class="text-center" colspan="6">No Transferred Payments
                                                                found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th># Index</th>
                                                        <th>Receipt Id</th>
                                                        <th>Receipt</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <h4 class="mt-2"><b>Boats Detail:</b></h4>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered base-style">
                                                <thead>
                                                    <tr>
                                                        <th># Index</th>
                                                        <th>Boat Name</th>
                                                        <th>Owner Name</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($records as $key => $record)
                                                        <tr>
                                                            <td>
                                                                {{ ++$key }}
                                                            </td>

                                                            <td>
                                                                {{ $record['name'] . ' ' . $record['number'] ?? '' }}
                                                            </td>
                                                            <td>
                                                                {{ ucfirst($user['first_name']) }}
                                                            </td>
                                                            <td>
                                                                {{ $record['BoatType']['name'] }}
                                                            </td>
                                                            <td>
                                                                @if ($record['is_active'])
                                                                    <div class="badge border-success success badge-border">
                                                                        Active</div>
                                                                @else
                                                                    <div class="badge border-danger danger badge-border">
                                                                        Inactive</div>
                                                                @endif
                                                                {{ $record['phone_number'] }}
                                                            </td>
                                                            <td class="text-center">
                                                                <a type="button" href="#"
                                                                    class="btn btn-outline-primary btn-sm">
                                                                    View detail
                                                                </a>

                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th># Index</th>
                                                        <th>Boat Name</th>
                                                        <th>Owner Name</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
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

    {{-- START: Model --}}
    <div class="modal fade text-left" id="boat_type_modal" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="basicModalLabel1">Delete Boat Type</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Do you want to delete this Boaty Type? !</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="yesDeleteBoatType">Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- END: Model --}}

    <!-- END: Content-->
@endsection

@section('local-script')
    <!-- BEGIN: Page JS-->
    <script src="{{ asset('public/theme/app-assets/js/scripts/tables/datatables/datatable-styling.js') }}"
        type="text/javascript"></script>
    <!-- END: Page JS-->
@endsection
