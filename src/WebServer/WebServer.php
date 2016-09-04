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

use Symfony\Component\Process\Process;
use WebHelper\Parser\ParserInterface;

/**
 * Base class for webserver classes.
 *
 * name can be either apache, nginx, lighttpd, php or other webserver name
 *
 * @author james <james@rezo.net>
 */
abstract class WebServer implements WebServerInterface
{
    /**
     * the name of a webserver.
     *
     * @var string the name of a webserver
     */
    private $name;

    /**
     * the version of a webserver.
     *
     * @var string the version of a webserver
     */
    private $version;

    /**
     * binaries that can be used to control the webserver.
     *
     * @var array
     */
    private $binaries = [];

    /**
     * the parameter string to use to detect version and config file.
     *
     * @var string
     */
    private $detectionParameter = '';

    /**
     * a web config parser.
     *
     * @var ParserInterface
     */
    private $parser;

    /**
     * Constructor.
     *
     * @param string $name    the name of a webserver
     * @param string $version the version of a webserver
     */
    public function __construct($name, $version = '')
    {
        $this->name = $name;
        $this->version = $version;
    }

    /**
     * Gets the name of a webserver.
     *
     * @return string the name of the webserver
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the version of a webserver.
     *
     * @return string the version of the webserver
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Gets the list of binaries that can be run to analyze.
     *
     * @return array the list of binaries that can be run
     */
    public function getBinaries()
    {
        return $this->binaries;
    }

    public function setBinaries(array $binaries)
    {
        $this->binaries = $binaries;

        return $this;
    }

    public function setDetectionParameter($parameter = '')
    {
        $this->detectionParameter = $parameter;

        return $this;
    }

    public function getSettings($fullPathBinary)
    {
        $process = new Process($fullPathBinary.$this->detectionParameter);
        $process->run();

        return $process->getOutput();
    }

    abstract public function extractVersion($settings = '');

    abstract public function extractRootConfigurationFile($settings = '');

    /**
     * Finds and return a specific information in a string.
     *
     * the regexp must contain delimiters.
     *
     * @param string $regexp   a regular expression to find
     * @param string $settings a string where to find
     *
     * @return string the found value or an empty string
     */
    protected function match($regexp, $settings)
    {
        $matches = [];

        if (preg_match($regexp, $settings, $matches)) {
            return $matches[1];
        }

        return '';
    }

    /**
     * Loads and cleans a config file.
     *
     * @param string $file a Configuration file
     *
     * @return array an array of the cleaned directives of a the config per entry
     */
    abstract public function getActiveConfig($file = '');
}
