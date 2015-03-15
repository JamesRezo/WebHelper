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
 * Base class for Project classes.
 *
 * name can be either symfony, drupal, wordpress or other project name
 *
 * @author james <james@rezo.net>
 */
abstract class Project implements ProjectInterface
{
    /**
     * The name of a Project.
     *
     * @var string the name of a Project
     */
    private $name;

    /**
     * The version of a Project.
     *
     * @var string the version of a Project
     */
    private $version;

    /**
     * The datas of a Project.
     *
     * @var array the datas of a Project
     */
    private $datas;

    /**
     * Constructor.
     *
     * @param string $name         the name of a Project
     * @param string $version|null the version of a Project
     */
    public function __construct($name, $version = null)
    {
        $this->name = $name;
        $this->version = $version;
        $this->datas = $this->resetDatas();
    }

    /**
     * Get the name of a Project.
     *
     * @return string the name of the Project
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the version of a Project.
     *
     * @return string the version of the Project
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
     * Set the datas to an empty project array.
     * 
     * @return [type] [description]
     */
    public function resetDatas()
    {
        $this->datas = array(
            'project' => array(),
        );
    }

    public function setData($name, $value = '')
    {
        $this->datas['project'][$name] = $value;

        return $this;
    }
}
