<?php

return [
    'merchant_id' => env('MIDTRANS_MERCHAT_ID', 'G220353234'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-Oi821fMphAsX4A9G'),
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-tAArf-grxMbqYZwVIgNwIbsN'),

    'is_production' => false, // Set to true for production environment
    'is_sanitized' => true,
    'is_3ds' => true,
];
