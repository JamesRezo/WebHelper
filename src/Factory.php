<?php

/**
 * This file is, guess what, part of WebHelper.
 *
 * (c) James <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JamesRezo\WebHelper;

use Symfony\Component\Yaml\Yaml;
use JamesRezo\WebHelper\WebServer\NullWebServer;

/**
 * WebHelper Factory Class.
 *
 * @author James <james@rezo.net>
 */
class Factory
{
    private $webservers = [];

    public function __construct()
    {
        $file = '';
        foreach ([
            getenv('HOME').'/.config/webhelper/parameters.yml',
            __DIR__.'/../app/config/parameters.yml'
        ] as $file) {
            if (is_readable($file)) {
                break;
            }
        }

        if ($file) {
            $yaml = new Yaml();
            $config = $yaml->parse(file_get_contents($file));
            $this->webservers = $config['webservers'];
        }
    }

    /**
     * Create a WebServerInterface Object.
     *
     * @param string $name    a web server software name
     * @param string $version a web server software version
     *
     * @return WebServer\WebServerInterface a WebServer Object
     */
    public function createWebServer($name, $version = '')
    {
        if (in_array($name, $this->getKnownWebServers())) {
            $webserver = new $this->webservers[$name][0]($version);
            $webserver
                ->setBinaries($this->webservers[$name][1])
                ->setDetectionParameter($this->webservers[$name][2]);

            return $webserver;
        }

        return new NullWebServer($version);
    }

    public function getKnownWebServers()
    {
        return array_keys($this->webservers);
    }
}
