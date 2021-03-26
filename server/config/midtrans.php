<?php

/**
 * Midtrans payment gateway
 */
return [
	'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-AifYqiAzEzfJUR_w'),
	'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-Vm_kcDhSryfVCiMV_5aUo0Yy'),
	'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
];
