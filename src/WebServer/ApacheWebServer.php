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
            if (preg_match('/^<If(Module|Define)\s+(\w+)>/i', trim($directive), $matches)) {
                $parsedActiveConfig[$line] = ['module section', $matches[2]];
            }
            if (preg_match('/^<(((Directory|Files|Location)(Match)?)|VirtualHost)/i', trim($directive), $matches)) {
                $parsedActiveConfig[$line] = ['scope section', $matches[2]];
            }
            /*if (preg_match('/^Include(Optional)?\s+(.+)/i', trim($directive), $matches)) {
                $parsedActiveConfig[$line] = ['include directive', $matches[2]];
            }*/
            if (preg_match('/^(\w+)\s+(.+)/i', trim($directive), $matches)) {
                $parsedActiveConfig[$line] = ['directive', [$matches[1], $matches[2]]];
            }
        }

        return $parsedActiveConfig;
    }
}
