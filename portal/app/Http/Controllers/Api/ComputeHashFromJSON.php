<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ComputeHashFromJSON extends Controller
{
    public static function generateHashFromRequest($input)
    {
        try {
            //  Step 1: Normalize input
            if (is_array($input)) {
                $body = $input;
            } elseif (is_string($input)) {
                // Remove outer quotes if they exist
                $trimmed = trim($input, '"');

                $body = json_decode($trimmed, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'error'   => 'Invalid JSON',
                        'message' => json_last_error_msg(),
                    ], 400);
                }
            } else {
                return response()->json([
                    'error'   => 'Unsupported input type',
                    'message' => 'Pass JSON string or PHP array only',
                ], 400);
            }

            // Step 2: Canonical/minified JSON
            $normalized = json_encode(
                $body,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION
            );

            //  Step 3: Load config
            $expectedName  = config('partner.name');
            $expectedCode  = config('partner.code');
            $partnerKey    = config('partner.secret');
            $isHexKey      = config('partner.key_is_hex', false);
            $outputFormat  = config('partner.output', 'base64'); // 'base64' or 'hex'

            //  Step 4: Validate partner
            $name = data_get($body, 'PartnerDetails.Partner', '');
            $code = data_get($body, 'PartnerDetails.PartnerCode', '');

            if ($name !== $expectedName || $code !== $expectedCode || empty($partnerKey)) {
                return response()->json(['error' => 'Invalid partner credentials'], 401);
            }

            //  Step 5: Convert hex key if required
            if ($isHexKey) {
                $partnerKey = hex2bin($partnerKey);
            }

            //  Step 6: Compute HMAC-SHA256
            if ($outputFormat === 'hex') {
                $signature = hash_hmac('sha256', $normalized, $partnerKey, false);
            } else {
                $rawHash   = hash_hmac('sha256', $normalized, $partnerKey, true);
                $signature = base64_encode($rawHash);
            }
//dd($signature);
            //  Step 7: Return hash (slashes unescaped)
            return response()->json([
                'hash'       => $signature,
                //'normalized' => $normalized,
            ], 200, [], JSON_UNESCAPED_SLASHES);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to compute hash',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
