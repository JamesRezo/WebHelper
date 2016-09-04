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
     * @param string $version the semver-like version of the apache webserver
     */
    public function __construct($version = '')
    {
        parent::__construct('null', $version);
    }

    public function extractVersion($settings = '')
    {
        $settings = '';

        return $settings;
    }

    public function extractRootConfigurationFile($settings = '')
    {
        $settings = '';

        return $settings;
    }

    public function getActiveConfig($file = '')
    {
        return [];
    }
}
