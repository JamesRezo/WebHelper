<?php
/**
 * This file is, guess what, part of WebHelper.
 *
 * (c) James <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JamesRezo\WebHelper\WebServer;

/**
 * Base class for webserver classes.
 *
 * name can be either apache, nginx, lighttpd, php or other webserver name
 *
 * @author james <james@rezo.net>
 * @package WebHelper\WebServer
 */
abstract class WebServer implements WebServerInterface
{
    /**
     * the name of a webserver
     * 
     * @var string $name the name of a webserver
     */
    private $name;

    /**
     * the version of a webserver
     * 
     * @var string $version the version of a webserver
     */
    private $version;

    /**
     * Constructor.
     * 
     * @param string $name         the name of a webserver
     * @param string $version|null the version of a webserver
     * 
     */
    public function __construct($name, $version = null)
    {
        $this->name = $name;
        $this->version = $version;
    }

    /**
     * Get the name of a webserver
     * 
     * @return string the name of the webserver
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the version of a webserver
     * 
     * @return string the version of the webserver
     */
    public function getVersion()
    {
        return $this->version;
    }
}
