<?php

/**
 * This file is, guess what, part of WebHelper.
 *
 * (c) James <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JamesRezo\WebHelper;

use Composer\Semver\VersionParser;
use Composer\Semver\Comparator;
use JamesRezo\WebHelper\WebServer\WebServerFactory;
use JamesRezo\WebHelper\WebHelperRepository;

/**
 * WebHelper.
 */
class WebHelper
{
    /** @var WebHelperRepository a Repository instance */
    private $repository;

    /** @var VersionParser a VersionParser instance */
    private $versionParser;

    /** @var Comparator a Comparator instance */
    private $comparator;

    /** @var WebServer\WebServerInterface a Web Server instance */
    private $server;

    /**
     * Base constructor.
     */
    public function __construct()
    {
        $this->versionParser = new VersionParser();
        $this->comparator = new Comparator();
    }

    /**
     * Sets the Repository Instance.
     *
     * @param string $resDir a Path of a Directives Repository
     */
    public function setRepository($resDir = '')
    {
        $this->repository = new WebHelperRepository($resDir);

        return $this;
    }

    /**
     * Gets the Repository Instance.
     *
     * @return WebHelperRepository the Repository Instance
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Sets the web server instance.
     *
     * @param string $server  a web server name
     * @param string $version a semver-like version
     */
    public function setServer($server, $version)
    {
        $factory = new WebServerFactory();
        $this->server = $factory->create($server, $this->versionParser->normalize($version));

        return $this;
    }

    /**
     * Gets the web server instance.
     *
     * @return WebServerInterface the web server instance
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Finds the best template for a web server directive according to its version.
     *
     * @param  string $directive a directive
     * @return string            the relative path to the template
     */
    public function find($directive)
    {
        $memoize = $this->getRepository()->getMemoize();
        $serverName = $this->getServer()->getName();
        $return = '';

        $versions = array_keys($memoize[$serverName]);
        sort($versions);

        foreach ($versions as $version) {
            if ($this->comparator->greaterThanOrEqualTo($this->getServer()->getVersion(), $version) &&
                array_key_exists($directive, $memoize[$serverName][$version])
            ) {
                $return = $memoize[$serverName][$version][$directive];
            }
        }

        return $return;
    }

    /**
     * Outputs a webserver directive.
     *
     * @param  string $twigFile a relative path of a template
     * @param  array  $params   parameters
     * @return string           the directive output
     */
    public function render($twigFile, array $params = array())
    {
        return $this->getRepository()->getTwig()->render($twigFile, $params);
    }
}
