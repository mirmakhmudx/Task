<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Driver
    |--------------------------------------------------------------------------
    | Qaysi SMS yuborgich ishlatilishi. "array" — test rejimi (hech qayerga
    | yubormaydi, xotirada saqlaydi). "sms.ru" — haqiqiy yuborish.
    */

    'driver' => env('SMS_DRIVER', 'array'),

    'drivers' => [

        'sms.ru' => [
            'app_id' => env('SMS_RU_APP_ID', ''),
            'url'    => env('SMS_RU_URL', 'https://sms.ru/sms/send'),
        ],

    ],

];
