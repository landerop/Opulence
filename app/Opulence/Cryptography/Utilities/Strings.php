<?php
/**
 * Opulence
 *
 * @link      https://www.opulencephp.com
 * @copyright Copyright (C) 2015 David Young
 * @license   https://github.com/opulencephp/Opulence/blob/master/LICENSE.md
 */
namespace Opulence\Cryptography\Utilities;

use Opulence\Cryptography\CryptographicException;
use RuntimeException;

/**
 * Defines some string utilities
 */
class Strings
{
    /**
     * Creates a cryptographically-strong random bytes
     *
     * @param int $length The desired number of bytes
     * @return string The random bytes
     * @throws CryptographicException Thrown if there was an error generating the bytes
     */
    public function generateRandomBytes($length)
    {
        if (PHP_MAJOR_VERSION >= 7) {
            return random_bytes($length);
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes($length, $isStrong);

            if ($bytes === false || !$isStrong) {
                throw new RuntimeException("Generated bytes were not secure");
            }

            return $bytes;
        }

        throw new RuntimeException("OpenSSL is required for PHP 5");
    }

    /**
     * Creates a cryptographically-strong random string
     *
     * @param int $length The desired length of the string
     * @return string The random string
     * @throws RuntimeException Thrown if there was an error generating the hash
     */
    public function generateRandomString($length)
    {
        // N bytes becomes 2N characters in bin2hex(), hence the division by 2
        $string = bin2hex($this->generateRandomBytes(ceil($length / 2)));

        if ($length % 2 == 1) {
            // Slice off one character to make it the appropriate odd length
            $string = mb_substr($string, 1);
        }

        return $string;
    }

    /**
     * Checks if two strings are equal without having to worry about timing attacks
     *
     * @param string $knownString The known string
     * @param string $userString The user string
     * @return bool True if the strings are equal, otherwise false
     * @link http://php.net/manual/en/function.hash-equals.php
     */
    public function isEqual($knownString, $userString)
    {
        if (!is_string($knownString)) {
            $knownString = (string)$knownString;
        }

        if (!is_string($userString)) {
            $userString = (string)$userString;
        }

        if (function_exists("hash_equals")) {
            return hash_equals($knownString, $userString);
        }

        $knownStringLength = mb_strlen($knownString, "8bit");
        $userStringLength = mb_strlen($userString, "8bit");

        if ($knownStringLength !== $userStringLength) {
            return false;
        }

        $result = 0;

        for ($i = 0;$i < $knownStringLength;++$i) {
            $result |= (ord($knownString[$i]) ^ ord($userString[$i]));
        }

        return $result === 0;
    }
}