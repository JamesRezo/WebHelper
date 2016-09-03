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

use JamesRezo\WebHelper\WebServer\ApacheWebServer\Directive;

/**
 * ApacheWebServer is the webserver class for apache httpd webserver.
 */
class ApacheWebServer extends WebServer
{
    /**
     * Constructor.
     *
     * @param string $version the semver-like version of the apache webserver
     */
    public function __construct($version = '')
    {
        parent::__construct('apache', $version);
    }

    public function extractVersion($settings = '')
    {
        return $this->match('/^Server version: Apache\/([0-9\.]+) .*/', $settings);
    }

    public function extractRootConfigurationFile($settings = '')
    {
        return $this->match('/ -D SERVER_CONFIG_FILE="([^"]+)"/', $settings);
    }

    public function parseActiveConfig(array $activeConfig = array())
    {
        $parsedActiveConfig = [];

        foreach ($activeConfig as $line => $directive) {
            if (preg_match('/^<If(Module|Define)\s+(\w+)>/i', $directive, $matches)) {
                $parsedActiveConfig[$line] = ['module section', $matches[2]];
            }
            if (preg_match('/^<\/If(Module|Define)>/i', $directive, $matches)) {
                $parsedActiveConfig[$line] = ['end module section'];
            }
            if (preg_match('/^<(((Directory|Files|Location)(Match)?)|VirtualHost)/i', $directive, $matches)) {
                $parsedActiveConfig[$line] = ['scope section', $matches[2]];
            }
            if (preg_match('/^<\/(((Directory|Files|Location)(Match)?)|VirtualHost)/i', $directive, $matches)) {
                $parsedActiveConfig[$line] = ['end scope section'];
            }
            if (preg_match('/^(\w+)\s+(.+)/i', $directive, $matches)) {
                $parsedActiveConfig[$line] = new Directive($matches[1], $matches[2]);
            }
        }

        return $parsedActiveConfig;
    }
}
