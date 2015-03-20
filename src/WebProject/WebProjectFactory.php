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
     * @param string $name    a web server software name
     * @param string $version a web server software version
     *
     * @return WebProjectInterface a WebProject Object
     */
    public function create(PackageInterface $package)
    {
        $project = null;
        $webProjectType = '';

        //Provides Project Properties
        $projectName = $package->getName();
        $projectVersion = $package->getVersion();
        $extra = $package->getExtra();
        $packageType = $package->getType();
        $alias = $vhost = preg_replace(',^[^\/]+\/,', '', $projectName);
        if (isset($extra['webhelper']['aliasname'])) {
            $alias = $extra['webhelper']['aliasname'];
        }
        if (isset($extra['webhelper']['vhostname'])) {
            $vhost = $extra['webhelper']['vhostname'];
        } else {
            $vhostdomain = '.'.preg_replace(',\/'.$alias.'$,', '', $projectName).'.net';
            if (isset($extra['webhelper']['vhostdomain'])) {
                $vhostdomain = $extra['webhelper']['vhostdomain'];
            }
            $vhost = $vhost.$vhostdomain;
        }

        switch ($webProjectType) {
            case 'symfony':
                $project = new SymfonyWebProject($projectVersion);
                $project->setData('documentroot', getcwd().'/web');
                break;
            default:
                $project = new StandardWebProject($projectVersion);
                $project->setData('documentroot', getcwd());
                break;
        }

        $project->setData('aliasname', $alias);
        $project->setData('vhostname', $vhost);

        return $project;
    }
}
