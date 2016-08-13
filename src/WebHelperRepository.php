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
use \Twig_Loader_Filesystem;
use \Twig_Environment;

/**
 * WebHelper Repository.
 */
class WebHelperRepository
{
    /** @var Finder a Finder instance */
    private $finder;

    /** @var VersionParser a VersionParser instance */
    private $versionParser;

    /** @var array a structured array of a directives repository */
    private $memoize = [];

    /** @var Twig_Environment a Twig_Environment instance */
    private $twig = null;

    /**
     * Base constructor.
     *
     * @param string $resDir the Path of a Directives Repository
     */
    public function __construct($resDir)
    {
        $this->finder = new Finder();
        $this->versionParser = new VersionParser();
        if ($resDir !== '') {
            $this->memoize = $this->memoize($resDir);
            $this->twig = $this->initialize($resDir);            
        }
    }

    /**
     * Initialize the Twig Environment.
     *
     * @param  string           $resDir the Path of a Directives Repository
     * @return Twig_Environment         the Twig Environment
     */
    private function initialize($resDir)
    {
        try {
            $loader = new Twig_Loader_Filesystem($resDir);
        } catch (\Twig_Error_Loader $e) {
            return null;
        }

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
        try {
            $this->finder->files()->name('*.twig')->in($resDir);
        } catch (\InvalidArgumentException $e) {
            return [];
        }

        $memoize = [];
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
     * Gets the structured array of a directives repository.
     *
     * @return array the structured array of a directives repository
     */
    public function getMemoize()
    {
        return $this->memoize;
    }

    /**
     * Gets the Twig Environment.
     *
     * @return Twig_Environment the Twig Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * Tells if the Repository can be used.
     *
     * @return boolean true if there are some directives in the Path of a Directives Repository
     */
    public function okGo()
    {
        return !empty($this->memoize) && is_a($this->twig, 'Twig_Environment');
    }
}
