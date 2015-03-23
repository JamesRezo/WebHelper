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
 * WebProjectInterface is the interface implemented by all WebProject classes.
 *
 * @author James <james@rezo.net>
 */
interface WebProjectInterface
{
     /**
      * Sets host, location and port.
      *
      * @param Array $needs host, location and port to configure
      *
      * @return WebProject this WebProject Instance
      */
     public function setNeeds($needs);

    /**
     * Says if a Package is kind of a WebProject.
     *
     * @param PackageInterface $package The Package to check
     *
     * @return boolean true if the Package is kind of the concrete class WebProject
     */
    public static function check(PackageInterface $package);

    /**
     * Get the web project type.
     *
     * Will be one of known Frameworks and/or Composer Installer plugins
     * and/or CMS and/or self-made ones.
     *
     * @return string the kind of the WebProject
     */
    public function getProjectType();

    /**
     * Get the version of a WebProject.
     *
     * @return string the version of the project type
     */
    public function getVersion();

    /**
     * Sets the sub-directory to be exposed on the web.
     *
     * @param string $dir the sub-directory of the package that will be exposed on the web
     */
    public function setWebDir($dir);

    /**
     * Sets Files or directories the web server needs to write.
     *
     * @param string $dir the sub-directories or special files of the package that will be exposed on the web
     */
    public function setWriteables($dir);

    /**
     * Get the datas of a WebProject.
     *
     * @return array the datas of the WebProject
     */
    public function getDatas();
}
