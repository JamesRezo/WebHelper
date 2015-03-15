<?php
/**
 * This file is, guess what, part of WebHelper.
 *
 * (c) James <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JamesRezo\WebHelper\Project;

/**
 * ProjectInterface is the interface implemented by all Project classes.
 *
 * @author James <james@rezo.net>
 */
interface ProjectInterface
{
    /**
     * Get the name of a Project.
     *
     * @return string the name of the Project
     */
    public function getName();

    /**
     * Get the version of a Project.
     *
     * @return string the version of the Project
     */
    public function getVersion();

    /**
     * Get the datas of a Project.
     *
     * @return array the datas of the Project
     */
    public function getDatas();
}
