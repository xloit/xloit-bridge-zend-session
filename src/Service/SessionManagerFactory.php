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

namespace Xloit\Bridge\Zend\Session\Service;

use Interop\Container\ContainerInterface;
use Xloit\Bridge\Zend\ServiceManager\AbstractFactory;
use Xloit\Bridge\Zend\Session\Exception;
use Zend\Session\Config\ConfigInterface;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SaveHandler\SaveHandlerInterface;
use Zend\Session\SessionManager;
use Zend\Session\Storage\StorageInterface;

/**
 * A {@link SessionManagerFactory} class.
 *
 * @package Xloit\Bridge\Zend\Session\Service
 */
class SessionManagerFactory extends AbstractFactory
{
    /**
     * Create the instance service (v3).
     *
     * @param ContainerInterface $container
     * @param string             $name
     * @param null|array         $options
     *
     * @return \Zend\Session\SessionManager
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Interop\Container\Exception\NotFoundException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Xloit\Bridge\Zend\ServiceManager\Exception\StateException
     * @throws \Xloit\Bridge\Zend\Session\Exception\InvalidArgumentException
     * @throws \Xloit\Std\Exception\RuntimeException
     * @throws \Zend\Session\Exception\InvalidArgumentException
     * @throws \Zend\Session\Exception\RuntimeException
     */
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $config      = null;
        $storage     = null;
        $saveHandler = null;

        if ($this->hasOption('config')) {
            $config = $this->getOption('config');
        }

        if (is_array($config)) {
            /** @noinspection UnSafeIsSetOverArrayInspection */
            $configClass = isset($config['class']) ? $config['class'] : SessionConfig::class;
            /** @noinspection UnSafeIsSetOverArrayInspection */
            $options = isset($config['options']) ? $config['options'] : [];
            /** @var $config SessionConfig */
            $config = new $configClass();

            $config->setOptions($options);
        }

        if ($this->hasOption('storage')) {
            $storageClass = $this->getOption('storage');
            $storage      = $storageClass;

            if (is_string($storageClass)) {
                $storage = new $storageClass();
            }
        }

        if ($this->hasOption('saveHandler')) {
            $saveHandlerClass = $this->getOption('saveHandler');
            $saveHandler      = $saveHandlerClass;

            if (is_string($saveHandlerClass)) {
                $saveHandler = new $saveHandlerClass();
            }
        }

        if (!$config && $container->has(ConfigInterface::class)) {
            $config = $container->get(ConfigInterface::class);
        }

        if (!$storage && $container->has(StorageInterface::class)) {
            $storage = $container->get(StorageInterface::class);
        }

        if (!$saveHandler && $container->has(SaveHandlerInterface::class)) {
            $saveHandler = $container->get(SaveHandlerInterface::class);
        }

        if (null !== $config && !($config instanceof ConfigInterface)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'SessionManager requires that the %s service implement %s; received "%s"',
                    ConfigInterface::class,
                    ConfigInterface::class,
                    (is_object($config) ? get_class($config) : gettype($config))
                )
            );
        }

        if (null !== $storage && !($storage instanceof StorageInterface)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'SessionManager requires that the %s service implement %s; received "%s"',
                    StorageInterface::class,
                    StorageInterface::class,
                    (is_object($storage) ? get_class($storage) : gettype($storage))
                )
            );
        }

        if (null !== $saveHandler && !($saveHandler instanceof SaveHandlerInterface)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'SessionManager requires that the %s service implement %s; received "%s"',
                    SaveHandlerInterface::class,
                    SaveHandlerInterface::class,
                    (is_object($saveHandler) ? get_class($saveHandler) : gettype($saveHandler))
                )
            );
        }

        $validators = [];
        $options    = [];

        if ($this->hasOption('validator')) {
            /** @var array $validators */
            $validators = $this->getOption('validator');
        }

        if ($this->hasOption('options')) {
            /** @var array $options */
            $options = $this->getOption('options');
        }

        $sessionManager = new SessionManager($config, $storage, $saveHandler, $validators, $options);

        if (!$this->hasOption('enableDefaultManager')
            || ($this->hasOption('enableDefaultManager')
                && $this->getOption('enableDefaultManager') === true)
        ) {
            Container::setDefaultManager($sessionManager);
        }

        return $sessionManager;
    }
}
