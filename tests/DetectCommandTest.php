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
use JamesRezo\WebHelper\Command\DetectCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use org\bovigo\vfs\vfsStream,
    org\bovigo\vfs\vfsStreamDirectory;

class DetectCommandTest extends PHPUnit_Framework_TestCase
{
    private $bin;

    private $sbin;

    protected function setUp()
    {
        $this->bin = vfsStream::setup('bin');
        $this->sbin = vfsStream::setup('sbin');
        vfsStream::newFile('nginx', 0755)->at($this->sbin)->setContent(
            '#!/bin/bash'."\n\n".'echo "nginx version: nginx/1.0.0"'
        );
        vfsStream::newFile('httpd', 0644)->at($this->sbin)->setContent(
            'fake httpd controler'
        );
    }

    public function dataExecute()
    {
        $data = [];

        $data['using PATH env variable'] = [
            'detected',
            [],
            32
        ];

        $data['empty path'] = [
            '',
            [vfsStream::url('bin')],
            32
        ];

        $data['path to nginx'] = [
            'detected for nginx',
            [vfsStream::url('sbin')],
            128
        ];

        return $data;
    }

    /**
     * @dataProvider dataExecute
     */
    public function testExecute($expected, $path, $verbosity)
    {
        $application = new Application();
        $application->add(new DetectCommand());

        $command = $application->find('detect');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command'  => $command->getName(),
                'path' => $path
            ],
            ['verbosity' => $verbosity]
        );

        $output = $commandTester->getDisplay();
        if ($expected) {
            $this->assertContains($expected, $output);
            if ($verbosity == 128) {
                $this->assertContains('You should analyze this with', $output);
            }
        } else {
            $this->assertEmpty($output);
        }
    }
}
