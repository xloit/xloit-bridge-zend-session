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

namespace Xloit\Bridge\Zend\Session\SaveHandler\Database\Adapter;

use Xloit\Bridge\Zend\Session\SaveHandler\Database\DatabaseOptions;

/**
 * An {@link AbstractAdapter} abstract class.
 *
 * @abstract
 * @package Xloit\Bridge\Zend\Session\SaveHandler\Database\Adapter
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * Session save handler options.
     *
     * @var DatabaseOptions
     */
    protected $options;

    /**
     *
     *
     * @var mixed
     */
    protected $storageManager;

    /**
     * Constructor to prevent {@link AbstractAdapter} from being loaded more than once.
     *
     * @param mixed           $storageManager
     * @param DatabaseOptions $options
     *
     * @return AbstractAdapter
     */
    public function __construct($storageManager, DatabaseOptions $options)
    {
        $this->setStorageManager($storageManager);
        $this->setOptions($options);
    }

    /**
     * Returns the Storage Manager value.
     *
     * @return mixed
     */
    public function getStorageManager()
    {
        return $this->storageManager;
    }

    /**
     * Sets the Storage Manager value.
     *
     * @param mixed $storageManager
     *
     * @return static
     */
    public function setStorageManager($storageManager)
    {
        $this->storageManager = $storageManager;

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
     * Indicates whether the session is valid.
     *
     * @param mixed $session
     *
     * @return bool
     */
    public function isValid($session)
    {
        return ($this->getModifiedValue($session)->getTimestamp() + $this->getLifetimeValue($session)) > time();
    }
}
