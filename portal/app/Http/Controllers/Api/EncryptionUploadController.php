<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class EncryptionUploadController extends Controller
{
    private static string $partnerKey = 'KEY'; // same as C# "PartnerKey"

    public static function encrypt(string $plainText, string $partnerName, string $partnerCode): string
    {
        $key = self::getAesKey(); // fixed key like in C#
        $ivLength = openssl_cipher_iv_length('aes-128-cbc');
        $iv = random_bytes($ivLength);

        $encrypted = openssl_encrypt(
            $plainText,
            'aes-128-cbc',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        // Prepend IV to ciphertext just like in C#
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt(string $encryptedText, string $partnerName, string $partnerCode): string
    {
        $key = self::getAesKey();
        $cipherData = base64_decode($encryptedText);
        $ivLength = openssl_cipher_iv_length('aes-128-cbc');

        $iv = substr($cipherData, 0, $ivLength);
        $ciphertext = substr($cipherData, $ivLength);

        $decrypted = openssl_decrypt(
            $ciphertext,
            'aes-128-cbc',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return $decrypted;
    }

    private static function getAesKey(): string
    {
        $key = substr("KEY" . str_repeat("\0", 16), 0, 16);

    }

}
