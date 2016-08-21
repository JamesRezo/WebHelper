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

class WebHelperTest extends PHPUnit_Framework_TestCase
{
    protected $webhelper;

    protected function setUp()
    {
        $this->webhelper = new WebHelper();
        $this->webhelper
            ->setRepository(__DIR__.'/dummyrepo')
            ->setServer('dummywebserver', '1.2.14');
    }

    public function dataFind()
    {
        $data = [];

        $data['Not Found'] = [
            '',
            'test',
        ];

        $data['Found'] = [
            'null/1.2/directory.twig',
            'directory',
        ];

        return $data;
    }

    /**
     * @dataProvider dataFind
     */
    public function testFind($expected, $directive)
    {
        $this->assertEquals($expected, $this->webhelper->find($directive));
    }

    public function dataRender()
    {
        $data = [];

        $data['0.0.0.0'] = [
            'Directory res/dummy'."\n",
            'null/directory.twig',
            [],
        ];

        $data['directive not found'] = [
            '',
            'null/notfound.twig',
            [],
        ];

        return $data;
    }

    /**
     * @dataProvider dataRender
     */
    public function testRender($expected, $twigFile, $params)
    {
        $this->assertEquals($expected, $this->webhelper->render($twigFile, $params));
    }
}
