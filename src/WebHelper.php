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

use Symfony\Component\Finder\Finder;
use Composer\Semver\VersionParser;
use Composer\Semver\Comparator;
use \Twig_Loader_Filesystem;
use \Twig_Environment;
use JamesRezo\WebHelper\WebServer\WebServerFactory;

/**
 * WebHelper.
 */
class WebHelper
{
    /** @var Finder a Finder instance */
    private $finder;

    /** @var VersionParser a VersionParser instance */
    private $versionParser;

    /** @var Comparator a Comparator instance */
    private $comparator;

    /** @var array a structured array of a directives repository */
    private $memoize = [];

    /** @var Twig_Environment a Twig_Environment instance */
    private $twig;

    /** @var WebServerInterface a Web Server instance */
    private $server;

    /**
     * Base constructor.
     */
    public function __construct()
    {
        $this->finder = new Finder();
        $this->versionParser = new VersionParser();
        $this->comparator = new Comparator();
    }

    /**
     * Initialize the Twig Environment.
     *
     * @param  string           $resDir the Path of a Directives Repository
     * @return Twig_Environment         the Twig Environment
     */
    private function initialize($resDir)
    {
        $loader = new Twig_Loader_Filesystem($resDir);
        $twig = new Twig_Environment($loader, array(
            'cache' => __DIR__ . '/../var/cache',
        ));

        return $twig;
    }

    /**
     * Initialize the structured array of a directives repository.
     *
     * @param  string $resDir the Path of a Directives Repository
     * @return array          the structured array of a directives repository
     */
    private function memoize($resDir)
    {
        $memoize = [];
        $this->finder->files()->name('*.twig')->in($resDir);

        foreach ($this->finder as $file) {
            $parsedPath = explode('/', $file->getRelativePathname());
            if (count($parsedPath) == 2) {
                $parsedPath[2] = $parsedPath[1];
                $parsedPath[1] = 0;
            }
            $parsedPath[2] = str_replace('.twig', '', $parsedPath[2]);
            $memoize[$parsedPath[0]]
                [$this->versionParser->normalize($parsedPath[1])]
                [$parsedPath[2]] = $file->getRelativePathname();
        }

        return $memoize;
    }

    /**
     * Set the Twig Environment.
     *
     * @param string $resDir a Path of a Directives Repository
     */
    public function setTwig($resDir = '')
    {
        $this->twig = $resDir == '' ? null : $this->initialize($resDir);

        return $this;
    }

    /**
     * Get Twig Environment.
     *
     * @return Twig_Environment the Twig_Environment instance
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * Sets the structure array of a directives repository.
     *
     * @param string $resDir a Path of a Directives Repository
     */
    public function setMemoize($resDir = '')
    {
        $this->memoize = $resDir == '' ? [] : $this->memoize($resDir);

        return $this;
    }

    /**
     * Sets the structure array of a directives repository.
     *
     * @return array the structure array of a directives repository
     */
    public function getMemoize()
    {
        return $this->memoize;
    }

    /**
     * Sets the web server instance.
     *
     * @param string $server a web server name
     * @param string $version a semver-like version
     */
    public function setServer($server, $version)
    {
        $factory = new WebServerFactory();
        $version = $this->versionParser->normalize($version);
        $this->server = $factory->create($server, $version);

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
        $return = '';
        $versions = array_keys($this->getMemoize()[$this->getServer()->getName()]);
        sort($versions);

        foreach ($versions as $version) {
            if ($this->comparator->greaterThanOrEqualTo($this->getServer()->getVersion(), $version) &&
                array_key_exists($directive, $this->memoize[$this->getServer()->getName()][$version])
            ) {
                $return = $this->memoize[$this->getServer()->getName()][$version][$directive];
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
        return $this->twig->render($twigFile, $params);
    }
}
