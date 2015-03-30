<?php
/**
 * This file is, guess what, part of WebHelper.
 *
 * (c) James <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JamesRezo\WebHelper\WebProject;

use Composer\Package\PackageInterface;

/**
 * WebProject Factory Class.
 *
 * @author James <james@rezo.net>
 */
class WebProjectFactory
{
    /**
     * Create a WebProjectInterface Object.
     *
     * @param PackageInterface $package the root Package
     * @param string           $url     the target url
     *
     * @return WebProjectInterface a WebProject Object
     */
    public function create(PackageInterface $package, $url)
    {
        $kind = $this->detectKind($package);
        $needs = $this->setUrlNeeds($url);

        switch ($kind) {
            case 'symfony':
                $project = new SymfonyWebProject($package->getVersion());
                break;
            case 'laravel':
                $project = new LaravelWebProject($package->getVersion());
                break;
            case 'dokuwiki':
                $project = new DokuwikiWebProject($package->getVersion());
                break;
            case 'base':
            default:
                $project = new BaseWebProject($package->getVersion());
                break;
        }

        $project
            ->setDirProperties()
            ->setNeeds($needs);

        return $project;
    }

    /**
     * Try to find what kind of project it is.
     *
     * @param PackageInterface $package Package datas
     *
     * @return string the type of web project
     */
    protected function detectKind(PackageInterface $package)
    {
        $kind = '';

        if (SymfonyWebProject::check($package)) {
            $kind = 'symfony';
        }
        if (LaravelWebProject::check($package)) {
            $kind = 'laravel';
        }
        if (DokuwikiWebProject::check($package)) {
            $kind = 'dokuwiki';
        }
        if (BaseWebProject::check($package)) {
            $kind = 'base';
        }

        return $kind;
    }

    /**
     * Helper to set what does a project need to be configured in a web server.
     *
     * @param string $url the target url
     *
     * @return array a parsed url
     */
    protected function setUrlNeeds($url)
    {
        if (is_null($url)) {
            $parsedUrl = array('scheme' => 'http', 'host' => 'localhost', 'path' => '/myproject', 'port' => 80);
        } else {
            $parsedUrl = parse_url($url);
            if (!isset($parsedUrl['port'])) {
                $parsedUrl['port'] = 80;
            }
        }
        if ($parsedUrl['scheme'] === 'https') {
            $parsedUrl['port'] = 443;
        }
        if (!isset($parsedUrl['path'])) {
            $parsedUrl['path'] = '/';
        }

        return array('host' => $parsedUrl['host'], 'location' => $parsedUrl['path'], 'port' => $parsedUrl['port']);
    }
}
