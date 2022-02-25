<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\SystemSettingRepository;
use Illuminate\Http\Request;

class SettingConroller extends Controller
{
    public $response;
    public $repository;
    public function __construct(ApiResponse $apiResponse, SystemSettingRepository $repository)
    {
        $this->response = $apiResponse;
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $record = $this->repository->getSystemSettings();

        return view('pages.setting.system-settings', compact('record'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.setting.create-system-settings');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->repository->addSettings($request->all());
        return redirect()->route('admin.settings.index')
            ->with(['success_message' => 'Settings has been added Successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $record = $this->repository->editSystemSettings($uuid);
        return view('pages.setting.edit-system-settings', compact('record'));
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
        $result = $this->repository->updateSystemSettings($request->all(), $uuid);

        if ($result) {
            return back()->with(['success_message' => 'System Settings has been update successfully!.']);
        } else {
            return back()->with(['error_message' => 'Something is going wrong please try again!.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function systemSettings()
    {
        $records = [];
        return view('pages.setting.system-settings', compact('records'));
    }
}
