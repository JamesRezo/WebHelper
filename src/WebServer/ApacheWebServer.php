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
        $matches = [];
        $regexp = '/^Server version: Apache\/([0-9\.]+) .*/';

        if (preg_match($regexp, $settings, $matches)) {
            return $matches[1];
        }

        return '';
    }

    public function extractRootConfigurationFile($settings = '')
    {
        $matches = [];
        $regexp = '/ -D SERVER_CONFIG_FILE="([^"]+)"/';
        if (preg_match($regexp, $settings, $matches)) {
            return $matches[1];
        }

        return '';
    }
}
