@extends('layouts.default')
@section('title')
    System Settings | Boatek
@endsection

@section('local-style')
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/theme/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <!-- END: Vendor CSS-->
@endsection
@if (isset($record['withdraw_scheduled_duration']))
    @if ($record['withdraw_scheduled_duration'] == 0)
        @php $duration = 'One Week' @endphp
    @elseif (isset($record['withdraw_scheduled_duration']) == 1)
        @php $duration = 'Two Week' @endphp
    @elseif (isset($record['withdraw_scheduled_duration']) == 2)
        @php $duration = 'One Month' @endphp
    @endif
@endif

@section('body')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title">
                        Create System Setting
                    </h3>
                </div>
                <div class="content-header-right col-md-8 col-12">
                    <div class="breadcrumbs-top float-md-right">
                        <div class="breadcrumb-wrapper mr-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Create System Setting
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
                                    @if (empty($record))
                                        <h4 class="card-title">
                                            <a type="button" href="{{ route('admin.settings.create') }}"
                                                class="btn btn-bg-gradient-x-purple-blue">Create System Setting
                                            </a>
                                        </h4>
                                    @endif
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
                                                        <th> </th>
                                                        <th> Status</th>
                                                        <th> VAT</th>
                                                        <th> Boat Commission Charges</th>
                                                        <th> Transaction Charges</th>
                                                        <th> Withdraw Scheduled Duration</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($record))
                                                        <tr>
                                                            <td>
                                                                <a class="btn btn-bg-gradient-x-blue-cyan"
                                                                    href="{{ route('admin.settings.edit', $record['system_setting_uuid']) }}">
                                                                    <i class="ft-edit-3 text-white"></i>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                @if ($record['is_active'])
                                                                    <div class="badge border-success success badge-border">
                                                                        Active</div>
                                                                @else
                                                                    <div class="badge border-danger danger badge-border">
                                                                        Inactive</div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ $record['vat'] }} %
                                                            </td>
                                                            <td>
                                                                {{ $record['boatek_commission_charges'] }} %
                                                            </td>
                                                            <td>
                                                                {{ $record['transaction_charges'] }} %
                                                            </td>
                                                            <td>
                                                                {{ $duration }}
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <th colspan="6" class="text-center">
                                                                System Setting not found
                                                            </th>
                                                        </tr>
                                                    @endif
                                                </tbody>
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
                    <h4 class="modal-title" id="basicModalLabel1">Message Codes</h4>
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
