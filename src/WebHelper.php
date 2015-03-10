<?php

namespace JamesRezo\WebHelper;

use JamesRezo\WebHelper\WebServer\WebServerInterface;
use Symfony\Component\Finder\Finder;

class WebHelper
{
    private $resDir;

    private $Twig_Environment;

    private $webserver = null;

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

    public function getRepository()
    {
        return realpath($this->resDir);
    }

    public function getWebServer()
    {
        return $this->webserver;
    }

    public function setWebServer(WebServerInterface $webserver)
    {
        $this->webserver = $webserver;

        return $this;
    }

    private function validateDirective($directive)
    {
        return preg_match(',^[a-z]+$,', $directive);
    }

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

    public function render($project, $models)
    {
        $text = '';

        foreach ($models as $model) {
            $text .= $this->Twig_Environment->render($model, $project);
        }

        return $text;
    }
}
