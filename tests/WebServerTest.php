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
use JamesRezo\WebHelper\Factory;
use JamesRezo\WebHelper\WebServer\NullWebServer;
use JamesRezo\WebHelper\WebServer\ApacheWebServer;
use JamesRezo\WebHelper\WebServer\NginxWebServer;

class WebServerTest extends PHPUnit_Framework_TestCase
{
    protected $factory;

    protected function setUp()
    {
        $this->factory = new Factory();
    }

    public function dataFactory()
    {
        $data = [];

        $data['Null'] = [
            NullWebServer::class,
            'test',
        ];

        $data['Apache'] = [
            ApacheWebServer::class,
            'apache',
        ];

        $data['Nginx'] = [
            NginxWebServer::class,
            'nginx',
        ];

        return $data;
    }

    /**
     * @dataProvider dataFactory
     */
    public function testFactory($expected, $webservername)
    {
        $this->assertInstanceOf($expected, $this->factory->createWebServer($webservername));
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

    public function dataGetBinaries()
    {
        $data = [];

        $data['Null'] = [
            [],
            'test',
        ];

        $data['Apache'] = [
            ['httpd', 'apachectl', 'apache2ctl'],
            'apache',
        ];

        $data['Nginx'] = [
            ['nginx'],
            'nginx',
        ];

        return $data;
    }

    /**
     * @dataProvider dataGetBinaries
     */
    public function testGetBinaries($expected, $webservername)
    {
        $this->assertEquals($expected, $this->factory->createWebServer($webservername)->getBinaries());
    }

    public function dataExtractVersion()
    {
        $data = [];

        $data['Null'] = [
            '',
            '',
            'test',
        ];

        $data['Apache'] = [
            '2.4.18',
            'Server version: Apache/2.4.18 (Unix)'."\netc...",
            'apache',
        ];

        $data['wrong Apache settings'] = [
            '',
            'fake line'."\n".'Server version: '."\nfake lines...",
            'apache',
        ];

        $data['Nginx'] = [
            '1.10.1',
            'nginx version: nginx/1.10.1'."\netc...",
            'nginx',
        ];

        $data['wrong Nginx settings'] = [
            '',
            "\n".'nginx version: nginx',
            'nginx',
        ];

        return $data;
    }

    /**
     * @dataProvider dataExtractVersion
     */
    public function testExtractVersion($expected, $settings, $webservername)
    {
        $this->assertEquals($expected, $this->factory->createWebServer($webservername)->extractVersion($settings));
    }

    public function dataExtractConfigFile()
    {
        $data = [];

        $data['Null'] = [
            '',
            '',
            'test',
        ];

        $data['Apache'] = [
            '/some/file.conf',
            'Server version: Apache/2.4.18 (Unix)'."\n".' -D SERVER_CONFIG_FILE="/some/file.conf"',
            'apache',
        ];

        $data['wrong Apache settings'] = [
            '',
            'fake line'."\n".'Server version: '."\n".' -D SERVER_CONFIG_FILE=""',
            'apache',
        ];

        $data['Nginx'] = [
            '/some/file.conf',
            'nginx version: nginx/1.10.1'."\nconfigure arguments: --conf-path=/some/file.conf --extra...",
            'nginx',
        ];

        $data['wrong Nginx settings'] = [
            '',
            "\n".'nginx version: nginx',
            'nginx',
        ];

        return $data;
    }

    /**
     * @dataProvider dataExtractConfigFile
     */
    public function testExtractRootConfigurationFile($expected, $settings, $webservername)
    {
        $this->assertEquals(
            $expected,
            $this->factory->createWebServer($webservername)->extractRootConfigurationFile($settings)
        );
    }
}
