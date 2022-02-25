<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


trait BoatWorkingHoursResponse
{
    public function boatWorkingHoursResponse($boatWorkingHours){

        return [
            'day'=>$boatWorkingHours['day'],
//            'from_time'=>$boatWorkingHours['from_time'],
//            'to_time'=>$boatWorkingHours['to_time'],
            'timings'=> $this->generateTimingsArray($boatWorkingHours),

        ];
    }

    public function generateTimingsArray($workingHours){
        return [
            [
                'from_time' => $workingHours['from_time'],
                'to_time' => $workingHours['to_time']
            ]
        ];
    }
}
