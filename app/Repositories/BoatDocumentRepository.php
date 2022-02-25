<?php

namespace App\Repositories;

use App\Models\Boat;
use App\Models\BoatDocument;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use Carbon\Carbon;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatDocumentResponse;
use Illuminate\Support\Str;

//use Your Model

/**
 * Class BoatRepository.
 */
class BoatDocumentRepository extends BaseRepository implements RepositoryInterface {

    use BoatDocumentResponse;

    /**
     * @return string
     *  Return the model
     */
    public function model() {
        return BoatDocument::class;
    }

    public function makeBulkInsertDocumentObject($docs, $boatId, $requiredDocs) {
        $documents = [];
        foreach ($docs as $doc) {
            $filename = explode('.', $doc['name']);
            $filteredDoc = $requiredDocs->where('boat_required_document_uuid', $doc['document_uuid'])->first();
            $extension = end($filename);
            $documents[] = ['boat_document_uuid' => Str::uuid()->toString(), 'boat_id' => $boatId, 'url' => $doc['name'], 'type' => $extension, 'boat_required_document_id' => $filteredDoc->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
        }
        return $documents;
    }

    public function makeMultipleResponse($documents) {
        $final = [];
        foreach ($documents as $doc) {
            $final[] = $this->boatDocumentResponse($doc);
        }
        return $final;
    }

    public function removeDocument($params) {
        $this->model->updateDocument('boat_document_uuid', $params['document_uuid'], ['is_active' => 0]);
        $boatId = Boat::where('boat_uuid', $params['boat_uuid'])->value('id');
        $boatDocuments = BoatDocument::where('boat_id', $boatId)->where('is_active', 1)->get();
        return $this->makeMultipleResponse($boatDocuments);
    }

    public function mapOnTable($params) {
        return [
        ];
    }

}
