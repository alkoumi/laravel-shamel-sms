<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Account Details
    |--------------------------------------------------------------------------
    |
    | Set your Username and Password used to log in to
    | http://www.shamelsms.net
    |
    */

    'username' => env('SHAMEL_SMS_USERNAME'),
    'password' =>  env('SHAMEL_SMS_PASSWORD'),

    // Name of Formal Sender & Ads Sender must be apporved by www.shamelsms.net for GCC
    'formal_sender' => env('SHAMEL_SMS_FORMALSENDER'),
    'ads_sender' => env('SHAMEL_SMS_ADSSENDER'),

    // Admin Mobile to notify & Balance to Notify Admin when get this Number
    'admin_mobile' => env('SHAMEL_SMS_ADMINMOBILE'),
    'admin_email' => env('SHAMEL_SMS_ADMINEMAIL'),
    'notify_under' => env('SHAMEL_SMS_NOTIFYUNDER'),




    /*
    |--------------------------------------------------------------------------
    | Universal Settings Required by www.shamelsms.net
    |--------------------------------------------------------------------------
    |
    | You do not need to change any of these settings.
    |
    |
    */

    // The Base Uri of the Api. Don't Change this Value.
    'base_uri' => 'http://www.shamelsms.net/api/',

    // The Send Uri of the Api. Don't Change this Value.
    'send_uri' => 'httpSms.aspx?',

    // The Main Uri of the Api. Don't Change this Value.
    'main_uri' => 'users.aspx?',

    // The Unicode Required by www.shamelsms.net Don't Change.
    'unicodetype' => 'u',

    // Code 7 when Get Balance. Don't Change
    'balance_code' => '7',

];
