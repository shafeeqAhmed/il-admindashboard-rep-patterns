<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoatType;
use App\Repositories\BoatTypeRepository;
use Illuminate\Http\Request;
use App\Http\Responses\Responses\ApiResponse;

class BoatTypeController extends Controller
{
    public $response;
    public $boatTypeRepository;
    public function __construct(ApiResponse $apiResponse, BoatTypeRepository $boatTypeRepository)
    {
        $this->response = $apiResponse;
        $this->boatTypeRepository = $boatTypeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = $this->boatTypeRepository->getAdminBoatTypes();
        return view('pages.boat.type.index',compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.boat.type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:boat_types',
            'pic' => 'nullable'
        ]);
        $this->boatTypeRepository->storeAdminBoatTypes($validatedData);
        return redirect()->route('admin.boatTypes.index')
            ->with(['success_message'=>'Boat Type has been added Successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($boat_type_uuid)
    {
        $type = $this->boatTypeRepository->getByColumn($boat_type_uuid,'boat_type_uuid');
        return view('pages.boat.type.edit',compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $uuid)
    {
        $request->validate([
            'name' => "required|unique:boat_types,boat_type_uuid,$uuid",
            'pic' => 'nullable|file'
        ]);
        $result = $this->boatTypeRepository->updateAdminBoatTypes($request->all(),$uuid);
        if($result) {
            return back()->with([
                'success_message' => 'Boat Type has been update successfully!.',
            ]);
        }else {
            return back()->with([
                'error_message' => 'Something is going wrong please try again!.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string  $boat_type_uuid
     * @return array
     */
    public function destroy($boat_type_uuid)
    {
            $result = $this->boatTypeRepository->getByColumn($boat_type_uuid,'boat_type_uuid')->update(['is_deleted'=>1,'is_active'=>0]);
            if($result) {
                return $this->response->webResponse('Boat Type Deleted Successfully!');
            }else {
                return $this->response->webResponse('There something going wrong please try again!',false);
            }
    }
}
