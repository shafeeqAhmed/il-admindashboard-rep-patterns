<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [
    "globals" => [
        "code_expire_time" => '+20 minutes',
    ],
    'ipregistry' => [
        'ipregistry_key' => 'nwdh2g829l5hs9xq',
//        'ipregistry_key' => 'tgh247t2zhyrjy',
//        'ipregistry_key' => '611k8s1a3o0g5h',
    ],
    'url' => [
        'staging_url' => 'http://boatekphpapis-env-1.eba-huumhcwx.ap-south-1.elasticbeanstalk.com/'
    ],
    "payfort" => [
        // test payfort url
        'payfort_url' => 'https://sbpaymentservices.payfort.com/FortAPI/paymentApi',
        // production
//        'payfort_url' => 'https://paymentservices.payfort.com/FortAPI/paymentApi',
        // production apple payfort url
//        'apple_payfort_url' => 'https://paymentservices.payfort.com/FortAPI/paymentApi',
        // test apple payfort url
        'apple_payfort_url' => 'https://sbpaymentservices.payfort.com/FortAPI/paymentApi',
        'access_code' => 'rH5fd8LraUrTXGymEU4i',
        'apple_access_code' => 'dajqUA2g8YVyH04YHtZe',
        'merchant_identifier' => 'b5bc943a',
        'SHA_REQUEST_PHRASE' => '82SJmFaKaJHZES9qUEIuv9&)',
        'APPLE_SHA_REQUEST_PHRASE' => '09Z1u5jr2VWFHr/CPDJNsV$@',
    ],
    "notifications" => [
        "notification_gateway" => env('NOTIFICATION_GATEWAY', 'ssl://gateway.push.apple.com:2195'),
        "bundle_identifier" => "com.app.boatek",
        "key_id" => "NC9VD362J5",
        "team_id" => "2VSTU8J8Z9",
        "p8_key" => "-----BEGIN PRIVATE KEY-----
MIGTAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBHkwdwIBAQQg4P4tms/5wSF0yKSX
rHQyQnOj18nAq7xcte97um4k2NSgCgYIKoZIzj0DAQehRANCAATFklkhGoOPPDu9
rCA8/IT1t4T0pJWz6CIwaQOVEo/3zXTAKJ39MnnGbsaNOyEPCSh76oWOD8GKA1dq
joOZlXPe
-----END PRIVATE KEY-----",
    ],
];
