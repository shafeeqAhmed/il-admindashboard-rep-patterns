<?php

namespace App\Repositories;


use App\Models\BoatRequiredDocument;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\RequiredDocumentResponse;
//use Your Model

/**
 * Class BoatRepository.
 */
class RequiredDocumentRepository extends BaseRepository implements RepositoryInterface
{
 use RequiredDocumentResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BoatRequiredDocument::class;
    }

    public function getAllRequiredDocuments(){
        return $this->makeMultiResponse($this->model->getDocuments());
    }

    public function makeMultiResponse($documents){
        $final = [];
        foreach($documents as $doc){
            $final[]= $this->requiredDocumentResponse($doc);
        }
        return $final;
    }

    public function getRecordsByUuids($uuids){
        return $this->model->whereIn('boat_required_document_uuid', $uuids)->get();
    }
    public function mapOnTable($params){
        return [

        ];
    }

}
