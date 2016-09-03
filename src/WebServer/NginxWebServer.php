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

use JamesRezo\WebHelper\WebServer\NginxWebServer\Directive;

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
    }

    public function extractVersion($settings = '')
    {
        return $this->match('/^nginx version: nginx\/([0-9\.]+).*/', $settings);
    }

    public function extractRootConfigurationFile($settings = '')
    {
        return $this->match('/--conf-path=([^\s]+) /', $settings);
    }

    public function parseActiveConfig(array $activeConfig = array())
    {
        $parsedActiveConfig = [];

        foreach ($activeConfig as $line => $directive) {
            if (preg_match('/(?P<key>\w+)\s+(?P<value>[^;]+);/', trim($directive), $matches)) {
                $parsedActiveConfig[$line] = new Directive($matches['key'], $matches['value']);
            }
            if (preg_match('/(?P<key>\w+)\s+(?P<value>[^\s{+])\s*{/', trim($directive), $matches)) {
                $parsedActiveConfig[$line] = ['block directive', [$matches['key'] => $matches['value']]];
            }
            if (preg_match('/(?P<key>\w+)\s*{/', trim($directive), $matches)) {
                $parsedActiveConfig[$line] = ['context', $matches['key']];
            }
        }

        return $parsedActiveConfig;
    }
}
