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

use WebHelper\Parser\ApacheParser;

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
        $this->parser = new ApacheParser();
    }

    public function extractVersion($settings = '')
    {
        return $this->match('/^Server version: Apache\/([0-9\.]+) .*/', $settings);
    }

    public function extractRootConfigurationFile($settings = '')
    {
        return $this->match('/ -D SERVER_CONFIG_FILE="([^"]+)"/', $settings);
    }

    /**
     * Loads and cleans a config file.
     *
     * @param string $file a Configuration file
     *
     * @return array an array of the cleaned directives of a the config per entry
     */
    public function getActiveConfig($file = '')
    {
        return $this->parser->setConfigFile($file)->getActiveConfig();
    }
}
