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
 * Base class for webproject classes.
 *
 * name can be either laravel, symfony, drupal, dokuwiki other webproject name
 *
 * @author james <james@rezo.net>
 */
abstract class WebProject implements WebProjectInterface
{
    /**
     * the name of a webproject.
     *
     * @var string the name of a webproject
     */
    private $name;

    /**
     * the version of a webproject.
     *
     * @var string the version of a webproject
     */
    private $version;

    /**
     * Constructor.
     *
     * @param string $name    the name of a webproject
     * @param string $version the version of a webproject
     */
    public function __construct($name, $version = '')
    {
        $this->name = $name;
        $this->version = $version;
    }

    /**
     * Gets the name of a webproject.
     *
     * @return string the name of the webproject
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the version of a webproject.
     *
     * @return string the version of the webproject
     */
    public function getVersion()
    {
        return $this->version;
    }

    abstract public function getParameters();
}
