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
 * WebServerInterface is the interface implemented by all webproject classes.
 *
 * @author James <james@rezo.net>
 */
interface WebProjectInterface
{
    /**
     * Gets the name of a webproject.
     *
     * @return string the name of the webproject
     */
    public function getName();

    /**
     * Gets the version of a webproject.
     *
     * @return string the version of the webproject
     */
    public function getVersion();

    public function getParameters();
}
