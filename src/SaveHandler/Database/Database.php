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

use Xloit\DateTime\DateTime;

/**
 * A {@link Database} class.
 *
 * @package Xloit\Bridge\Zend\Session\SaveHandler\Database
 */
class Database implements DatabaseInterface
{
    /**
     *
     *
     * @var Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * Holds the sessionName value.
     *
     * @var string
     */
    protected $sessionName;

    /**
     * Holds the sessionSavePath value.
     *
     * @var string
     */
    protected $sessionSavePath;

    /**
     * Holds the lifetime value.
     *
     * @var string
     */
    protected $lifetime;

    /**
     * MongoDB session save handler options.
     *
     * @var DatabaseOptions
     */
    protected $options;

    /**
     * Constructor to prevent {@link AbstractDatabase} from being loaded more than once.
     *
     * @param Adapter\AdapterInterface $adapter
     * @param DatabaseOptions          $options
     */
    public function __construct(Adapter\AdapterInterface $adapter, DatabaseOptions $options)
    {
        $adapter->setOptions($options);

        $this->adapter = $adapter;
        $this->options = $options;
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        $this->lifetime = @ini_get('session.gc_maxlifetime');
    }

    /**
     * Returns the SessionName value.
     *
     * @return string
     */
    public function getSessionName()
    {
        return $this->sessionName;
    }

    /**
     * Sets the SessionName value.
     *
     * @param string $sessionName
     *
     * @return static
     */
    public function setSessionName($sessionName)
    {
        $this->sessionName = $sessionName;

        return $this;
    }

    /**
     * Returns the SessionSavePath value.
     *
     * @return string
     */
    public function getSessionSavePath()
    {
        return $this->sessionSavePath;
    }

    /**
     * Sets the SessionSavePath value.
     *
     * @param string $sessionSavePath
     *
     * @return static
     */
    public function setSessionSavePath($sessionSavePath)
    {
        $this->sessionSavePath = $sessionSavePath;

        return $this;
    }

    /**
     * Returns the Lifetime value.
     *
     * @return string
     */
    public function getLifetime()
    {
        if (!$this->lifetime) {
            /** @noinspection PhpUsageOfSilenceOperatorInspection */
            $this->lifetime = @ini_get('session.gc_maxlifetime');
        }

        return $this->lifetime;
    }

    /**
     * Sets the Lifetime value.
     *
     * @param string $lifetime
     *
     * @return static
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;

        return $this;
    }

    /**
     * Returns the Options value.
     *
     * @return DatabaseOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets the Options value.
     *
     * @param DatabaseOptions $options
     *
     * @return static
     */
    public function setOptions(DatabaseOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Open Session - retrieve resources.
     *
     * @param string $savePath
     * @param string $name
     *
     * @return bool
     */
    public function open($savePath, $name)
    {
        $this->sessionSavePath = $savePath;
        $this->sessionName     = $name;

        return true;
    }

    /**
     * Close Session - free resources.
     *
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Read session data.
     *
     * @param string $id
     *
     * @return mixed
     * @throws \Zend\Json\Exception\RuntimeException
     */
    public function read($id)
    {
        $idColumn   = $this->options->getIdColumn();
        $nameColumn = $this->options->getNameColumn();
        $adapter    = $this->getAdapter();

        $sessionData = [
            $idColumn   => $id,
            $nameColumn => $this->sessionName
        ];

        $session = $adapter->select($sessionData);

        if ($session) {
            if ($adapter->isValid($session)) {
                return $this->sanitizeDataRead($id, $adapter->getDataValue($session));
            }

            $this->destroy($id);
        }

        return '';
    }

    /**
     * Returns the Adapter value.
     *
     * @return Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Sets the Adapter value.
     *
     * @param Adapter\AdapterInterface $adapter
     *
     * @return static
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * Destroy Session - remove data from resource for given session id.
     *
     * @param string $id
     *
     * @return bool
     */
    public function destroy($id)
    {
        $idColumn    = $this->options->getIdColumn();
        $nameColumn  = $this->options->getNameColumn();
        $adapter     = $this->getAdapter();
        $sessionData = [
            $idColumn   => $id,
            $nameColumn => $this->sessionName
        ];
        $session     = $adapter->select($sessionData);

        if ($session) {
            return (bool) $this->getAdapter()->delete($session);
        }

        return false;
    }

    /**
     * Write Session - commit data to resource.
     *
     * @param string $id
     * @param mixed  $data
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function write($id, $data)
    {
        $idColumn       = $this->options->getIdColumn();
        $nameColumn     = $this->options->getNameColumn();
        $dataColumn     = $this->options->getDataColumn();
        $modifiedColumn = $this->options->getModifiedColumn();
        $createdColumn  = $this->options->getCreatedColumn();
        $lifetimeColumn = $this->options->getLifetimeColumn();
        $adapter        = $this->getAdapter();

        $sessionData = [
            $idColumn   => $id,
            $nameColumn => $this->sessionName
        ];

        $session = $adapter->select($sessionData);

        $sessionData[$dataColumn]     = $this->sanitizeDataWrite($id, $data);
        $sessionData[$modifiedColumn] = new DateTime();

        if ($session) {
            return (bool) $adapter->update($session, $sessionData);
        }

        $sessionData[$lifetimeColumn] = $this->lifetime;
        $sessionData[$createdColumn]  = new DateTime();

        /** @noinspection PhpUndefinedMethodInspection */
        return (bool) $adapter->insert($sessionData);
    }

    /**
     * Garbage Collection - remove old session data older than the maximum lifetime (in seconds).
     *
     * @param int $maxLifetime
     *
     * @return bool
     */
    public function gc($maxLifetime)
    {
        return (bool) $this->getAdapter()->gc($maxLifetime);
    }

    /**
     *
     *
     * @param string $id
     * @param string $data
     *
     * @return string
     */
    protected function sanitizeDataRead(
        /** @noinspection PhpUnusedParameterInspection */
        $id, $data
    ) {
        return (string) $data;
    }

    /**
     *
     *
     * @param string $id
     * @param string $data
     *
     * @return string
     */
    protected function sanitizeDataWrite(
        /** @noinspection PhpUnusedParameterInspection */
        $id, $data
    ) {
        return (string) $data;
    }
}
