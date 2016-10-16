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

use Closure;
use DateTime;
use Xloit\Bridge\Zend\Session\SaveHandler\Database\DatabaseOptions;

/**
 * An {@link AdapterInterface} interface.
 *
 * @package Xloit\Bridge\Zend\Session\SaveHandler\Database\Adapter
 */
interface AdapterInterface
{
    /**
     * Returns the Storage Adapter value.
     *
     * @return mixed
     */
    public function getStorageManager();

    /**
     * Select entities by a set of criteria.
     *
     * @param Closure|string|array $criteria
     *
     * @return array
     */
    public function selectBy($criteria = null);

    /**
     * Select all entities.
     *
     * @return array
     */
    public function selectAll();

    /**
     * Select entity by a set of criteria.
     *
     * @param Closure|string|array $criteria
     *
     * @return mixed
     */
    public function select($criteria);

    /**
     * Insert a new record.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function insert($data);

    /**
     * Update a record.
     *
     * @param mixed $sessionEntity
     * @param array $data
     *
     * @return mixed
     */
    public function update($sessionEntity, $data);

    /**
     * Delete a record.
     *
     * @param mixed $sessionEntity
     *
     * @return mixed
     */
    public function delete($sessionEntity);

    /**
     * Garbage Collection - remove old session data older than $maxlifetime (in seconds).
     *
     * @param int $maxlifetime
     *
     * @return bool
     */
    public function gc($maxlifetime);

    /**
     * Returns the Options value.
     *
     * @return DatabaseOptions
     */
    public function getOptions();

    /**
     * Sets the Options value.
     *
     * @param DatabaseOptions $options
     *
     * @return static
     */
    public function setOptions(DatabaseOptions $options);

    /**
     * Returns the session id.
     *
     * @param mixed $sessionEntity
     *
     * @return string|int
     */
    public function getIdValue($sessionEntity);

    /**
     * Returns the session name.
     *
     * @param mixed $sessionEntity
     *
     * @return string
     */
    public function getNameValue($sessionEntity);

    /**
     * Returns the session lifetime.
     *
     * @param mixed $sessionEntity
     *
     * @return int
     */
    public function getLifetimeValue($sessionEntity);

    /**
     * Returns the session modified Unix timestamp.
     *
     * @param mixed $sessionEntity
     *
     * @return DateTime
     */
    public function getModifiedValue($sessionEntity);

    /**
     * Returns the session created Unix timestamp.
     *
     * @param mixed $sessionEntity
     *
     * @return DateTime
     */
    public function getCreatedValue($sessionEntity);

    /**
     * Returns the session data.
     *
     * @param mixed $sessionEntity
     *
     * @return string
     */
    public function getDataValue($sessionEntity);

    /**
     * Indicates whether the session is valid.
     *
     * @param mixed $sessionEntity
     *
     * @return bool
     */
    public function isValid($sessionEntity);
}
