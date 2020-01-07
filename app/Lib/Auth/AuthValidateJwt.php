<?php

namespace App\Lib\Auth;

/**
 * JSON Web Token validation
 * @author Martin Vach
 */
class  AuthValidateJwt
{
    /**
     * Validate signature key
     *
     * @param string $signature_key
     * @throws \Exception 
     * @return void
     */
    public static function signatureKey(string $signature_key)
    {
        if (empty($signature_key)) {
            throw new \Exception('Invalid token: Signature not provided');
        }
        return;
    }
    /**
     * Validate token
     *
     * @param string $token
     * @throws \Exception 
     * @return void
     */
    public static function token(string $token)
    {
        if (substr_count($token, '.') < 2) {
            throw new \Exception('Invalid token: Incomplete segments');
        }
        return;
    }
    /**
     * Validate payload
     *
     * @param array $payload
     * @throws \Exception 
     * @return void
     */
    public static function payload(array $payload)
    {
        $iat = intval($payload['iat']);
        $nbf = intval($payload['nbf']);
        $exp = intval($payload['exp']);

        if ($iat < 1) {
            throw new \Exception('Invalid token: Payload iat ('.$iat.') must be greater than 0');
        }
        if ($nbf < 1) {
            throw new \Exception('Invalid token: Payload nbf ('.$nbf.') must be greater than 0');
        }
        if ($nbf < $iat) {
            throw new \Exception('Invalid token: Payload nbf ('.$nbf.') must be equal to or greater than iat (' . $iat.')');
        }
        if ($exp < 1) {
            throw new \Exception('Invalid token: Payload exp ('.$exp.') must be greater than 0');
        }
        if ($exp <= $nbf) {
            throw new \Exception('Invalid token: Payload exp ('.$exp.') must be greater than nbf (' . $nbf.')');
        }
        return;
    }
    /**
     * Validate header
     *
     * @param array $header
     * @param array $algos
     * @throws \Exception 
     * @return void
     */
    public static function header(array $header, array $algos)
    {
        if (empty($header['alg'])) {
            throw new \Exception('Invalid token: Missing header algo');
        }
        if (empty($algos[$header['alg']])) {
            throw new \Exception('Invalid token: Unsupported header algo');
        }
        return;
    }
    /**
     * Validate token expiration
     *
     * @param integer $expire
     * @param integer $now
     * @throws \Exception 
     * @return void
     */
    public static function expired(int $expire, int $now)
    {
        if ($expire <= $now) {
            throw new \Exception('Invalid token: Auth token is expired');
        }
        return;
    }
    /**
     * Validate bearer token
     *
     * @param string $bearer_token
     * @throws \Exception 
     * @return void
     */
    public static function bearerToken(string $bearer_token)
    {
        if (empty($bearer_token)) {
            throw new \Exception('Invalid token: Bearer token not provided');
        }
        return;
    }

    /**
     * Throw up if last json_encode/decode was a failure.
     *
     * @throws \Exception 
     * @return void
     */
    public static function json()
    {
        if (JSON_ERROR_NONE === json_last_error()) {
            return;
        }

        throw new \Exception('JSON failed: ' . json_last_error_msg());
    }
}
