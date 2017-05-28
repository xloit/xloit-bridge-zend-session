<?php
/**
 * This source file is part of Xloit project.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * <http://www.opensource.org/licenses/mit-license.php>
 * If you did not receive a copy of the license and are unable to obtain it through the world-wide-web,
 * please send an email to <license@xloit.com> so we can send you a copy immediately.
 *
 * @license   MIT
 * @link      http://xloit.com
 * @copyright Copyright (c) 2016, Xloit. All rights reserved.
 */

namespace Xloit\Bridge\Zend\Session\SaveHandler\Database;

/**
 * A {@link CryptDatabase} class.
 *
 * @package Xloit\Bridge\Zend\Session\SaveHandler\Database
 */
class CryptDatabase extends Database
{
    /**
     *
     *
     * @var string
     */
    const CRYPT_MODE = 'AES-128-CBC';

    /**
     *
     *
     * @param string $id
     * @param string $data
     *
     * @return string
     */
    protected function sanitizeDataRead($id, $data)
    {
        return (string) $this->decrypt($data, $id);
    }

    /**
     *
     *
     * @param string $id
     * @param string $data
     *
     * @return string
     */
    protected function sanitizeDataWrite($id, $data)
    {
        return $this->encrypt((string) $data, $id);
    }

    /**
     * Encrypt the given data.
     *
     * @param string $data Session data to encrypt.
     * @param string $key
     *
     * @return string Encrypted data.
     */
    protected function encrypt($data, $key)
    {
        $keySize = openssl_cipher_iv_length(static::CRYPT_MODE);
        $padding = $keySize - (mb_strlen($data, '8bit') % $keySize);
        /** @noinspection CryptographicallySecureRandomnessInspection */
        $iv   = openssl_random_pseudo_bytes($keySize);
        $key  = $this->generateKey($key);
        $data .= str_repeat(chr($padding), $padding);

        // add in our IV and base64 encode the data
        $data = base64_encode(
            $iv
            . openssl_encrypt(
                $data, static::CRYPT_MODE, $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv
            )
        );

        return $data;
    }

    /**
     * Decrypt the given session data.
     *
     * @param string $data Data to decrypt
     * @param string $key
     *
     * @return string Decrypted data
     */
    protected function decrypt($data, $key)
    {
        $base64 = base64_decode($data, true);

        $keySize = openssl_cipher_iv_length(static::CRYPT_MODE);
        $key     = $this->generateKey($key);

        $iv      = substr($base64, 0, $keySize);
        $results = substr($base64, $keySize);

        $results = openssl_decrypt(
            $results, static::CRYPT_MODE, $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv
        );

        return $results;
    }

    /**
     * Generate secret key.
     *
     * @param string $key
     *
     * @return string Generated secret key
     */
    protected function generateKey($key)
    {
        $keySize = openssl_cipher_iv_length(static::CRYPT_MODE);
        $key     = hash(
            'SHA256', $this->options->getClassName() . ':' . $this->sessionName . ':' . $key, true
        );

        return substr($key, 0, $keySize);
    }
}
