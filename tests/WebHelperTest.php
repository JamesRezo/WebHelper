<?php
/**
 * This file is, guess what, part of WebHelper.
 *
 * (c) James <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JamesRezo\WebHelper\Test;

use PHPUnit_Framework_TestCase;
use JamesRezo\WebHelper\WebHelper;
use JamesRezo\WebHelper\WebServer\ApacheWebServer;

class WebHelperTest extends PHPUnit_Framework_TestCase
{
    protected $project = array(
        'project' => array(
            'aliasname' => 'testproject',
            'vhostname' => 'testproject.domain.tld',
            'documentroot' => __DIR__,
        ),
    );

    public function testDirectoryWebHelper()
    {
        $webhelper = new WebHelper();
        $myWebServer = new ApacheWebServer('2.4.9');
        $webhelper->setWebServer($myWebServer);
        $directory = $webhelper->findDirective('directory');

        $this->assertEquals(
            $webhelper->render($this->project, array($directory)),
            '<Directory "'.__DIR__.'">
    Options Indexes FollowSymLinks MultiViews
    Require all granted
</Directory>
'
        );
    }
}
