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
 * SymfonyWebProject is the WebProject class for a Symfony project.
 */
class SymfonyWebProject extends WebProject
{
    /**
     * Constructor.
     *
     * @param integer $version the version of the Symfony project
     */
    public function __construct($version = 0)
    {
        parent::__construct('symfony', $version);
    }

    /**
     * {@inheritDoc}
     */
    public function setDirProperties()
    {
        $this->setWebDir('/web');
        $this->setWriteables(version_compare($this->getVersion(), '3', '>=') ?
            array('/var') :
            array('/app/cache', '/app/logs')
        );

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public static function check(PackageInterface $package)
    {
        $webDir = '';
        $extra = $package->getExtra();
        if (isset($extra['symfony-web-dir'])) {
            $webDir = $extra['symfony-web-dir'];
        }
        //name may be 'symfony/framework-standard-edition'

        return $webDir && (file_exists('app/SymfonyRequirements.php') || file_exists('bin/symfony_requirements'));
    }
}
