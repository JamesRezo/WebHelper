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
use JamesRezo\WebHelper\WebProject\BaseWebProject;

class WebHelperTest extends PHPUnit_Framework_TestCase
{
    public function testSetWebHelper()
    {
        $webhelper = new WebHelper();
        $webhelper->setRepository(__DIR__.'/dummyrepo')->setTwigEnvironment();

        $this->assertEquals($webhelper->getRepository(), $this->setWindowsPath(__DIR__.'/dummyrepo'));
    }

    public function testUnknownDirective()
    {
        $webhelper = new WebHelper();
        $webhelper->setRepository(__DIR__.'/dummyrepo')->setTwigEnvironment();
        $myWebServer = new ApacheWebServer();
        $webhelper->setWebServer($myWebServer);
        $directory = $webhelper->findDirective('UnknownDirective');

        $this->assertEquals($directory, '');
    }

    public function testLowestVersionWebHelper()
    {
        $webhelper = new WebHelper();
        $webhelper->setRepository(__DIR__.'/dummyrepo')->setTwigEnvironment();
        $myWebServer = new ApacheWebServer();
        $webhelper->setWebServer($myWebServer);
        $directory = $webhelper->findDirective('directory');

        $this->assertEquals(
            $directory,
            $this->setWindowsPath('apache/directory.twig')
        );
    }

    public function testVersion1WebHelper()
    {
        $webhelper = new WebHelper();
        $webhelper->setRepository(__DIR__.'/dummyrepo')->setTwigEnvironment();
        $myWebServer = new ApacheWebServer('1');
        $webhelper->setWebServer($myWebServer);
        $directory = $webhelper->findDirective('directory');

        $this->assertEquals(
            $directory,
            $this->setWindowsPath('apache/directory.twig')
        );
    }

    public function testPreciseVersion12WebHelper()
    {
        $webhelper = new WebHelper();
        $webhelper->setRepository(__DIR__.'/dummyrepo')->setTwigEnvironment();
        $myWebServer = new ApacheWebServer('1.2.0');
        $webhelper->setWebServer($myWebServer);
        $directory = $webhelper->findDirective('directory');

        $this->assertEquals(
            $directory,
            $this->setWindowsPath('apache/1/1.2/directory.twig')
        );
    }

    public function testVersion129WebHelper()
    {
        $webhelper = new WebHelper();
        $webhelper->setRepository(__DIR__.'/dummyrepo')->setTwigEnvironment();
        $myWebServer = new ApacheWebServer('1.2.9');
        $webhelper->setWebServer($myWebServer);
        $directory = $webhelper->findDirective('directory');

        $this->assertEquals(
            $directory,
            $this->setWindowsPath('apache/1/1.2/directory.twig')
        );
    }

    public function testAwesomeVersionWebHelper()
    {
        $webhelper = new WebHelper();
        $webhelper->setRepository(__DIR__.'/dummyrepo')->setTwigEnvironment();
        $myWebServer = new ApacheWebServer('1.2.17');
        $webhelper->setWebServer($myWebServer);
        $directory = $webhelper->findDirective('directory');

        $this->assertEquals(
            $directory,
            $this->setWindowsPath('apache/1/1.2/1.2.17/directory.twig')
        );
    }

    public function testLatestVersionWebHelper()
    {
        $webhelper = new WebHelper();
        $webhelper->setRepository(__DIR__.'/dummyrepo')->setTwigEnvironment();
        $myWebServer = new ApacheWebServer('2');
        $webhelper->setWebServer($myWebServer);
        $directory = $webhelper->findDirective('directory');

        $this->assertEquals(
            $directory,
            $this->setWindowsPath('apache/2/directory.twig')
        );
    }

    public function testRenderWebHelper()
    {
        $webhelper = new WebHelper();
        $webhelper->setRepository(__DIR__.'/dummyrepo')->setTwigEnvironment();
        $myWebServer = new ApacheWebServer('1.2.17');
        $webhelper->setWebServer($myWebServer);
        $myWebProject = new BaseWebProject();
        $webhelper->setWebProject($myWebProject);
        $directory = $webhelper->findDirective('directory');

        $this->assertEquals(
            $webhelper->render(array($directory)),
            'Directory res/dummy/1/1.2/1.2.17'."\n"
        );
    }

    private function setWindowsPath($path)
    {
        static $isWindows = null;

        if (is_null($isWindows)) {
            $isWindows = preg_match(',^WIN,', PHP_OS);
        }

        if ($isWindows) {
            $path = str_replace('/', '\\', $path);
        }

        return $path;
    }
}
