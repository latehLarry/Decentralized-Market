<?php

namespace App\Tools;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use OpenPGP;
use OpenPGP_Crypt_RSA;
use OpenPGP_Crypt_Symmetric;
use OpenPGP_LiteralDataPacket;
use OpenPGP_Message;
use OpenPGP_SecretKeyPacket;

class PGP
{
    /**
     * Encrypt message function
     * @param  string $key 
     * @param  string $message    
     *  
     * @return OpenPGP          
     */
    public static function encryptMessage($key,$message)
    {
        if (is_null($key) or is_null($message)) {
            throw new \Exception('Ops... Could not encrypt!');
        }

        $pubkey = $key;
        $key = OpenPGP_Message::parse(OpenPGP::unarmor($pubkey, 'PGP PUBLIC KEY BLOCK'));
        $data = new OpenPGP_LiteralDataPacket($message, ['format' => 'u']);
        $encrypted = OpenPGP_Crypt_Symmetric::encrypt($key, new OpenPGP_Message(array($data)));
        $armored = OpenPGP::enarmor($encrypted->to_bytes(), 'PGP MESSAGE');

        return $armored;
    }

    /**
     * Create new verification function
     * @param  string $key  
     * @param  string $name 
     * 
     * @return App\Tools\PGP      
     */
    public static function verification($key,$name)
    {
        $verificationCode = Str::random(16); #Create verification code
        $message = "---------------------------\nSUBMARINE ANONYMOUS MARKET\n---------------------------\nVERIFICATION CODE: $verificationCode";

        $encryptedMessage = self::encryptMessage($key, $message); #Encrypt message with PGP key received as parameter 

        #Create validation sessions
        session([
            'verification_name' => $name,
            'encrypted_message' => $encryptedMessage,
            'verification_code' => Crypt::encryptString($verificationCode)
        ]);
    }
}