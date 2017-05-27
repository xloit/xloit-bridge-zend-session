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

/**
 * A {@link TableGateaway} class.
 *
 * @package Xloit\Bridge\Zend\Session\SaveHandler\Database\Adapter
 */
class TableGateaway extends AbstractAdapter
{
    /**
     * Select all entities.
     *
     * @return array
     */
    public function selectAll()
    {
        return $this->selectBy();
    }

    /**
     * Select entities by a set of criteria.
     *
     * @param Closure|string|array $criteria
     *
     * @return array
     */
    public function selectBy($criteria = null)
    {
        return $this->storageManager->select($criteria);
    }

    /**
     * Select entity by a set of criteria.
     *
     * @param Closure|string|array $criteria
     *
     * @return mixed
     */
    public function select($criteria)
    {
        return current($this->selectBy($criteria));
    }

    /**
     * Insert a new record.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function insert($data)
    {
        return $this->storageManager->insert($data);
    }

    /**
     * Update a record.
     *
     * @param mixed $sessionEntity
     * @param array $data
     *
     * @return mixed
     */
    public function update($sessionEntity, $data)
    {
        return $this->storageManager->update(
            $data,
            [
                $this->options->getIdColumn()   => $this->getIdValue($sessionEntity),
                $this->options->getNameColumn() => $this->getNameValue($sessionEntity)
            ]
        );
    }

    /**
     * Returns the session id.
     *
     * @param mixed $sessionEntity
     *
     * @return string|int
     */
    public function getIdValue($sessionEntity)
    {
        return $sessionEntity->{$this->getOptions()->getIdColumn()};
    }

    /**
     * Returns the session name.
     *
     * @param mixed $sessionEntity
     *
     * @return string
     */
    public function getNameValue($sessionEntity)
    {
        return $sessionEntity->{$this->getOptions()->getNameColumn()};
    }

    /**
     * Garbage Collection - remove old session data older than $maxlifetime (in seconds).
     *
     * @param int $maxlifetime
     *
     * @return bool
     */
    public function gc($maxlifetime)
    {
        $sessions = $this->selectBy(
            sprintf(
                '%s < %d',
                $this->options->getModifiedColumn(),
                time() - $maxlifetime
            )
        );

        foreach ($sessions as $session) {
            $this->delete($session);
        }

        return true;
    }

    /**
     * Delete a record.
     *
     * @param mixed $sessionEntity
     *
     * @return mixed
     */
    public function delete($sessionEntity)
    {
        return (bool) $this->storageManager->delete(
            [
                $this->options->getIdColumn()   => $this->getIdValue($sessionEntity),
                $this->options->getNameColumn() => $this->getNameValue($sessionEntity)
            ]
        );
    }

    /**
     * Returns the session created Unix timestamp.
     *
     * @param mixed $sessionEntity
     *
     * @return int
     */
    public function getCreatedValue($sessionEntity)
    {
        return $sessionEntity->{$this->getOptions()->getCreatedColumn()};
    }

    /**
     * Returns the session data.
     *
     * @param mixed $sessionEntity
     *
     * @return string
     */
    public function getDataValue($sessionEntity)
    {
        return $sessionEntity->{$this->getOptions()->getDataColumn()};
    }

    /**
     * Returns the session lifetime.
     *
     * @param mixed $sessionEntity
     *
     * @return int
     */
    public function getLifetimeValue($sessionEntity)
    {
        return $sessionEntity->{$this->getOptions()->getLifetimeColumn()};
    }

    /**
     * Returns the session modified Unix timestamp.
     *
     * @param mixed $sessionEntity
     *
     * @return int
     */
    public function getModifiedValue($sessionEntity)
    {
        return $sessionEntity->{$this->getOptions()->getModifiedColumn()};
    }
}
