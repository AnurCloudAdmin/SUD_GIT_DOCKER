<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class EncryptionCTRController extends Controller
{
   
    public static function encrypt($plaintext, $key)
    {
        $cipher = "aes-128-ctr";
        // Ensure key is 16 bytes for AES-128
        $key = substr(hash('sha256', $key, true), 0, 16);
 
        // Use a 16-byte IV (Nonce)
        $iv = random_bytes(openssl_cipher_iv_length($cipher));
 
        // Encrypt the data
        $encrypted = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
 
        // Combine IV with encrypted data and encode in hex
        return bin2hex($iv . $encrypted);
    }
 
    // Decrypt function
    public static function decrypt($encrypted, $key)
    {
        $cipher = "aes-128-ctr";
        // Ensure key is 16 bytes for AES-128
        $key = substr(hash('sha256', $key, true), 0, 16);
 
        // Decode from hex
        $data = hex2bin($encrypted);
 
        // Extract IV and encrypted data
        $iv_length = openssl_cipher_iv_length($cipher);
        $iv = substr($data, 0, $iv_length);
        $ciphertext = substr($data, $iv_length);
 
        // Decrypt the data
        return openssl_decrypt($ciphertext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    }
 
}
