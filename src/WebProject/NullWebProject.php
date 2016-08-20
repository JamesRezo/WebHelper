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
 * NullWebProject is the webproject class used when no known php project is found.
 */
class NullWebProject extends WebProject
{
    /**
     * Constructor.
     *
     * @param string $version the semver-like version of the webproject
     */
    public function __construct($version = '')
    {
        parent::__construct('webhelper', $version);
    }

    public function getParameters()
    {
        return [
            'project' => [
                'aliasname' => $this->getName(),
                'documentroot' => realpath(__DIR__.'/../../'),
                'vhostname' => $this->getName(),
                'portnumber' => 80
            ]
        ];
    }
}
