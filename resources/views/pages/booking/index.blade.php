@extends('layouts.default')
@section('title')
    Booking List
@endsection

@section('local-style')
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css"
          href="{{asset('/theme/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
    <!-- END: Vendor CSS-->
@endsection

<?php
    use App\Traits\CommonHelper;
    use Carbon\Carbon;
    $common_helper_obj = new CommonHelper;
?>

@section('body')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title">
                        {{ ucfirst($type) .' Booking' }}
                    </h3>
                </div>
                <div class="content-header-right col-md-8 col-12">
                    <div class="breadcrumbs-top float-md-right">
                        <div class="breadcrumb-wrapper mr-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    {{ ucfirst($type) .' Booking' }}
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
                                    <a class="heading-elements-toggle"><i
                                            class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            {{-- <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li> --}}
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            {{-- <li><a data-action="close"><i class="ft-x"></i></a></li> --}}
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
                                                    <th>Booking ID</th>
                                                    <th>Boat Name</th>
                                                    <th>Customer Name</th>
                                                    <th>Start DateTime</th>
                                                    <th>End DateTime</th>
                                                    <th>Booking Time</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($records as $key => $record)
                                                    <tr>
                                                        <td>
                                                            {{$record['booking_short_id']}}
                                                        </td>
                                                        <td>
                                                            {{ $record['boat']['name'].' - ' ?? ''  }}
                                                            {{ $record['boat']['number'] ?? ''  }}
                                                        </td>
                                                        <td>
                                                            {{ $record['user']['first_name'] }}
                                                        </td>
                                                        
                                                        {{-- @dd($common_helper_obj->convertUTCDateTimeToLocalTimezone($record['start_date_time'],$record['saved_timezone'],$record['local_timezone']),Carbon::parse($record['start_date_time'])->format('Y-m-d H:i:a')) --}}
                                                        <td>
                                                            {{ $common_helper_obj->convertUTCDateTimeToLocalTimezone($record['start_date_time'],$record['saved_timezone'],$record['local_timezone'],'Y-m-d H:i:A') }}
                                                        </td>
                                                        <td>
                                                            {{ $common_helper_obj->convertUTCDateTimeToLocalTimezone($record['end_date_time'],$record['saved_timezone'],$record['local_timezone'],'Y-m-d H:i:A') }}
                                                        </td>
                                                        <td>
                                                            {{ $common_helper_obj->convertDateTimeToLocalTimezone($record['created_at'], 'UTC', $record['local_timezone'])}}
                                                        </td>
                                                        <td>
                                                            {{ $record['status'] }}
                                                        </td>
                                                        <td>
                                                            <a type="button" href="{{ route('admin.bookings.edit',['uuid'=>$record['booking_uuid'],'type'=>$type]) }}" class="btn mr-1 mb-1 btn-outline-primary btn-sm" >
                                                                View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>Booking ID</th>
                                                    <th>Boat Name</th>
                                                    <th>Customer Name</th>
                                                    <th>Start DateTime</th>
                                                    <th>End DateTime</th>
                                                    <th>Booking Time</th>
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
