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

use Symfony\Component\Yaml\Yaml;

/**
 * WebServer Factory Class.
 *
 * @author James <james@rezo.net>
 */
class WebServerFactory
{
    private $webservers;

    public function __construct()
    {
        $yaml = new Yaml();
        $config = $yaml->parse(file_get_contents(__DIR__ . '/../../app/config/parameters.yml'));
        $this->webservers = $config['webservers'];
    }

    /**
     * Create a WebServerInterface Object.
     *
     * @param string $name    a web server software name
     * @param string $version a web server software version
     *
     * @return WebServerInterface a WebServer Object
     */
    public function create($name, $version)
    {
        if (in_array($name, array_keys($this->webservers))) {
            return new $this->webservers[$name]($version);
        }

        return new NullWebServer($version);
    }
}
