<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Traits;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Mail;
trait CommonService {


    public function emails($key){
        $list = [
            'admin_email' => env('ADMIN_EMAIL'),
            'support_email' => env('SUPPORT_EMAIL'),
            'from_email' => env('EMAIL_FROM'),
            'site_title' => env('EMAIL_TITLE'),
        ];
        return $list[$key];
    }


    public function sendEmailUser($template, $data, $attachment=Null) {
        try {
            $support_email = $this->emails('support_email');
            $site_title = $this->emails('site_title');
            Mail::send($template, ['data' => $data], function($message) use ($support_email, $site_title, $data , $attachment) {
                $message->from($support_email, $site_title);
                $message->subject($data['subject'] ?? 'Boatek');
                $message->to($data['email']);
                // $message->to('hashir.amjad@ilsainteractive.com');

                if (!empty($attachment)) {
                    $message->attachData($attachment['invoice'],'Invoice.pdf');
                }
            });
            if (count(Mail::failures()) > 0) {

                \Log::info(Mail::failures());
            }
            return true;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
    

}
