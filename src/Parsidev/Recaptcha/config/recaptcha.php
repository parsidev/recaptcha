<?php

return [
    'public_key' => env('RECAPTCHA_PUBLIC_KEY', ''),
    'private_key' => env('RECAPTCHA_PRIVATE_KEY', ''),
    'template' => '',
    'driver' => 'curl',
    'options' => [
        'curl_timeout' => 1,
    ],
    'version' => 2,
];