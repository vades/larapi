<?php
namespace App\Lib\Auth;

use AuthValidateJwt;

/**
 * JSON Web Token (JWT) implementation
 * @author Martin Vach
 */
class AuthJwt
{

    /** 
     * @var array 
     * The header typically consists of two parts: the type of the token, which is JWT, 
     * and the signing algorithm being used, such as HMAC SHA256 or RSA.
     * 
     **/
    private $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];

    /** 
     * @var array 
     * The payload, which contains the claims. Claims are statements 
     * about an entity (typically, the user) and additional data. 
     * 
     **/
    private $payload = [
        'iat'  =>  0, // Issued at: time when the token was generated
        'nbf'  => 0, //  Timestamp of when the token should start being considered valid. Should be equal to or greater than iat. 
        'exp'    => 0, // Timestamp of when the token should cease to be valid. Should be greater than iat and nbf.
        'iss'    => '', // the issuer of the token determining the auth server
        'data' => [], // Data related to the signer user
    ];

    /** 
     * @var string 
     * The JWT signing algorithm. Defaults to HS256. 
     * 
     **/
    private $algo = 'HS256';

    /** 
     * @var array Supported Signing algorithms. 
     */
    private $algos = [
        'HS256' => 'sha256'
    ];

    /**
     * Encode header, payload and signature as JWT token.
     * To create the signature part you have to take the encoded header, 
     * the encoded payload, a signature key, the algorithm specified in the header, and sign that.
     *
     * @throws \Exception
     * @return string URL safe JWT token.
     */
    public function encode(string $signature_key,array $payload, string $algo = '', array $header = [])
    {
        $payload = (!empty($payload) ? array_merge($this->payload, $payload) : $this->header);
        $header = (!empty($header) ? array_merge($this->header, $header) : $this->header);
        $algo = (!empty($algo) ? $algo : $this->algo);

        $payload_encoded   = $this->urlSafeEncode($payload);
        $header_encoded    = $this->urlSafeEncode($header);
        $signature_encoded = $this->urlSafeEncode($this->sign($header_encoded . '.' . $payload_encoded, $algo, $signature_key));
        if(!$signature_encoded){
            throw new \Exception('Invalid token: Signing token failed');
        }

        return $header_encoded  . '.' . $payload_encoded  . '.' .  $signature_encoded;
    }

    /**
     * Decode JWT token and return original payload.
     *
     * @param string $token
     * @throws \Exception
     * @return array
     */
    public function decode(string $signature_key,string $token)
    {
        AuthValidateJwt::token($token);

        $token = explode('.', $token, 3);
        AuthValidateJwt::header((array) $this->urlSafeDecode($token[0]),$this->algos);

        // Validate signature.
        if (!$this->verify($token[0] . '.' . $token[1], $token[2], $signature_key)) {
            throw new \Exception('Invalid token: Verification failed');
        }

        $payload = (array) $this->urlSafeDecode($token[1]);

        return $payload;
    }

    /**
     * URL safe base64 encode.
     *
     * First serialized the payload as json if it is an array.
     *
     * @param array $data
     *
     * @throws \Exception
     *
     * @return string
     */
    private function urlSafeEncode($data)
    {
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_SLASHES);
            AuthValidateJwt::json();
        }

        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * URL safe base64 decode.
     *
     * @param array $data
     *
     * @throws \Exception
     *
     * @return array
     */
    private function urlSafeDecode($data)
    {
        $data = json_decode(base64_decode(strtr($data, '-_', '+/')));
        AuthValidateJwt::json();

        return $data;
    }

    /**
     * Sign the input with configured key and return the signature.
     *
     * @param string $input
     *
     * @return bool
     */
    private function sign($input, $algo, $signature_key)
    {
        // HMAC SHA.
        if (substr($algo, 0, 2) === 'HS') {
            return hash_hmac($this->algos[$algo], $input, $signature_key, true);
        }
        return false;
    }

    /**
     * Verify the signature of given input.
     *
     * @param string $input
     * @param string $signature
     *
     * @return bool
     */
    private function verify(string $input, string $signature, string $signature_key)
    {
        $algo = $this->algos[$this->algo];

        // HMAC SHA.
        if (substr($this->algo, 0, 2) === 'HS') {
            return hash_equals($this->urlSafeEncode(hash_hmac($algo, $input,  $signature_key, true)), $signature);
        }

        return false;
    }

    /**
     * Get bearer token from Authorization Header
     *
     * @return string
     */
    public function getBearerToken()
    {
        $bearer_token = '';
        $auth_header = $this->getAuthorizationHeader();

        if (!empty($auth_header)) {
            if (preg_match('/Bearer\s(\S+)/',$auth_header, $matches)) {
                $bearer_token = $matches[1];
            }
        }
        return $bearer_token;
    }

    /**
     * Get Authorization Header from request headers
     *
     * @return string
     */
    private function getAuthorizationHeader()
    {
        $auth_header = '';
        $headers = apache_request_headers();
        if(isset($headers['Authorization'])){
            $auth_header = trim($headers['Authorization']);
        }
        return  $auth_header;
    }
    
}
