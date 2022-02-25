<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


trait RequiredDocumentResponse
{
    public function requiredDocumentResponse($requiredDocument){
        return [
            'required_document_uuid' => $requiredDocument['boat_required_document_uuid'],
            'required_document' => $requiredDocument['name']
        ];
    }
}
