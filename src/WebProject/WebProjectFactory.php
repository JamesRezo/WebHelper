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
 * WebProject Factory Class.
 *
 * @author James <james@rezo.net>
 */
class WebProjectFactory
{
    /**
     * Create a WebProjectInterface Object.
     *
     * @param string $name    a web server software name
     * @param string $version a web server software version
     *
     * @return WebProjectInterface a WebProject Object
     */
    public function create($name, $version)
    {
        $project = null;

        switch ($name) {
            case 'symfony':
                $project = new SymfonyWebProject($version);
                break;
        }

        return $project;
    }
}
