<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\RequiredDocumentRepository;
use Illuminate\Http\Request;

class RequiredDocumentController extends Controller
{
    protected $response = "";
    protected $requiredDocumentRepository = "";
    public function __construct(ApiResponse $response,RequiredDocumentRepository $RequiredDocumentRepository){
        $this->response = $response;
        $this->requiredDocumentRepository = $RequiredDocumentRepository;
    }

    public function getRequiredDocuments(){
        return $this->response->respond(["data" => [
            'required_documents' => $this->requiredDocumentRepository->getAllRequiredDocuments()
        ]]);
    }
}
