@extends('layouts.default')
@section('title')
    Create System Settings | Boatek
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
                        Create Boat Types
                    </h3>
                </div>
                <div class="content-header-right col-md-8 col-12">
                    <div class="breadcrumbs-top float-md-right">
                        <div class="breadcrumb-wrapper mr-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Create Boat Type
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
                                        <a href="{{ route('admin.settings.index') }}" type="button"
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

                                        <form method="post" enctype="multipart/form-data"
                                            action="{{ route('admin.settings.store') }}">
                                            @csrf
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName2">TAX (VAT in %)</label>
                                                            <input min="1" step="any"
                                                                placeholder="Add Tax (VAT in percentage)" name="vat"
                                                                type="number" value="{{ old('vat') }}"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName2">Commission Charges (%)</label>
                                                            <input min="1" step="any"
                                                                placeholder="Add Commission Charges in percentage"
                                                                name="boatek_commission_charges" name="vat" type="number"
                                                                value="{{ old('boatek_commission_charges') }}"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName2">Transaction Charges (%)</label>
                                                            <input min="1" step="any"
                                                                placeholder="Add Transaction Charges in percentage"
                                                                name="transaction_charges" type="number"
                                                                value="{{ old('transaction_charges') }}"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName2">Withdraw Duration</label>
                                                            <select class="select2 form-control"
                                                                name="withdraw_scheduled_duration" required>
                                                                <option selected disabled> -- Select -- </option>
                                                                <option value="0">One Week</option>
                                                                <option value="1">Two Week</option>
                                                                <option value="2">One Month</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="row pull-right">
                                                    <a href="{{ route('admin.settings.index') }}"
                                                        class="btn btn-light btn-min-width btn-glow mr-1 mb-1">Cancel</a>
                                                    <button type="submit"
                                                        class="btn btn-success btn-min-width btn-glow mr-1 mb-1">
                                                        Submit
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </form>
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
