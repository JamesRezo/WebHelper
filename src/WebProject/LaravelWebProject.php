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
 * LaravelWebProject is the WebProject class for a Laravel project.
 */
class LaravelWebProject extends WebProject
{
    /**
     * Constructor.
     *
     * @param string $version the version of the Laravel project
     */
    public function __construct($version = '')
    {
        parent::__construct('laravel', $version);
    }

    /**
     * {@inheritDoc}
     */
    public function setDirProperties()
    {
        $this->setWebDir('/public');
        $this->setWriteables(array('/storage/logs'));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public static function check(PackageInterface $package)
    {
        //name may be 'laravel/laravel'

        return file_exists('bootstrap/autoload.php') || file_exists('bootstrap/app.php');
    }
}
