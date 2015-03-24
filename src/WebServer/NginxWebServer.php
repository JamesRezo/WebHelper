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
 * NginxWebServer is the webserver class for nginx httpd webserver.
 */
class NginxWebServer extends WebServer
{
    /**
     * Constructor.
     *
     * @param integer $version the version of the nginx webserver
     */
    public function __construct($version = 0)
    {
        parent::__construct('nginx', $version);
    }
}
