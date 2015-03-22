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
 * Base class for WebProject classes.
 *
 * name can be either symfony, drupal, wordpress or other project name
 *
 * @author james <james@rezo.net>
 */
abstract class WebProject implements WebProjectInterface
{
    /**
     * The name of a project.
     *
     * @var string the name of a project
     */
    private $name;

    /**
     * The version of a project.
     *
     * @var string the version of a project
     */
    private $version;

    /**
     * The datas of a project.
     *
     * @var array the datas of a project
     */
    private $datas;

    /**
     * Constructor.
     *
     * @param string $name         the name of a WebProject
     * @param string $version|null the version of a WebProject
     */
    public function __construct($name, $version = null)
    {
        $this->name = $name;
        $this->version = $version;
        $this->datas = $this->resetDatas();
    }

    /**
     * Get the name of a WebProject.
     *
     * @return string the name of the WebProject
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the version of a WebProject.
     *
     * @return string the version of the WebProject
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * {@inheritDoc}
     */
    public function getDatas()
    {
        return $this->datas;
    }

    /**
     * Set the datas to an empty WebProject array.
     *
     * @return array empty project array
     */
    public function resetDatas()
    {
        $this->datas = array(
            'project' => array(),
        );
    }

    /**
     * Sets a property to a value.
     *
     * @param string $name  a property name
     * @param string $value a value for the property, an empty string by default
     *
     * @return WebProject the instance of the project
     */
    public function setData($name, $value = '')
    {
        $this->datas['project'][$name] = $value;

        return $this;
    }
}
