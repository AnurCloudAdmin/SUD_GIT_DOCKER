<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class EncryptionController extends Controller
{
    //  public $encryptMethod = 'AES-256-CBC';
    private const AES_KEY_SIZE = 16;
    private const AES_METHOD = 'AES-128-CBC';

    public static function decrypt($encryptedText, $key)
    {
        if (strlen($key) !== self::AES_KEY_SIZE) {
            throw new Exception("Key must be exactly " . self::AES_KEY_SIZE . " bytes long.");
        }

        $data = base64_decode($encryptedText);
        $iv = substr($data, 0, self::AES_KEY_SIZE);
        $cipherText = substr($data, self::AES_KEY_SIZE);

        return openssl_decrypt($cipherText, self::AES_METHOD, $key, OPENSSL_RAW_DATA, $iv);
    }// decrypt

    public static function encrypt($plainText, $key)
    {
        if (strlen($key) !== self::AES_KEY_SIZE) {
            throw new Exception("Key must be exactly " . self::AES_KEY_SIZE . " bytes long.");
        }

        $iv = random_bytes(self::AES_KEY_SIZE);
        $encrypted = openssl_encrypt($plainText, self::AES_METHOD, $key, OPENSSL_RAW_DATA, $iv);
        
        return base64_encode($iv . $encrypted);
    }// encrypt
 
}
