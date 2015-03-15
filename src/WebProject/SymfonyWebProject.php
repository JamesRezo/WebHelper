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
}
