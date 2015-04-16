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
     * @param string $version the version of the Symfony project
     */
    public function __construct($version = '')
    {
        parent::__construct('symfony', $version);
    }

    /**
     * Sets the web sub-directory and the writeable directories of the Symfony project.
     *
     * {@inheritdoc}
     *
     * @return WebProjectInterface the instance of the web project
     */
    public function setDirProperties()
    {
        $this->setWebDir('/web');
        $this->setWriteables(
            version_compare(intval($this->getVersion()), '3', '>=') ?
            array('/var') : array('/app/cache', '/app/logs')
        );

        return $this;
    }

    /**
     * Check if $package matches the specificities of a Symfony web project.
     *
     * {@inheritdoc}
     *
     * @param PackageInterface $package Package datas
     *
     * @return bool always true
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
