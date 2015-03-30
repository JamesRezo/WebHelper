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
 * DokuwikiWebProject is the WebProject class for a Dokuwiki project.
 */
class DokuwikiWebProject extends WebProject
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
     * Sets the web sub-directory, sub-sub-dirs to deny web access and the writeable directories of the project.
     *
     * {@inheritdoc}
     *
     * @return WebProjectInterface the instance of the web project
     */
    public function setDirProperties()
    {
        $this->setWebDir('');
        $this->setWriteables(array());
        $this->setDenied('(data|conf|bin|inc)'); //WIP array structure to regexp format

        return $this;
    }

   /**
    * Check if $package matches the specificities of a web project.
    *
    * {@inheritdoc}
    *
    * @param  PackageInterface $package Package datas
    *
    * @return bool                      always true
    */
   public static function check(PackageInterface $package)
   {
        //WIP
       return false;
   }
}
