<?php

return [
    'client_id' => env('CASHFREE_CLIENT_ID'),
    'client_secret' => env('CASHFREE_CLIENT_SECRET'),
    'environment' => env('CASHFREE_ENVIRONMENT', 'production'),
    'api_version' => env('CASHFREE_API_VERSION', '2022-09-01'),
];