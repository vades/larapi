<?php

namespace App\Lib\Uuid;

/**
 * The class generates  Universally Unique IDentifiers (UUID) for version 4 and 5.
 * @author Martin Vach
 *
 */
class Uuid
{

    /**
     * The namespace is whatever UUID you like.
     * You could create one random UUID for your namespace
     * or use the null UUID: 00000000-0000-0000-0000-000000000000 as root.
     *
     */
    private $uudV5Namespace = '49448738-2496-5c7a-8e69-0966b0c74d67';
    
    /**
     * Generate UUID V5
     *
     * The name is a string of arbitrary length.
     *
     * @param    string    $name
     * @throws 	Exception in UuidV5Validate
     * @return     string
     */
    public function v5( string $text ) : ?string
    {
        if(!$this->isValidUuid( $this->uudV5Namespace ) ||  !$this->isValidName( $text) ){
            return null;
        }

        // Convert Namespace UUID to bits
        $bits = $this->namespaceToBitsV5($this->uudV5Namespace);

        // Calculate hash value
        $hash = sha1( $bits . $text) ;

        return $this->buildV5( $hash );
    }
    /**
     * Convert Namespace UUID to bits
     *
     * @param string $namespace
     * @return string
     */
    private function namespaceToBitsV5(string $namespace)
    {
        // Get hexadecimal components of namespace
        $hex = str_replace(array('-', '{', '}'), '', $namespace);
        // Binary Value
        $bits = '';
        // Convert Namespace UUID to bits
        for ($i = 0; $i < strlen($hex); $i += 2) {
            $bits .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $bits;
    }

    /**
     * Build uuid
     *
     * @param string $hash
     * @return string
     */
    private function buildV5(string $hash) : string
    {

        $items = [
            // 32 bits for "time_low"
            substr($hash, 0, 8),
            // 16 bits for "time_mid"
            substr($hash, 8, 4),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 5
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
            // 48 bits for "node"
            substr($hash, 20, 12),
        ];

        return sprintf('%08s-%04s-%04x-%04x-%12s',
            $items[0],
            $items[1],
            $items[2],
            $items[3],
            $items[4]
        );
    }

    /**
     * Generate UUID V4
     *
     * A version 4 UUID is randomly generated.
     *
     * @return string
     */
    public function v4() : string
    {
        $items = [
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
        ];

        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            $items[0],
            $items[1],
            $items[2],
            $items[3],
            $items[4],
            $items[5],
            $items[6],
            $items[7]
        );
    }

    /**
     * Check if a given string is a valid UUID
     *
     * @param string $uuid
     * @return boolean
     */
    public function isValidUuid(string $uuid)
    {
        $is_valid = preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?' .
            '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;

         return $is_valid;
    }

    /**
     * Check if a given string is a valid name
     *
     * @param string $name
     * @return boolean
     */
    public static function isValidName(string $name)
    {
       return (empty($name) ? false :true);
    }
}