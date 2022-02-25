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
                        Edit Booking
                    </h3>
                </div>
                <div class="content-header-right col-md-8 col-12">
                    <div class="breadcrumbs-top float-md-right">
                        <div class="breadcrumb-wrapper mr-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Edit Booking
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
                                        <a href="{{route('admin.bookings.index',['type'=>$type])}}" type="button"
                                           class="btn btn-bg-gradient-x-purple-blue">
                                            Back
                                        </a>
                                    </h4>
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
                                       {{-- <form method="post" enctype="multipart/form-data" action="{{route('admin.boatTypes.update',['boatType'=>$type['boat_type_uuid']])}}"> --}}
                                            @method('put')
                                            @csrf
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">Change Status</label>
                                                        <select class="form-control" name="" id="">
                                                            <option value="">Change </option>
                                                        </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName2">Booking Short Id :</label>
                                                            <input type="text" name="boat_name"
                                                                   placeholder="Enter Boat Type Name" class="form-control"
                                                                   value="{{ $record['booking_short_id'] }}"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                            </fieldset>
                                            
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName2">Boat  Name :</label>
                                                            <input type="text" name="boat_name"
                                                                   placeholder="Enter Boat Type Name" class="form-control"
                                                                   value="{{ $record['boat']['name'].' '.$record['boat']['number'] }}"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName2">Boat  Type :</label>
                                                            <input type="text" name="boat_name"
                                                                   placeholder="Enter Boat Type Name" class="form-control"
                                                                   value="{{ $record['boat']['boat_type']['name'].' '.$record['boat']['number'] }}"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName2">Customer Name :</label>
                                                            <input type="text" name="boat_name"
                                                                   placeholder="Enter Boat Type Name" class="form-control"
                                                                   value="{{ $record['user']['first_name'].' '.$record['user']['last_name'] ?? '' }}"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName2">Start Date:</label>
                                                            <input type="date" name="start_date_time"
                                                                   placeholder="Enter End Date Time" class="form-control"
                                                                   value="{{ date('Y-m-d',$record['start_date_time']) }}"
                                                                   disabled
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName2">End Date  :</label>
                                                            <input type="date" name="end_date_time"
                                                                   placeholder="Enter End Date Time" class="form-control"
                                                                   value="{{ date('Y-m-d',$record['end_date_time']) }}"
                                                                   disabled
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                            </fieldset>

                                        <fieldset>
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="firstName2">Start Time :</label>
                                                        <input type="" name="start_time"
                                                               placeholder="Enter End Start Time" class="form-control"
                                                               value="{{$common_helper_obj->convertUTCDateTimeToLocalTimezone($record['start_date_time'],$record['saved_timezone'],$record['local_timezone'],'H:i:A') }}"
                                                               disabled
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </fieldset>
                                        <fieldset>
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="firstName2">End Time :</label>
                                                        <input type="" name="end_time"
                                                               placeholder="Enter End Time" class="form-control"
                                                               value="{{$common_helper_obj->convertUTCDateTimeToLocalTimezone($record['end_date_time'],$record['saved_timezone'],$record['local_timezone'],'H:i:A') }}"
                                                               disabled
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </fieldset>

                                       {{-- </form> --}}
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
    <div class="modal fade text-left" id="boat_type_modal" tabindex="-1" role="dialog"
         aria-labelledby="basicModalLabel1" aria-hidden="true">
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
