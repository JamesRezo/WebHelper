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
 * StandardWebProject is the WebProject class for a Non specific project.
 */
class BaseWebProject extends WebProject
{
    /**
     * Constructor.
     *
     * @param string $version the version of the project
     */
    public function __construct($version = '')
    {
        parent::__construct('base', $version);
    }

    /**
     * {@inheritDoc}
     */
    public function setDirProperties()
    {
        $this->setWebDir('');
        $this->setWriteables(array());

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public static function check(PackageInterface $package)
    {
        return true;
    }
}
