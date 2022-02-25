<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/28/2021
 * Time: 10:03 AM
 */

namespace App\Http\Responses\Interfaces;


Interface ResponseInterface
{
    public function respond(array $response, array $headers = []);
}