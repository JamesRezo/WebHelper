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

use JamesRezo\WebHelper\WebServer\WebServerInterface;
use Symfony\Component\Finder\Finder;

/**
 * WebHelper 
 * @package WebHelper
 */
class WebHelper
{
    /**
     * Repository path
     * @var string $resDir the repository attached to the helper
     */
    private $resDir;

    /**
     * the twig engine
     * 
     * @var \Twig_Environment $Twig_Environment the twig engine
     */
    private $Twig_Environment;

    /**
     * A webserver to generate the directives statements
     * @var  WebServerInterface $webserver a webserver to generate the directives statements
     */
    private $webserver = null;

    /**
     * [__construct description]
     * @param [type] $dir   [description]
     * @param [type] $cache [description]
     */
    public function __construct($dir = null, $cache = null)
    {
        $resDir = is_null($dir) ? __DIR__.'/../res' : $dir;
        $resDir = realpath(preg_replace(',\/*$,', '', $resDir));
        $this->resDir = $resDir;

        $cacheDir = is_null($cache) ? __DIR__.'/../var' : $cache;
        $cacheDir = realpath(preg_replace(',\/*$,', '', $cacheDir));

        if ($resDir && $cacheDir) {
            $loader = new \Twig_Loader_Filesystem($resDir);
            $this->Twig_Environment = new \Twig_Environment($loader, array(
                'cache' => $cacheDir,
            ));
        }
    }

    /**
     * [getRepository description]
     * @return [type] [description]
     */
    public function getRepository()
    {
        return realpath($this->resDir);
    }

    /**
     * [getWebServer description]
     * @return [type] [description]
     */
    public function getWebServer()
    {
        return $this->webserver;
    }

    /**
     * [setWebServer description]
     * @param WebServerInterface $webserver the webserver to generate the directives statements
     */
    public function setWebServer(WebServerInterface $webserver)
    {
        $this->webserver = $webserver;

        return $this;
    }

    /**
     * [validateDirective description]
     * @param  [type] $directive [description]
     * @return [type]            [description]
     */
    private function validateDirective($directive)
    {
        return preg_match(',^[a-z]+$,', $directive);
    }

    /**
     * [findDirective description]
     * @param  [type] $directive [description]
     * @return [type]            [description]
     */
    public function findDirective($directive)
    {
        if (!$this->validateDirective($directive) || is_null($this->webserver)) {
            return '';
        }

        $finder = new Finder();
        $name = $this->webserver->getName();
        $version = $this->webserver->getVersion();
        $finder->files()->path($name)->name($directive.'.twig')->in($this->getRepository());
        if (iterator_count($finder) == 0) {
            return '';
        }

        $sortByVersion = function (\SplFileInfo $a, \SplFileInfo $b) {
            return version_compare(
                basename($a->getRelativePathname()),
                basename($b->getRelativePathname()),
                '<'
            );
        };
        $finder->sort($sortByVersion);
        $files = iterator_to_array($finder);
        $relativePathname = '';
        foreach ($files as $file) {
            $file_version = basename($file->getRelativePath());
            if ($file_version === $name) {
                $file_version = 0;
            }
            if (version_compare($file_version, $version, '<=')) {
                $relativePathname = $file->getRelativePathname();
            }
        }

        return $relativePathname;
    }

    /**
     * [render description]
     * @param  [type] $project [description]
     * @param  [type] $models  [description]
     * @return [type]          [description]
     */
    public function render($project, $models)
    {
        $text = '';

        foreach ($models as $model) {
            $text .= $this->Twig_Environment->render($model, $project);
        }

        return $text;
    }
}
