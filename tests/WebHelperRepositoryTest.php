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
use JamesRezo\WebHelper\WebHelperRepository;

class WebHelperRepositoryTest extends PHPUnit_Framework_TestCase
{
    public function dataOkGo()
    {
        $data = [];

        $data['unset'] = [
            false,
            ''
        ];

        $data['finder can\'t find resDir'] = [
            false,
            'doesntexist'
        ];

        $data['twig can\'t find resDir'] = [
            false,
            'doesntexist'
        ];

        $data['resDir does exist'] = [
            true,
            __DIR__ . '/dummyrepo'
        ];

        return $data;
    }

    /**
     * @dataProvider dataOkGo
     */
    public function testOkGo($expected, $resDir)
    {
        $this->repository = new WebHelperRepository($resDir);
        $this->assertEquals($expected, $this->repository->okGo());
    }
}
