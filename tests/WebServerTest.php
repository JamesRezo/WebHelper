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
use JamesRezo\WebHelper\WebServer\WebServerFactory;
use JamesRezo\WebHelper\WebServer\NullWebServer;
use JamesRezo\WebHelper\WebServer\ApacheWebServer;
use JamesRezo\WebHelper\WebServer\NginxWebServer;

class WebServerTest extends PHPUnit_Framework_TestCase
{
    protected $factory;

    protected function setUp()
    {
        $this->factory = new WebServerFactory();
    }

    public function dataFactory()
    {
        $data = [];

        $data['Null'] = [
            NullWebServer::class,
            'test'
        ];

        $data['Apache'] = [
            ApacheWebServer::class,
            'apache'
        ];

        $data['Nginx'] = [
            NginxWebServer::class,
            'nginx'
        ];

        return $data;
    }

    /**
     * @dataProvider dataFactory
     */
    public function testFactory($expected, $webservername)
    {
        $this->assertInstanceOf($expected, $this->factory->create($webservername, 1));
    }

    public function testGetName()
    {
        $apache = new ApacheWebServer(1);
        $this->assertEquals('apache', $apache->getName());
    }

    public function testGetVersion()
    {
        $apache = new ApacheWebServer(1);
        $this->assertEquals(1, $apache->getVersion());        
    }
}
