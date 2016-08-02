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

/**
 * WebHelper.
 */
class WebHelper
{
    private $finder;

    private $versionParser;

    private $memoize = [];

    private $twig;

    private $server;

    private $version;

    public function __construct()
    {
        $this->finder = new Finder();
        $this->versionParser = new VersionParser();
    }

    private function initialize($resDir)
    {
        $loader = new Twig_Loader_Filesystem($resDir);
        $twig = new Twig_Environment($loader, array(
            'cache' => __DIR__ . '/../var/cache',
        ));

        return $twig;
    }

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

    public function setTwig($resDir = '')
    {
        $this->twig = $resDir == '' ? null : $this->initialize($resDir);

        return $this;
    }

    public function getTwig()
    {
        return $this->twig;
    }

    public function setMemoize($resDir = '')
    {
        $this->memoize = $resDir == '' ? [] : $this->memoize($resDir);

        return $this;
    }

    public function getMemoize()
    {
        return $this->memoize;
    }

    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function setVersion($version)
    {
        $this->version = $this->versionParser->normalize($version);

        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function find($directive)
    {
        $return = '';
        $versions = array_keys($this->getMemoize()[$this->getServer()]);
        sort($versions);
        foreach ($versions as $version) {
            if (Comparator::greaterThanOrEqualTo($this->getVersion(), $version) &&
                array_key_exists($directive, $this->memoize[$this->getServer()][$version])
            ) {
                $return = $this->memoize[$this->getServer()][$version][$directive];
            }
        }

        return $return;
    }

    public function render($twigFile, array $params = array())
    {
        return $this->twig->render($twigFile, $params);
    }
}
