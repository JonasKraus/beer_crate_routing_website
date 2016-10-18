<?php

/**
 * Created by PhpStorm.
 * User: jonas-uni
 * Date: 15.10.2016
 * Time: 12:56
 */
class cryptography
{

    const METHOD = 'aes-256-ctr';

    /**
     * Encrypts (but does not authenticate) a message
     *
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded
     * @return string (raw binary)
     */
    public static function encrypt($message, $key, $encode = false)
    {
        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            return base64_encode($nonce.$ciphertext);
        }
        return $nonce.$ciphertext;
    }

    /**
     * Decrypts (but does not verify) a message
     *
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string
     */
    public static function decrypt($message, $key, $encoded = false)
    {
        if ($encoded) {
            $message = base64_decode($message, true);
            if ($message === false) {
                throw new Exception('Encryption failure');
            }
        }

        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        return $plaintext;
    }

    public static function wrapProgress($progress, $pseudonym, $base64Encoded = false) {

        $head = cryptography::generateRandomString(5);
        $middle = cryptography::generateRandomString(2);
        $tail = cryptography::generateRandomString(7);

        $link = $head . $progress . $middle . strlen($pseudonym) . $pseudonym . $tail;

        $encrypted = cryptography::encrypt($link, consts::getKEY());

        if ($base64Encoded) {
            $encrypted = base64_encode($encrypted);
            $encrypted = str_replace("/", "_", $encrypted);
            $encrypted = str_replace("=", "-", $encrypted);
        }

        $encrypted = urlencode($encrypted);

        return $encrypted;
    }

    public static function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function unwrapProgress($link, $base64Encoded = false) {
        $link = urldecode($link);

        if ($base64Encoded) {
            $link = str_replace("-", "=", $link);
            $link = str_replace("_", "/", $link);
            $link = base64_decode($link);
        }

        $decrypt = cryptography::decrypt($link, consts::getKEY());
        $progress = substr($decrypt, 5,1);
        $length = substr($decrypt, 8,1);
        $pseudonym = substr($decrypt, 9, $length); // TODO check länge vom kürzl

        return array(
            "success" => true,
            "pseudonym" => $pseudonym,
            "progress" => $progress
        );
    }
}