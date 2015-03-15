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

/**
 * WebProjectInterface is the interface implemented by all WebProject classes.
 *
 * @author James <james@rezo.net>
 */
interface WebProjectInterface
{
    /**
     * Get the name of a WebProject.
     *
     * @return string the name of the WebProject
     */
    public function getName();

    /**
     * Get the version of a WebProject.
     *
     * @return string the version of the WebProject
     */
    public function getVersion();

    /**
     * Get the datas of a WebProject.
     *
     * @return array the datas of the WebProject
     */
    public function getDatas();
}
