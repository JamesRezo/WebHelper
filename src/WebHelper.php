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
     * @var string $resDir the repository related to the helper
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
     * Constructor
     * 
     * @param string $dir   Path of the related repository
     * @param string $cache Path of Twig Cache directory
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
     * Absolute path to the related repository
     * 
     * @return string Absolute path to the related repository
     */
    public function getRepository()
    {
        return realpath($this->resDir);
    }

    /**
     * Get the current webserver to be configured
     * 
     * @return WebServerInterface $webserver the current webserver to be configured
     */
    public function getWebServer()
    {
        return $this->webserver;
    }

    /**
     * Sets the webserver to generate the directives statements
     * 
     * @param WebServerInterface $webserver the webserver to generate the directives statements
     */
    public function setWebServer(WebServerInterface $webserver)
    {
        $this->webserver = $webserver;

        return $this;
    }

    /**
     * Validates a Directive
     * 
     * @param  string  $directive the directive to be tested
     * @return boolean            TRUE if the $directive is known word
     */
    private function validateDirective($directive)
    {
        return preg_match(',^[a-z]+$,', $directive);
    }

    /**
     * Looks for the best version of directive twig file
     * 
     * @param  string $directive the directive to be generated
     * @return string            the relative pathname for the $directive
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
     * Output the generated Directives statements
     * 
     * @param  array  $project Project Datas
     * @param  array  $models  List of Twig files
     * @return string          the statements
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
