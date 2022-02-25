<?php

/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;

use App\Repositories\RequiredDocumentRepository;

trait BoatDocumentResponse {

    public function boatDocumentResponse($boatDocument) {
        return [
            'boat_document_uuid' => !empty($boatDocument['boat_document_uuid']) ? $boatDocument['boat_document_uuid'] : '',
            'document_name' => !empty($boatDocument['url']) ? $boatDocument['url'] : '',
            'type' => $boatDocument['type'],
            'required_document_uuid' => !empty($boatDocument['required_documents']) ? (string) $boatDocument['required_documents']['boat_required_document_uuid'] : null,
            'required_document' => isset($boatDocument['required_documents']) ? $boatDocument['required_documents']['name'] : null,
        ];
    }

}
