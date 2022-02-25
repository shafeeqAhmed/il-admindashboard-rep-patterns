<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Models\Boat;
use App\Models\BoatCaptain;
use App\Models\BoatDefaultService;
use App\Models\BoatDocument;
use App\Models\BoatImage;
use App\Models\BoatPriceDiscount;
use App\Models\BoatRequiredDocument;
use App\Models\BoatService;
use App\Models\BoatType;
use App\Models\User;
use App\Repositories\BoatRepository;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;

class BoatController extends Controller
{
    public $response;
    public $repository;
    public function __construct(ApiResponse $apiResponse, BoatRepository $repository)
    {
        $this->response = $apiResponse;
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type = null)
    {
        $is_approved = $type == 'approved' ? 1 : 0;
        $records = $this->repository->getAdminBoats('is_approved', $is_approved);
        return view('pages.boat.index', compact('records', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $record = (new BoatRepository())->makeModel()->getBoatDetail($uuid);
        $boat_required_documents = BoatRequiredDocument::get();
        $boat_types = BoatType::get();
        $defualt_services = BoatDefaultService::get();
        return view('pages.boat.dd',compact('record','boat_required_documents','boat_types','defualt_services'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $result = $this->repository->getByColumn($uuid, 'boat_uuid')->update(['is_approved' => !(int)$request->is_approved]);
        if ($result) {
            return $this->response->webResponse('Boat Update Successfully!');
        } else {
            return $this->response->webResponse('There something going wrong please try again!', false);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function boatBookingList($boat_uuid)
    {
        $boat_bookings = (new BoatRepository())->getBoatBookingsList($boat_uuid);
        return view('pages.boat.booking-list', compact('boat_bookings'));
    }
    /**
     * boat booking withdraw
     *
     * @return \Illuminate\Http\Response
     */
    public function boatBookingWithdraw(Request $request)
    {
        $this->repository->addBoatBookingWithdraw($request->all());
        return redirect()->back()->with(['success_message' => 'Withdraw has been submitted!']);
    }

    public function updateBoat(Request $request)
    {
        if($request->info_type == "boat_basic_info")
        {
            $data = $this->prepareParamsForBoatBasicInfo($request->all());
            $result = Boat::where('boat_uuid',$request->boat_uuid)->update($data);
            return redirect()->back()->with('success_message','Boat Basic Info Updated successfully');
        }
        if($request->info_type == "price")
        {
            // $data = $this->prepareParamsForBoatPriceInfo($request->all());
            $result = Boat::where('boat_uuid',$request->boat_uuid)->update(['price'=>$request['price']]);
            return redirect()->back()->with('success_message','Boat Price updated successfully');
        }
        if($request->info_type == "price_discount")
        {
            // dd($request->all());
            // $data = $this->prepareParamsForBoatPriceInfo($request->all());
            $result = BoatPriceDiscount::where('id',$request->dicount_id)->update(['discount_after' => $request['discount_after'],'percentage' => $request['percentage']]);
            return redirect()->back()->with('success_message','Boat discount updated successfully');
        }
        if($request->info_type == "new_document")
        {
            // dd($request->all());
            $data = $this->prepareParamsForBoatDocument($request);
            $result = BoatDocument::updateOrCreate(
                [
                   'boat_id'   => $request->boat_id,
                   'boat_required_document_id' => $request->boat_required_document_id,
                ],
                $data
            );
            return redirect()->back()->with('success_message','Boat documents added successfully');
        }
        if($request->info_type == "captain")
        {
            $validator = \Validator::make($request->all(), [
                'email' => "required|email|unique:users,email,$request->captain_id",
            ]);
            if($validator->fails())
            {
                return redirect()->back()->with('error_message',Arr::first(Arr::flatten($validator->messages()->get('email'))));
            }
            $data = $this->prepareParamsForBoatCaptain($request);
            // dd($data,$request->all());
            $result = User::where('id',$request['captain_id'])->update($data);
            return redirect()->back()->with('success_message','Boat captain updated successfully');
        }
        if($request->info_type == "boat_image")
        {
            $data = $this->prepareParamsForBoatImages($request);
            // dd($data,$request->all());
            $result = BoatImage::create($data);
            return redirect()->back()->with('success_message','Boat image added successfully');
        }

        if($request->info_type == "new_captain")
        {
            $data = $this->prepareParamsForBoatNewCaptain($request);
            $user = User::create($data);
            $data = $this->prepareParamsForCaptain($user,$request);
            BoatCaptain::create($data);
            // dd($data,$request->all());
            return redirect()->back()->with('success_message','Boat captain added successfully');
        }

        if($request->info_type == "custom_service")
        {
            $data = $this->prepareParamsForBoatCustomServices($request);
            return redirect()->back()->with('success_message','Boat custom services updated successfully');
        }
        if($request->info_type == "defualt_service")
        {
            
            $data = $this->prepareParamsForBoatDefaultServices($request);
            BoatService::insert($data);
            return redirect()->back()->with('success_message','Boat default services updated successfully');
        }
        
    }
    public function removeBoatInfo(Request $request)
    {
        if(isset($request['boat_document_id']))
        {
            BoatDocument::where('id',$request['boat_document_id'])->delete();
            return redirect()->back()->with('success_message','Boat document deleted successfully');
        }
        if(isset($request['boat_image_id']))
        {
            BoatImage::where('id',$request['boat_image_id'])->delete();
            return redirect()->back()->with('success_message','Boat image deleted successfully');
        }
    }
    public function prepareParamsForBoatBasicInfo($request)
    {
        $data = [];
        $data['boat_type_id'] = $request['boat_type_id'];
        $data['manufacturer'] = $request['manufacturer'];
        $data['name'] = $request['name'];
        $data['number'] = $request['number'];
        $data['capacity'] = $request['capacity'];
        return $data;
    }
    public function prepareParamsForBoatDocument($request)
    {
        $data = [];
        $file = $request->file('document');
        $result = CommonHelper::uploadSingleImage($file, CommonHelper::$s3_image_paths['mobile_uploads'], $pre_fix = '', $server = 's3');
        $data['boat_id'] = $request['boat_id'];
        $data['boat_required_document_id'] = $request['boat_required_document_id'];
        $data['boat_document_uuid'] = Uuid::uuid4()->toString();
        $data['type'] = $file->getClientOriginalExtension();
        $data['url'] = $result['file_name'];
        return $data;
    }

    public function prepareParamsForBoatCaptain($request)
    {
        $data = [];
       
        if($request->file('image') != null)
        {
            $file = $request->file('image');
            $result = CommonHelper::uploadSingleImage($file, CommonHelper::$s3_image_paths['mobile_uploads'], $pre_fix = '', $server = 's3');
            $data['profile_pic'] =$result['file_name'];
        }
        $data['first_name'] = $request['first_name'];
        $data['last_name'] = $request['last_name'];
        $data['email'] = $request['email'];
        return $data;
    }
    public function prepareParamsForBoatImages($request)
    {
        $data = [];
        if($request->file('image') != null)
        {
            $file = $request->file('image');
            $result = CommonHelper::uploadSingleImage($file, CommonHelper::$s3_image_paths['mobile_uploads'], $pre_fix = '', $server = 's3');
            $data['boat_image_uuid'] = Uuid::uuid4()->toString();
            $data['url'] =$result['file_name'];
            $data['boat_id'] =$request['boat_id'];
            return $data;
        }
    }

    public function prepareParamsForBoatNewCaptain($request)
    {
        $data = [];
        if($request->file('image') != null)
        {
            $file = $request->file('image');
            $result = CommonHelper::uploadSingleImage($file, CommonHelper::$s3_image_paths['mobile_uploads'], $pre_fix = '', $server = 's3');
            $data['profile_pic'] =$result['file_name'];
        }
        $data['user_uuid'] = Uuid::uuid4()->toString();
        $data['first_name'] = $request['first_name'];
        $data['last_name'] = $request['last_name'];
        $data['email'] =$request['email'];
        $data['role'] = 'captain';
        $data['status'] = 'active';
        $data['is_active'] = 1;
        return $data;
    }
    public function prepareParamsForCaptain($user,$request)
    {
        $data = [];
        $data['boat_id'] = $request['boat_id'];
        $data['user_id'] = $user['id'];
        $data['captain_uuid'] = Uuid::uuid4()->toString();
        return $data;
    }
    

    public function prepareParamsForBoatCustomServices($request)
    {

        $data = [];
        if(isset($request['custom_services']))
        {
            foreach($request['custom_services'] as $key => $value)
            {
            
                if($value == 'on')
                {
                    BoatService::where('id',$key)->update(['is_approved' => 1]);
                }
                else
                {
                    BoatService::where('id',$key)->update(['is_approved' => 0]);
                }
                
            }
        }
        foreach($request['service_name'] as $key => $value)
        {
            BoatService::where('id',$key)->update(['name' => $value, 'arabic_name' =>$request['service_arabic_name'][$key]]);
        }
        return $data;
    }
    public function prepareParamsForBoatDefaultServices($request)
    {

        $data = [];
        if(isset($request['default_services']))
        {
            BoatService::where('boat_id',$request['boat_id'])->whereNotNull('default_service_id')->delete();
            foreach($request['default_services'] as $key => $value)
            {
                if($value == 'on')
                {
                    $data[] = [
                        'boat_id' => $request['boat_id'],
                        'boat_service_uuid' => Uuid::uuid4()->toString(),
                        'name' => $request['name'][$key],
                        'arabic_name' => $request['arabic_name'][$key],
                        'default_service_id' => $key,
                        'is_approved' => 1,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                    ];
                  
                }
            }
        }
        return $data;
    }
    public function approveBoat(Request $request)
    {
        if($request['boat_id'] != null)
        {
            if(isset($request['is_active']))
            {
                Boat::where('id',$request['boat_id'])->update(['is_active' => $request['is_active']]);
                return redirect()->back()->with('success_message','Boat status changed successfully');
            }
            else
            {
                Boat::where('id',$request['boat_id'])->update(['is_approved' => 1]);
                return redirect()->back()->with('success_message','Boat Approved successfully');
            }
          
        }
    }

    public function defualtBoatImage(Request $request)
    {
        if($request->all() != null)
        {
            BoatImage::where('boat_id',$request['boat_id'])->update(['is_default'=>0]);
            $boat_image = BoatImage::where('id',$request['boat_image_id'])->first();
            if($boat_image != null)
            {
                $boat_image->is_default = 1;
                $boat_image->save();
                Boat::where('id',$boat_image->boat_id)->update(['profile_pic' => $boat_image->url]);
                return redirect()->back()->with('success_message','Boat default image changed successfully');
            }
            return redirect()->back()->with('error_message','Something went wrong');
            
        }
    }
}
