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
 * SymfonyProject is the Project class for Symfony Project.
 */
class SymfonyProject extends Project
{
    /**
     * Constructor.
     *
     * @param integer $version the version of the Symfony Project
     */
    public function __construct($version = 0)
    {
        parent::__construct('symfony', $version);
    }
}
