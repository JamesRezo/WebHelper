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
 * Project Factory Class.
 *
 * @author James <james@rezo.net>
 */
class ProjectFactory
{
    /**
     * Create a ProjectInterface Object.
     *
     * @param string $name    a web server software name
     * @param string $version a web server software version
     *
     * @return ProjectInterface a Project Object
     */
    public function create($name, $version)
    {
        $project = null;

        switch ($name) {
            case 'symfony':
                $project = new SymfonyProject($version);
                break;
        }

        return $project;
    }
}
