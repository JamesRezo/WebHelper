<?php
/**
 * This file is, guess what, part of WebHelper.
 *
 * (c) James <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JamesRezo\WebHelper\WebServer;

/**
 * WebServerInterface is the interface implemented by all webserver classes.
 *
 * @author James <james@rezo.net>
 * @package WebHelper\WebServer
 */
interface WebServerInterface
{
	/**
	 * Get the name of a webserver
	 * @return string the name of the webserver
	 */
    public function getName();

    /**
     * Get the version of a webserver
     * @return string the version of the webserver
     */
    public function getVersion();
}
