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
     * Decrypt the given session data.
     *
     * @param string $data Data to decrypt
     * @param string $key
     *
     * @return string Decrypted data
     */
    protected function decrypt($data, $key)
    {
        $data = base64_decode($data, true);

        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $key    = $this->generateKey($key);

        $iv   = substr($data, 0, $ivSize);
        $data = substr($data, $ivSize);

        $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);

        return $data;
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
        $keySize = mcrypt_get_key_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $key     = hash('SHA256', $this->options->getClassName() . ':' . $this->sessionName . ':' . $key, true);

        return substr($key, 0, $keySize);
    }

    /**
     * Encrypt the given data.
     *
     * @param string $data Session data to encrypt
     * @param string $key
     *
     * @return string Encrypted data
     */
    protected function encrypt($data, $key)
    {
        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv     = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $key    = $this->generateKey($key);

        // add in our IV and base64 encode the data
        $data = base64_encode(
            $iv . mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv)
        );

        return $data;
    }
}
