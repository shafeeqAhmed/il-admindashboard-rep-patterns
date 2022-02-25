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
                                    </h4>
                                    <h1 class="text-center">
                                        
                                    </h1>

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
                              
                            </div>
                            @include('includes.error')
                            <div class="card">
                                <h4 class="card-header">
                                  Boat Action
                                  </h4>
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-md-3 text-left"><h5>Status</h5></div>
                                        <div class="col-md-4">
                                            @if($record['is_approved'])
                                                <div class="badge border-success success badge-border h2">Approved</div>
                                            @else
                                                <div class="badge border-danger danger badge-border">Un Approved</div>
                                            @endif
                                        </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-3 text-left">
                                        <h5>Change Status</h5>
                                     </div>
                                     <div class="col-md-4 ">
                                         @if($record['is_approved'] == 0)
                                         <a href="{{route('admin.approve-boat',['boat_id' => $record['id']])}}" class="btn btn-primary  text-white">Approve</a>
                                         @elseif($record['is_approved'] == 1)
                                         @if($record['is_active'] == 1)
                                             <a href="{{route('admin.approve-boat',['boat_id' => $record['id'],'is_active' => 0])}}" class="btn btn-info  text-white">Deactivate</a>
                                         @elseif($record['is_active'] == 0)
                                             <a href="{{route('admin.approve-boat',['boat_id' => $record['id'],'is_active' => 1])}}" class="btn btn-success  text-white">Activate</a>
                                         @endif
                                         @endif
                                     </div>
                                </div>
                                </div>
                            </div>
                            <div class="card">
                                <h4 class="card-header">
                                  Boat Basic Info
                                  {{-- @if($record['is_approved'] == 0)
                                        <a href="{{route('admin.approve-boat',['boat_id' => $record['id']])}}" class="btn btn-primary float-right text-white">Approve</a>
                                    @elseif($record['is_approved'] == 1)
                                        @if($record['is_active'] == 1)
                                            <a href="{{route('admin.approve-boat',['boat_id' => $record['id'],'is_active' => 0])}}" class="btn btn-info float-right text-white">Deactivate</a>
                                        @elseif($record['is_active'] == 0))
                                            <a href="{{route('admin.approve-boat',['boat_id' => $record['id'],'is_active' => 1])}}" class="btn btn-success float-right text-white">Activate</a>
                                        @endif
                                    @endif --}}
                                </h4>
                                <div class="card-body">
                                    <form action="{{url('update-boat')}}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="inputState">Boat Type</label>
                                                <select name="boat_type_id" id="inputState" class="form-control">
                                                <option selected>Choose</option>
                                                @foreach($boat_types as $key => $value)
                                                    <option value="{{$value->id}}" {{($value->id == $record['boat_type_id'])?'selected':''}}>{{$value->name}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Boat Manufacturer</label>
                                                <input name="manufacturer" placeholder="Boat Manufacturer" value="{{$record['manufacturer']}}" type="text" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Boat Name</label>
                                                <input name="name" placeholder="Boat Name" type="text" value="{{$record['name']}}" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Boat #</label>
                                                <input name="number" placeholder="Boat #" type="text" value="{{$record['number']}}" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Boat Capacity</label>
                                                <input name="capacity" placeholder="Boat Capacity" value="{{$record['capacity']}}" type="number" class="form-control">
                                            </div>
                                       </div>
                                       <input type="hidden" name="boat_uuid" value="{{$record['boat_uuid']}}">
                                       <input type="hidden" name="info_type" value="boat_basic_info">
                                        <button type="submit"  class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card">
                                <h4 class="card-header">
                                   Boat Prices
                                </h4>
                                <div class="card-body">
                                    <form class="form-inline" action="{{url('update-boat')}}" method="post"> 
                                        @csrf
                                        <div class="input-group mb-2 mr-sm-2 col-5">
                                        <label class="mr-2">Price per {{$record['price_unit']}}</label>
                                          <div class="input-group-prepend">
                                            <div class="input-group-text">SAR</div>
                                          </div>
                                          <input type="text" name="price" value="{{$record['price']}}" class="form-control" id="inlineFormInputGroupUsername2" placeholder="Price">
                                        </div>
                                        <input type="hidden" name="info_type" value="price">
                                        <input type="hidden" name="boat_uuid" value="{{$record['boat_uuid']}}">
                                        <button type="submit" class="btn btn-primary mb-2">Update</button>

                                    </form>
                                </div>
                                <div class="card-body">
                                    <h5>Add Discounts</h5>
                                    
                                    <div class="card-body">
                                       @foreach($record['discount'] as $key => $discount)
                                            {{-- <form class="form-inline">
                                                <div class="input-group mb-2 mr-sm-2 col-5">
                                                    <label class="mr-2">Discount After</label>
                                                    <input value="{{$discount['discount_after']}}" type="text"  class="form-control">
                                                    <input value="{{$discount['percentage']}}" type="text"  class="form-control">
                                                </div>
                                                <button type="submit" class="btn btn-primary mb-2">Update</button>
                                            </form> --}}
                                            <form class="form-inline mt-1" action="{{url('update-boat')}}" method="post">
                                                @csrf
                                                <div class="input-group mr-2">
                                                    <label class="mr-2">Discount After</label>
                                                    
                                                    <input name="discount_after" value="{{$discount['discount_after']}}" type="text" style="width:100px" class="form-control" placeholder="Time">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">{{$record['price_unit']}}</span>
                                                        </div>
                                                </div>
                                                <div class="input-group mr-1">
                                                    <input name="percentage" value="{{$discount['percentage']}}" type="text" style="width:100px" class="form-control" placeholder="Price">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">%</span>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="dicount_id" value="{{$discount['id']}}">
                                                <input type="hidden" name="info_type" value="price_discount">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                       @endforeach
                                      
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <h4 class="card-header">
                                    Boat Images
                                    <button type="button" class="btn btn-primary float-right " data-toggle="modal" data-target="#boat_image_modal">
                                     Add New Image
                                   </button>
                                </h4>
                                <div class="card-body">
                                    <div class="row">
                                       @foreach($record['boat_images'] as $key => $boat_image)
                                       <div class="card" >
                                            <div style="position:relative;" class="mx-1">
                                                <a href="{{route('admin.remove-boat-info',['boat_image_id' => $boat_image['id']])}}" style="right:5px; position: absolute;"
                                                    type="submit" class="close AClass">
                                                <span>&times;</span>
                                                </a>
                                                <div class="img-thumbnail rounded">
                                                    <img class="card-img-top" style="height: 150px; width: 150px" alt="" src="https://d1qnkuf3hb9ct2.cloudfront.net/mobileUploads/{{ $boat_image['url']}}">
                                                </div>
                                            </div>
                                            <div class="card-body text-center py-0">
                                                <input {{(($boat_image['is_default'])?'checked':'')}} class="defualt_image" data-boat_id="{{$record['id']}}" data-boat_image_id="{{$boat_image['id']}}"  name="is_default" type="radio"> 
                                            </div>
                                      </div>
                                     
                                       
                                       @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <h4 class="card-header">
                                   Defualt Services
                                   <br><small style="font-size: 11px">Select the services to approve</small>
                                </h4>
                                <div class="card-body">
                                        @if(empty($defualt_services))
                                        <div class="col-md-12">
                                            <p class="">
                                                No default service
                                            </p>
                                        </div>
                                        @else
                                        <form action="{{url('update-boat')}}" method="post">
                                            @csrf
                                            <div class="row">
                                            @foreach($defualt_services as $service)
                                            <div class="col-md-3">
                                                <div class="form-group pb-1">
                                                    <input type="checkbox" id="checkbox_{{$service['id']}}" class="" name="default_services[{{$service['id']}}]" {{(in_array($service['id'],array_column($record['boat_defualt_services'], 'default_service_id')))? 'checked':''}}  />
                                                    <label for="checkbox_{{$service['id']}}" class="font-medium-2 text-bold-600 ml-1">
                                                    {{ $service['name'] }}
                                                    </label>
                                                    <input type="hidden" name="name[{{$service['id']}}]" value="{{$service['name']}}">
                                                    <input type="hidden" name="arabic_name[{{$service['id']}}]" value="{{$service['arabic_name']}}">
                                                </div>
                                            </div>
                                            @endforeach
                                            </div>
                                            <input type="hidden" name="info_type" value="defualt_service">
                                            <input type="hidden" name="boat_id" value="{{$record['id']}}">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                        @endif
                                </div>
                            </div>
                            <div class="card">
                                <h4 class="card-header">
                                  Custom Services
                                  <br><small style="font-size: 11px">Select the services to approve</small>
                                </h4>
                                <div class="card-body">
                                   <form action="{{url('update-boat')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        @if(empty($record['boat_custom_services']))
                                        <div class="col-md-12">
                                            <p class="">
                                                No custom service
                                            </p>
                                        </div>
                                        @else
                                        @foreach($record['boat_custom_services'] as $service)
                                        <div class="col-md-6">
                                            <div class="row pb-1">
                                                <div class="col-md-6">
                                                    <div class="d-flex">
                                                    <input type="checkbox" id="checkbox_{{$service['id']}}" class="mr-1" name="custom_services[{{$service['id']}}]" {{($service['is_approved'] == 1)? 'checked':''}}  />
                                                    <input placeholder="English Name" class="form-control px-1" type="text" value="{{ $service['name'] }}" name="service_name[{{$service['id']}}]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <input placeholder="Arabic Name" class="form-control px-1" type="text" value="{{ $service['arabic_name'] }}" name="service_arabic_name[{{$service['id']}}]">
                                                </div>
                                                
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                        
                                    </div>
                                    <input type="hidden" name="info_type" value="custom_service">
                                    <input type="hidden" name="boat_id" value="{{$record['id']}}">
                                    @if(!empty($record['boat_custom_services']))
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    @endif
                                    
                                   </form>
                                </div>
                            </div>

                            <div class="card">
                                <h4 class="card-header">
                                   Documents
                                   <button type="button" class="btn btn-primary float-right d-none" data-toggle="modal" data-target="#document_modal">
                                    Add New Document
                                  </button>
                                </h4>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($boat_required_documents as $key => $document)
                                        @if(in_array($document['id'],array_column($record['boat_documents'],'boat_required_document_id')))
                                            <?php 
                                                $default_document = $document->getDocument($record['id'])->first();
                                            ?>
                                            <div class="col-md-6  ">
                                                <div class="row mt-2">
                                                <div class="col-md-6 mb-3">
                                                    <a target="_blank" href="https://d1qnkuf3hb9ct2.cloudfront.net/mobileUploads/{{$default_document['url']}}" download="{{$default_document['url']}}">
                                                        @if($default_document['required_documents'] != null)
                                                        {{str_replace("Upload","",$default_document['required_documents']['name'])}}
                                                        <i class="la la-download"></i>
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <input type="hidden" name="info_type" value="document">
                                                    <button  class="btn btn-primary d-none update_document_btn" data-document_id="{{$default_document['id']}}">Update</button>
                                                    <a href="{{route('admin.remove-boat-info',['boat_document_id' => $default_document['id']])}}" type="submit" class="btn btn-primary float-center">Remove</a>
                                                </div>
                                                </div>
                                            </div>
                                        @else
                                            <form action="{{url('update-boat')}}" method="post" class="col-md-6" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="">{{$document['name']}}</label>
                                                            <div class="custom-file">
                                                                <input required name="document" type="file" class="custom-file-input" id="inputGroupFile04">
                                                                <input type="hidden" value="{{$document['id']}}" name="boat_required_document_id">
                                                                <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label style="visibility:hidden"  for="">Upload</label>
                                                        <input  type="hidden" value="new_document" name="info_type">
                                                        <input type="hidden" name="boat_id" value="{{$record['id']}}">
                                                        <button type="submit"  class="btn btn-primary" >Upload</button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    @endforeach
                                    </div>
                                       
                                        
                                        {{-- @foreach($record['boat_documents'] as $key => $boat_document)
                                            @csrf
                                               <div class="col-md-6  ">
                                                   <div class="row mt-2">
                                                    <div class="col-md-6 mb-3">
                                                        <a target="_blank" href="https://d1qnkuf3hb9ct2.cloudfront.net/mobileUploads/{{$boat_document['url']}}" download="{{$boat_document['url']}}">
                                                            @if($boat_document['required_documents'] != null)
                                                            {{str_replace("Upload","",$boat_document['required_documents']['name'])}}
                                                            <i class="la la-download"></i>
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <div class="col-md-6 ">
                                                        <input type="hidden" name="info_type" value="document">
                                                        <a href="{{route('admin.remove-boat-info',['boat_document_id' => $boat_document['id']])}}" type="submit" class="btn btn-primary float-center">Remove</a>
                                                    </div>
                                                   </div>
                                               </div>
                                            
                                        @endforeach --}}


                                   
                                </div>
                            </div>
                            <div class="card">
                                <h4 class="card-header">
                                   Boat Captains
                                   <button type="button" class="btn btn-primary float-right " data-target="#new_captain_modal" data-toggle="modal">
                                    Add New Captain
                                  </button>
                                </h4>
                               <div class="card-body">
                                    <div class="row">
                                        @foreach ($record['boat_captains'] as $item)
                                        <a data-captain_uuid="{{$item['captain_user']['id']}}" data-captain_first_name="{{$item['captain_user']['first_name']}}" data-captain_last_name="{{$item['captain_user']['last_name']}}" data-captain_email="{{$item['captain_user']['email']}}"  href="javascript:void(0)" style="position:relative;" class="update_captain_modal col-md-3">
                                            {{-- <button style="right:5px; position: absolute;"
                                                type="submit" class="close AClass">
                                               <span>&times;</span>
                                            </button> --}}
                                            <div class="card mx-0 text-center" >
                                                <img style="width:170px;height:170px" class="card-img-top rounded-circle img-thumbnail" src="https://d1qnkuf3hb9ct2.cloudfront.net/mobileUploads/{{$item['captain_user']['profile_pic'] }}" alt="Card image cap">
                                                <h5 class="mt-1"> {{$item['captain_user']['first_name']}} {{$item['captain_user']['last_name']}}</h5>
                                            </div>
                                        </a>
                                           
                                        @endforeach

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
{{-- Boat Image Modal--}}
<div class="modal fade" id="boat_image_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Boat Image</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{url('update-boat')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="">Image</label>
                    <div class="custom-file">
                        <input required name="image" type="file" class="custom-file-input" id="inputGroupFile04">
                        <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                    </div>
                </div>
                <input type="hidden" name="info_type" value="boat_image">
                <input type="hidden" name="boat_id" value="{{$record['id']}}">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        
      </div>
    </div>
</div>
{{--Update Captain Modal --}}
<div class="modal fade" id="update_captain_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Update Captain</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{url('update-boat')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">First Name</label>
                        <input required id="captain_first_name" name="first_name" placeholder="First Name"  type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Last Name</label>
                        <input required id="captain_last_name" name="last_name" placeholder="Last Name"  type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="">Email</label>
                        <input required id="captain_email" name="email" placeholder="Email"  type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="">Update Image</label>
                        <div class="custom-file">
                            <input name="image" type="file" class="custom-file-input" id="inputGroupFile04">
                            <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="info_type" value="captain">
                <input type="hidden" name="captain_id" value="" id="captain_id">
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
        
      </div>
    </div>
</div>

{{--Add New Captain Modal --}}
<div class="modal fade" id="new_captain_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add New Captain</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{url('update-boat')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">First Name</label>
                        <input required  name="first_name" placeholder="First Name"  type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="">Last Name</label>
                        <input required  name="last_name" placeholder="Last Name"  type="text" class="form-control">
                    </div>
                    
                    <div class="form-group col-md-12">
                        <label for="">Email</label>
                        <input required  name="email" placeholder="Email"  type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="">Image</label>
                        <div class="custom-file">
                            <input name="image" type="file" class="custom-file-input" id="inputGroupFile04">
                            <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="info_type" value="new_captain">
                <input type="hidden" name="boat_id" value="{{$record['id']}}" id="">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        
      </div>
    </div>
</div>

{{-- Document Model --}}
<div class="modal fade" id="document_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add New Document</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{url('update-boat')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group ">
                    <label for="">Choose Document Type</label>
                    <select required class="form-control" name="boat_required_document_id">
                        @foreach($boat_required_documents as $key => $value)
                            <option value="{{$value['id']}}">{{$value['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Choose File</label>
                    <div class="custom-file">
                        <input required name="document" type="file" class="custom-file-input" id="inputGroupFile04">
                        <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                    </div>
                </div>
                <input type="hidden" name="boat_id" value="{{$record['id']}}">
                <input type="hidden" name="info_type" value="new_document">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        
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
    <script>
         $('.custom-file input').change(function (e) {
        if (e.target.files.length) {
            $(this).next('.custom-file-label').html(e.target.files[0].name);
        }
    });

    $('.update_captain_modal').on('click',function(){
        var captain_id = $(this).data('captain_uuid');
        var captain_first_name = $(this).data('captain_first_name');
        var captain_last_name = $(this).data('captain_last_name');
        var captain_email = $(this).data('captain_email');
        $('#captain_first_name').val(captain_first_name);
        $('#captain_last_name').val(captain_last_name);
        $('#captain_email').val(captain_email);
        $('#captain_id').val(captain_id);
        $('#update_captain_modal').modal('show');
    });

    $('.defualt_image').on('click',function(){
        var boat_image_id = $(this).data('boat_image_id');
        var boat_id = $(this).data('boat_id');
        window.location.href = "{{route('admin.boat-default-image')}}"+"?boat_id="+boat_id+"&&boat_image_id="+boat_image_id;
    })
    </script>
    <!-- END: Page JS-->
@endsection
