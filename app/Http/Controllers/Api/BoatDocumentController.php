<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatDocumentRepository;
use Illuminate\Http\Request;

class BoatDocumentController extends Controller
{
    protected $response = "";
    protected $boatDocumentRepository = "";
    public function __construct(ApiResponse $response,BoatDocumentRepository $BoatDocumentRepository){
        $this->response = $response;
        $this->boatDocumentRepository = $BoatDocumentRepository;
    }


    public function removeBoatDocument(Request $request){
        return $this->response->respond(["data" => [
            'required_documents' => $this->boatDocumentRepository->removeDocument($request->all())
        ]]);
    }
}
