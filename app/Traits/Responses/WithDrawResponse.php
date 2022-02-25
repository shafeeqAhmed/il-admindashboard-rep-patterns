<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


trait WithDrawResponse
{
    public function withDrawResponse($withdraw){
        return [
            'withdraw_uuid'=>$withdraw['withdraw_uuid'],
            'scheduled_date'=>setDateFormat($withdraw['created_at']),
            'amount_transfer'=>$withdraw['amount'],
            'schedule_status'=>$withdraw['schedule_status'],
            'bank_name' => $withdraw['bank_name'],
            'account_number' => $withdraw['account_number'],
            'account_title' => $withdraw['account_title']
        ];
    }
}
