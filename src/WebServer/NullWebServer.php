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
 * NullWebServer is the webserver class used when no web server found.
 */
class NullWebServer extends WebServer
{
    /**
     * Constructor.
     *
     * @param int $version the version of the apache webserver
     */
    public function __construct($version = '')
    {
        parent::__construct('null', $version);
    }
}
