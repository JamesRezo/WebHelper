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

use WebHelper\Parser\NginxParser;

/**
 * NginxWebServer is the webserver class for nginx httpd webserver.
 */
class NginxWebServer extends WebServer
{
    /**
     * Constructor.
     *
     * @param string $version the semver-like version of the apache webserver
     */
    public function __construct($version = '')
    {
        parent::__construct('nginx', $version);
        $this->parser = new NginxParser();
    }

    public function extractVersion($settings = '')
    {
        return $this->match('/^nginx version: nginx\/([0-9\.]+).*/', $settings);
    }

    public function extractRootConfigurationFile($settings = '')
    {
        return $this->match('/--conf-path=([^\s]+) /', $settings);
    }

    public function getActiveConfig($file = '')
    {
        return $this->parser->setConfigFile($file)->getActiveConfig();
    }
}
