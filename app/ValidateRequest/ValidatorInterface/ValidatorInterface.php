<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/27/2021
 * Time: 6:26 PM
 */

namespace App\ValidateRequest\ValidatorInterface;


use Illuminate\Http\Request;

Interface ValidatorInterface
{
    public function validate(Request $request,$rules,$message);
}