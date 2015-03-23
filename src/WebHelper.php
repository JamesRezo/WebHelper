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
use JamesRezo\WebHelper\WebProject\WebProjectInterface;
use Symfony\Component\Finder\Finder;
use Composer\Composer;
use Composer\Factory;
use Composer\Cache;
use Composer\IO\IOInterface;
use Composer\IO\NullIO;
use Composer\Util\RemoteFilesystem;
use Composer\Json\JsonFile;

/**
 * WebHelper.
 */
class WebHelper
{
    /**
     * Repository path.
     *
     * @var string the repository related to the helper
     */
    private $resDir;

    /**
     * the twig engine.
     *
     * @var \Twig_Environment the twig engine
     */
    private $twigEnvironment;

    /**
     * A webserver to generate the directives statements.
     *
     * @var WebServerInterface a webserver to generate the directives statements
     */
    private $webserver = null;

    /**
     * A webproject to transmit the directives parameters.
     *
     * @var WebProjectInterface a webproject to transmit the directives parameters
     */
    private $webproject = null;

    /**
     * The Compsoer instance.
     *
     * @var Composer
     */
    private $composer;

    /**
     * The IO Interface.
     *
     * @var IOInterface
     */
    private $io;

    /**
     * Constructor.
     */
    public function __construct(Composer $composer = null, IOInterface $io = null)
    {
        $this->io = $io ?: new NullIO();
        $this->composer = $composer ?: Factory::create($this->io, getcwd().'/composer.json');
        $this->setRepository();
    }

    private function getcacheDir()
    {
        $cacheDir = $this->composer->getConfig()->get('webhelper-cache-dir');
        $cacheDir = $cacheDir ?: $this->composer->getConfig()->get('cache-dir').'/webhelper';
        $cache = new Cache($this->io, $cacheDir);
        if (!$cache->isEnabled()) {
            $this->io->writeError("<info>Cache is not enabled (webhelper-cache-dir): $cacheDir</info>");
        }

        return $cache->getRoot();
    }

    /**
     * Sets the Twig Environment.
     */
    public function setTwigEnvironment()
    {
        $cacheTwigDir = $this->getcacheDir().'/twig';
        $cache = new Cache($this->io, $cacheTwigDir);
        if (!$cache->isEnabled()) {
            $this->io->writeError("<info>Cache is not enabled (webhelper-cache-dir): $cacheTwigDir</info>");
        }

        $cacheDir = $cache->getRoot();
        if ($this->resDir && $cacheDir) {
            $loader = new \Twig_Loader_Filesystem($this->resDir);
            $this->twigEnvironment = new \Twig_Environment($loader, array(
                'cache' => $cacheDir,
            ));
        }

        return $this;
    }

    /**
     * Sets the absolute path to the related repository.
     *
     * @param string $repo path to an alternative repository
     */
    public function setRepository($repo = null)
    {
        if (is_null($repo)) {
            $repo = $this->composer->getConfig()->get('webhelper-repository-path');
            $repo = $repo ?: __DIR__.'/../res';
        }
        if (preg_match('{^https?://}i', $repo)) {
            $cacheRepoDir = $this->getcacheDir().'/res';
            $cache = new Cache($this->io, $cacheRepoDir, 'a-z0-9./');
            if (!$cache->isEnabled()) {
                $this->io->writeError("<info>Cache is not enabled (webhelper-cache-dir): $cacheRepoDir</info>");
            }

            $rfs = new RemoteFilesystem($this->io);
            $contents = $rfs->getContents(parse_url($repo, PHP_URL_HOST), $repo.'/webhelper.json', false);
            $config = JsonFile::parseJson($contents, $repo.'/webhelper.json');

            //feed the cache
            foreach ($config['files'] as $file) {
                $contents = $rfs->getContents(parse_url($repo, PHP_URL_HOST), $repo.'/'.$file, false);
                @mkdir($cache->getRoot().dirname($file), 0777, true);
                $cache->write($file, $contents);
            }

            $repo = $cacheRepoDir;
        } else {
            if (!file_exists($repo)) {
                $this->io->writeError('<error>Repository not found: '.$repo.'</error>');

                throw new \RuntimeException('Repository not found');
                exit(1);
            }
        }
        $this->resDir = realpath($repo);

        return $this;
    }

    /**
     * Absolute path to the related repository.
     *
     * @return string Absolute path to the related repository
     */
    public function getRepository()
    {
        return $this->resDir;
    }

    /**
     * Get the current webserver to be configured.
     *
     * @return WebServerInterface $webserver the current webserver to be configured
     */
    public function getWebServer()
    {
        return $this->webserver;
    }

    /**
     * Sets the webserver to generate the directives statements.
     *
     * @param WebServerInterface $webserver the webserver to generate the directives statements
     */
    public function setWebServer(WebServerInterface $webserver)
    {
        $this->webserver = $webserver;

        return $this;
    }

    /**
     * Get the current webproject to be configured.
     *
     * @return WebProjectInterface $webproject the current webproject to be configured
     */
    public function getWebProject()
    {
        return $this->webproject;
    }

    /**
     * Sets the webproject to generate the directives statements.
     *
     * @param WebProjectInterface $webproject the webproject to generate the directives statements
     */
    public function setWebProject(WebProjectInterface $webproject)
    {
        $this->webproject = $webproject;

        return $this;
    }

    /**
     * Validates a Directive.
     *
     * @param string $directive the directive to be tested
     *
     * @return boolean TRUE if the $directive is known word
     */
    private function validateDirective($directive)
    {
        return preg_match(',^[a-z]+$,i', $directive);
    }

    /**
     * Looks for the best version of directive twig file.
     *
     * @param string $directive the directive to be generated
     *
     * @return string the relative pathname for the $directive
     */
    public function findDirective($directive)
    {
        if (!$this->validateDirective($directive) || is_null($this->webserver)) {
            return '';
        }

        $finder = new Finder();
        $name = $this->webserver->getName();
        $version = $this->webserver->getVersion();
        if (is_null($version)) {
            $version = 0;
        }
        $finder->files()->path($name)->name($directive.'.twig')->in($this->getRepository());
        if (iterator_count($finder) == 0) {
            return '';
        }

        $sortByVersion = function (\SplFileInfo $aFile, \SplFileInfo $bFile) {
            $aVersion = basename($aFile->getRelativePath());
            $bVersion = basename($bFile->getRelativePath());

            return version_compare(
                $aVersion == $this->webserver->getName() ? 0 : $aVersion,
                $bVersion == $this->webserver->getName() ? 0 : $bVersion,
                '>='
            );
        };
        $finder->sort($sortByVersion);
        $files = iterator_to_array($finder);
        $relativePathname = '';
        foreach ($files as $file) {
            $fileVersion = basename($file->getRelativePath());
            if ($fileVersion === $name) {
                $fileVersion = 0;
            }
            if (version_compare($fileVersion, $version, '<=')) {
                $relativePathname = $file->getRelativePathname();
            }
        }

        return $relativePathname;
    }

    /**
     * Output the generated Directives statements.
     *
     * @param array $models List of Twig files
     *
     * @return string the statements
     */
    public function render($models)
    {
        $text = '';

        foreach ($models as $model) {
            $text .= $this->twigEnvironment->render($model, $this->getWebProject()->getDatas());
        }

        return $text;
    }
}
