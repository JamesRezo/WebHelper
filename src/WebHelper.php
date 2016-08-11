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
    /** @var Finder [description] */
    private $finder;

    /** @var VersionParser [description] */
    private $versionParser;

    /** @var Comparator [description] */
    private $comparator;

    /** @var array [description] */
    private $memoize = [];

    /** @var Twig_Environment [description] */
    private $twig;

    /** @var string [description] */
    private $server;

    /** @var string [description] */
    private $version;

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
     * @param  string           $resDir [description]
     * @return Twig_Environment         [description]
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
     * Initialize the structured array of a repository of directives.
     *
     * @param  string $resDir [description]
     * @return array          [description]
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
     * @param string $resDir [description]
     */
    public function setTwig($resDir = '')
    {
        $this->twig = $resDir == '' ? null : $this->initialize($resDir);

        return $this;
    }

    /**
     * Get Twig Environment.
     *
     * @return [type] [description]
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * [setMemoize description]
     * @param string $resDir [description]
     */
    public function setMemoize($resDir = '')
    {
        $this->memoize = $resDir == '' ? [] : $this->memoize($resDir);

        return $this;
    }

    /**
     * [getMemoize description]
     * @return [type] [description]
     */
    public function getMemoize()
    {
        return $this->memoize;
    }

    /**
     * [setServer description]
     * @param [type] $server [description]
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * [getServer description]
     * @return [type] [description]
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * [setVersion description]
     * @param [type] $version [description]
     */
    public function setVersion($version)
    {
        $this->version = $this->versionParser->normalize($version);

        return $this;
    }

    /**
     * [getVersion description]
     * @return [type] [description]
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * [find description]
     * @param  [type] $directive [description]
     * @return [type]            [description]
     */
    public function find($directive)
    {
        $return = '';
        $versions = array_keys($this->getMemoize()[$this->getServer()]);
        sort($versions);

        foreach ($versions as $version) {
            if ($this->comparator->greaterThanOrEqualTo($this->getVersion(), $version) &&
                array_key_exists($directive, $this->memoize[$this->getServer()][$version])
            ) {
                $return = $this->memoize[$this->getServer()][$version][$directive];
            }
        }

        return $return;
    }

    /**
     * [render description]
     * @param  [type] $twigFile [description]
     * @param  array  $params   [description]
     * @return [type]           [description]
     */
    public function render($twigFile, array $params = array())
    {
        return $this->twig->render($twigFile, $params);
    }
}
