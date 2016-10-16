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

namespace Xloit\Bridge\Zend\Session;

use Zend\Session\Container;
use Zend\Session\ManagerInterface;

/**
 * A {@link SessionRedirectContainer} class.
 *
 * @package Xloit\Bridge\Zend\Session
 */
class SessionRedirectContainer extends Container
{
    /**
     *
     *
     * @var string
     */
    const SESSION_NAME = 'Xloit_Session_SessionRedirectContainer';

    /**
     *
     *
     * @var string
     */
    protected $redirectUri;

    /**
     *
     *
     * @var string
     */
    protected $redirectRoute;

    /**
     * Constructor to prevent {@link SessionRedirectContainer} from being loaded more than once.
     *
     * @param null|string      $name
     * @param ManagerInterface $manager
     *
     * @throws \Zend\Session\Exception\InvalidArgumentException
     */
    public function __construct($name = self::SESSION_NAME, ManagerInterface $manager = null)
    {
        parent::__construct($name, $manager);
    }

    /**
     *
     *
     * @return bool
     */
    public function hasRedirect()
    {
        return $this->isUri() || $this->isRoute();
    }

    /**
     *
     *
     * @return bool
     */
    public function isUri()
    {
        $uri = $this->getRedirectUri();

        /** @noinspection IsEmptyFunctionUsageInspection */
        return !empty($uri);
    }

    /**
     *
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     *
     *
     * @param string $redirectUri
     *
     * @return static
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    /**
     *
     *
     * @return bool
     */
    public function isRoute()
    {
        $route = $this->getRedirectRoute();

        /** @noinspection IsEmptyFunctionUsageInspection */
        return !empty($route);
    }

    /**
     *
     *
     * @return string
     */
    public function getRedirectRoute()
    {
        return $this->redirectRoute;
    }

    /**
     *
     *
     * @param string $redirectRoute
     *
     * @return static
     */
    public function setRedirectRoute($redirectRoute)
    {
        $this->redirectRoute = $redirectRoute;

        return $this;
    }

    /**
     *
     *
     * @return static
     */
    public function clear()
    {
        $this->redirectUri   = null;
        $this->redirectRoute = null;

        return $this;
    }
}
