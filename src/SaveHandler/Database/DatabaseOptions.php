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

use Traversable;
use Xloit\Bridge\Zend\Session\Exception;
use Zend\Stdlib\AbstractOptions;

/**
 * A {@link DatabaseOptions} class.
 *
 * @package Xloit\Bridge\Zend\Session\SaveHandler\Database
 */
class DatabaseOptions extends AbstractOptions
{
    /**
     * Class name.
     *
     * @var string
     */
    protected $className;

    /**
     * ID Column.
     *
     * @var string
     */
    protected $idColumn = 'id';

    /**
     * Name Column.
     *
     * @var string
     */
    protected $nameColumn = 'name';

    /**
     * Data Column.
     *
     * @var string
     */
    protected $dataColumn = 'data';

    /**
     * Lifetime Column.
     *
     * @var string
     */
    protected $lifetimeColumn = 'lifetime';

    /**
     * Modified Column.
     *
     * @var string
     */
    protected $modifiedColumn = 'modified';

    /**
     * Created Column.
     *
     * @var string
     */
    protected $createdColumn = 'created';

    /**
     * Constructor to prevent {@link DatabaseOptions} from being loaded more than once.
     *
     * @param array|Traversable|null $options
     *
     * @throws \Xloit\Bridge\Zend\Session\Exception\InvalidArgumentException
     */
    public function __construct($options)
    {
        parent::__construct($options);

        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($this->className)) {
            throw new Exception\InvalidArgumentException('Class Name must be a non-empty string.');
        }
    }

    /**
     * Returns the ClassName value.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Sets the ClassName value.
     *
     * @param string $className
     *
     * @return $this
     * @throws \Xloit\Bridge\Zend\Session\Exception\InvalidArgumentException
     */
    public function setClassName($className)
    {
        $className = (string) $className;

        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($className)) {
            throw new Exception\InvalidArgumentException('Class Name must be a non-empty string.');
        }

        $this->className = $className;

        return $this;
    }

    /**
     * Returns the IdColumn value.
     *
     * @return string
     */
    public function getIdColumn()
    {
        return $this->idColumn;
    }

    /**
     * Sets the IdColumn value.
     *
     * @param string $idColumn
     *
     * @return $this
     * @throws \Xloit\Bridge\Zend\Session\Exception\InvalidArgumentException
     */
    public function setIdColumn($idColumn)
    {
        $idColumn = (string) $idColumn;

        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($idColumn)) {
            throw new Exception\InvalidArgumentException('ID column must be a non-empty string.');
        }

        $this->idColumn = $idColumn;

        return $this;
    }

    /**
     * Returns the NameColumn value.
     *
     * @return string
     */
    public function getNameColumn()
    {
        return $this->nameColumn;
    }

    /**
     * Sets the NameColumn value.
     *
     * @param string $nameColumn
     *
     * @return $this
     * @throws \Xloit\Bridge\Zend\Session\Exception\InvalidArgumentException
     */
    public function setNameColumn($nameColumn)
    {
        $nameColumn = (string) $nameColumn;

        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($nameColumn)) {
            throw new Exception\InvalidArgumentException('Name column must be a non-empty string.');
        }

        $this->nameColumn = $nameColumn;

        return $this;
    }

    /**
     * Returns the DataColumn value.
     *
     * @return string
     */
    public function getDataColumn()
    {
        return $this->dataColumn;
    }

    /**
     * Sets the DataColumn value.
     *
     * @param string $dataColumn
     *
     * @return $this
     * @throws \Xloit\Bridge\Zend\Session\Exception\InvalidArgumentException
     */
    public function setDataColumn($dataColumn)
    {
        $dataColumn = (string) $dataColumn;

        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($dataColumn)) {
            throw new Exception\InvalidArgumentException('Data column must be a non-empty string.');
        }

        $this->dataColumn = $dataColumn;

        return $this;
    }

    /**
     * Returns the LifetimeColumn value.
     *
     * @return string
     */
    public function getLifetimeColumn()
    {
        return $this->lifetimeColumn;
    }

    /**
     * Sets the LifetimeColumn value.
     *
     * @param string $lifetimeColumn
     *
     * @return $this
     * @throws \Xloit\Bridge\Zend\Session\Exception\InvalidArgumentException
     */
    public function setLifetimeColumn($lifetimeColumn)
    {
        $lifetimeColumn = (string) $lifetimeColumn;

        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($lifetimeColumn)) {
            throw new Exception\InvalidArgumentException('Lifetime column must be a non-empty string.');
        }

        $this->lifetimeColumn = $lifetimeColumn;

        return $this;
    }

    /**
     * Returns the ModifiedColumn value.
     *
     * @return string
     */
    public function getModifiedColumn()
    {
        return $this->modifiedColumn;
    }

    /**
     * Sets the ModifiedColumn value.
     *
     * @param string $modifiedColumn
     *
     * @return $this
     * @throws \Xloit\Bridge\Zend\Session\Exception\InvalidArgumentException
     */
    public function setModifiedColumn($modifiedColumn)
    {
        $modifiedColumn = (string) $modifiedColumn;

        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($modifiedColumn)) {
            throw new Exception\InvalidArgumentException('Modified column must be a non-empty string.');
        }

        $this->modifiedColumn = $modifiedColumn;

        return $this;
    }

    /**
     * Returns the CreatedColumn value.
     *
     * @return string
     */
    public function getCreatedColumn()
    {
        return $this->createdColumn;
    }

    /**
     * Sets the CreatedColumn value.
     *
     * @param string $createdColumn
     *
     * @return $this
     * @throws \Xloit\Bridge\Zend\Session\Exception\InvalidArgumentException
     */
    public function setCreatedColumn($createdColumn)
    {
        $createdColumn = (string) $createdColumn;

        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($createdColumn)) {
            throw new Exception\InvalidArgumentException('Created column must be a non-empty string.');
        }

        $this->createdColumn = $createdColumn;

        return $this;
    }
}
