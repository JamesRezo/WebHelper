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
 * kind can be either symfony, drupal, wordpress ...
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
     * The host part of an url.
     *
     * @var string the host part of an url
     */
    private $host;

    /**
     * The path part of an url.
     *
     * @var string the path part of an url
     */
    private $location;

    /**
     * The port the webserver is listening to.
     *
     * @var int the port the webserver is listening to
     */
    private $port;

    /**
     * Constructor.
     *
     * @param string $kind         the type of a WebProject
     * @param integer $version the version of a WebProject
     */
    public function __construct($kind, $version = null)
    {
        $this->kind = $kind;
        $this->version = $version;
    }

    /**
     * Sets host, location and port.
     *
     * @param Array $needs host, location and port to configure
     *
     * @return WebProject this WebProject Instance
     */
    public function setNeeds($needs)
    {
        $this->host = $needs['host'];
        $this->location = $needs['location'];
        $this->port = $needs['port'];

        return $this;
    }

    /**
     * Sets the dir to be exposed by webserver.
     *
     * @param string $dir the sub-directory to complete a directory access Directove
     *
     * @return WebProject this WebProject Instance
     */
    public function setWebDir($dir)
    {
        $this->webDir = $dir;

        return $this;
    }

    /**
     * Sets the directories to be written by webserver.
     *
     * @param Array $dir List of writeables directories
     */
    public function setWriteables($dir)
    {
        $this->writeables = $dir;

        return $this;
    }

    /**
     * Get the kind of a WebProject.
     *
     * @return string the kind of the WebProject
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
     * Sets webDir and writeables.
     *
     * @return WebProject this WebProject Instance
     */
    abstract public function setDirProperties();
}
