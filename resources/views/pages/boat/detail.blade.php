@extends('layouts.default')
@section('title')
    Dashboard
@endsection

@section('local-style')
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css"
          href="{{asset('/theme/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
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
                        Boat Detail
                    </h3>
                </div>
                <div class="content-header-right col-md-8 col-12">
                    <div class="breadcrumbs-top float-md-right">
                        <div class="breadcrumb-wrapper mr-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Boat Detail
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
                                        <a href="{{route('admin.dashboard')}}" type="button"
                                           class="btn btn-bg-gradient-x-purple-blue">
                                            Back
                                        </a>
                                        <a href="{{route('admin.boatBooking.show', ['uuid'=>$record['boat_uuid']])}}" type="button"
                                           class="btn btn-bg-gradient-x-blue-green">
                                            View Bookings
                                        </a>
                                    </h4>
                                    <h1 class="text-center">
                                        @if($record['is_approved'])
                                            <div class="badge border-success success badge-border">Approved</div>
                                        @else
                                            <div class="badge border-danger danger badge-border">Un Approved</div>
                                        @endif
                                    </h1>

                                    <a class="heading-elements-toggle"><i
                                            class="la la-ellipsis-v font-medium-3"></i></a>
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
                                        <div class="card-content collapse show">
                                            <div class="card-body">
                                                <form action="#" class="boat-detail-tab-steps">
                                                    <input type="hidden" id="form_wizard_boat_uuid" value="{{$record['boat_uuid']}}" >
                                                    <input type="hidden" id="form_wizard_boat_action_type" value="{{ $record['is_approved'] }}" >
                                                <!-- Step 1 -->
                                                    @include('pages.boat.steps.step-1')
                                                <!-- Step 2 -->
                                                    @include('pages.boat.steps.step-2')
                                                <!-- Step 3 -->
                                                    @include('pages.boat.steps.step-3')
                                                <!-- Step 4 -->
                                                    @include('pages.boat.steps.step-4')
                                               <!-- Step 5 -->
                                                    @include('pages.boat.steps.step-5')
                                                <!-- Step 6 -->
                                                    @include('pages.boat.steps.step-6')

                                                </form>
                                            </div>
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

{{--    START: Model --}}
    <div class="modal fade text-left" id="boat_type_modal" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel1" aria-hidden="true">
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

    {{--    END: Model --}}

    <!-- END: Content-->
@endsection

@section('local-script')
    <!-- BEGIN: Page JS-->
    <script src="{{ asset('public/theme/app-assets/js/scripts/tables/datatables/datatable-styling.js') }}"
            type="text/javascript"></script>
    <!-- END: Page JS-->
@endsection
