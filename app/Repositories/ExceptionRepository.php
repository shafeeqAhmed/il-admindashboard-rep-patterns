<?php

namespace App\Repositories;


use App\Models\Exception;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\Responses\ExceptionResponse;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

//use Your Model

/**
 * Class BoatRepository.
 */
class ExceptionRepository extends BaseRepository implements RepositoryInterface
{
 use ExceptionResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Exception::class;
    }

    public function createException($params){
        return $this->create($this->mapOnTable($params));
    }

    public function mapOnTable($params){
        return [
            'exception_uuid'=>Str::uuid()->toString(),
            'exception_file'=>$params->getFile(),
            'exception_line'=>$params->getLine(),
            'exception_message'=>$params->getMessage(),
            'exception_url'=>request()->url(),
            'exception_code'=>$params->getCode(),
        ];
    }

}
