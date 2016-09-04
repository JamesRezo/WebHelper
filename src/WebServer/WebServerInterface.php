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
 * WebServerInterface is the interface implemented by all webserver classes.
 *
 * @author James <james@rezo.net>
 */
interface WebServerInterface
{
    /**
     * Gets the name of a webserver.
     *
     * @return string the name of the webserver
     */
    public function getName();

    /**
     * Gets the version of a webserver.
     *
     * @return string the version of the webserver
     */
    public function getVersion();

    /**
     * Gets the list of binaries that can be run to analyze.
     *
     * @return array the list of binaries that can be run
     */
    public function getBinaries();

    /**
     * Sets the list of binaries that can be run to analyze.
     */
    public function setBinaries(array $binaries);

    /**
     * Sets the parameter string to use to detect version and config file.
     *
     * @param string $parameter parameter string
     */
    public function setDetectionParameter($parameter = '');

    /**
     * Gets the settings output.
     *
     * @return string Settings output
     */
    public function getSettings($fullPathBinary);

    public function extractVersion($settings = '');

    public function extractRootConfigurationFile($settings = '');

    /**
     * Loads and cleans a config file.
     *
     * @param string $file a Configuration file
     *
     * @return array an array of the cleaned directives of a the config per entry
     */
    public function getActiveConfig($file = '');
}
