<?php
 
return [
   'name'       => env('PARTNER', ''),
   'code'       => env('PARTNER_CODE', ''),
   'secret'     => env('PARTNER_SECRET', ''),
   
   'key_is_hex' => filter_var(env('PARTNER_KEY_IS_HEX', false), FILTER_VALIDATE_BOOLEAN),
   // Output encoding for the signature.
   'output'     => env('PARTNER_OUTPUT', 'base64'), // 'base64' or 'hex'
];