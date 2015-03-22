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
 * projectTypecan be either symfony, drupal, wordpress or other project projectType *
 *
 * @author james <james@rezo.net>
 */
abstract class WebProject implements WebProjectInterface
{
    /**
     * The kind of a WebProject.
     *
     * @var string the type of a project
     */
    private $kind;

    /**
     * The version of the project kind.
     *
     * @var string the version of the project kind
     */
    private $version;

    /**
     * The sub-directory to be exposed on the web.
     *
     * @var string the sub-directory to be exposed on the web
     */
    private $webDir;

    /**
     * Files or directories the web server needs to write.
     *
     * @var array the list of files and directories the web server needs to write
     */
    private $writeables;

    /**
     * [$host description].
     *
     * @var string
     */
    private $host;

    /**
     * [$location description].
     *
     * @var string
     */
    private $location;

    /**
     * [$port description].
     *
     * @var int
     */
    private $port;

    /**
     * Constructor.
     *
     * @param string $kind         the type of a WebProject
     * @param string $version|null the version of a WebProject
     */
    public function __construct($kind, $version = null)
    {
        $this->kind = $kind;
        $this->version = $version;
    }

    /**
     * [setNeeds description].
     *
     * @param [type] $needs [description]
     */
    public function setNeeds($needs)
    {
        $this->host = $needs['host'];
        $this->location = $needs['location'];
        $this->port = $needs['port'];

        return $this;
    }

    /**
     * [setWebDir description].
     *
     * @param [type] $dir [description]
     */
    public function setWebDir($dir)
    {
        $this->webDir = $dir;

        return $this;
    }

    /**
     * [setWriteables description].
     *
     * @param [type] $dir [description]
     */
    public function setWriteables($dir)
    {
        $this->writeables = $dir;

        return $this;
    }

    /**
     * Get the projectTypeof a WebProject.
     *
     * @return string the projectTypeof the WebProject
     */
    public function getProjectType()
    {
        return $this->kind;
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
        return array(
            'project' => array(
                'documentroot' => realpath(getcwd()).$this->webDir,
                'aliasname' => $this->location,
                'vhostname' => $this->host,
                'portnumber' => $this->port,
            ),
        );
    }

    /**
     * [setDirProperties description].
     */
    abstract public function setDirProperties();
}
