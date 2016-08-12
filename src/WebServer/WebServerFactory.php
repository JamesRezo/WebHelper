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
 * WebServer Factory Class.
 *
 * @author James <james@rezo.net>
 */
class WebServerFactory
{
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
        switch ($name) {
            case 'apache':
                $webserver = new ApacheWebServer($version);
                break;
            case 'nginx':
                $webserver = new NginxWebServer($version);
                break;
            default:
                $webserver = new NullWebServer($version);
                break;
        }

        return $webserver;
    }
}
