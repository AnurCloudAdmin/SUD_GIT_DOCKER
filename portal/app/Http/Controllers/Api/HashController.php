<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
 
class HashController extends Controller
{
   public function generate(Request $request): JsonResponse
   {
    $body = $request->json()->all();
 
        // Canonical/minified JSON: stable flags, no whitespace changes later
        $normalized = json_encode(
            $body,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION
        );
 
        // Partner constants from config (bound to .env)
        $expectedName  = config('partner.name');
        $expectedCode  = config('partner.code');
        $partnerKey    = config('partner.secret');
        $outputFormat  = config('partner.output', 'base64'); // 'base64' or 'hex'
 
        // Basic credential check (if required by your flow)
        $name = data_get($body, 'PartnerDetails.Partner', '');
        $code = data_get($body, 'PartnerDetails.PartnerCode', '');
 
        if ($name !== $expectedName || $code !== $expectedCode || empty($partnerKey)) {
            return response()->json(['error' => 'Invalid partner credentials'], 401);
        }
 
        // Compute HMAC-SHA256 over the normalized JSON string
        if ($outputFormat === 'hex') {
            // hex string output
            $signature = hash_hmac('sha256', $normalized, $partnerKey, false);
        } else {
            // base64 output (binary digest → base64)
            $raw = hash_hmac('sha256', $normalized, $partnerKey, true);
            $signature = base64_encode($raw);
        }
 
        return response()->json([
            'hash' => $signature,
            'normalized' => $normalized, // helpful for debugging; remove in prod if not desired
        ]);
   }
}