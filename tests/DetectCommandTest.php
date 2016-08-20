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

class DetectCommandTest extends PHPUnit_Framework_TestCase
{
    private $bin;

    protected function setUp()
    {
        $this->bin = realpath(__DIR__.'/dummyfilesystem/bin');
    }

    public function dataExecute()
    {
        $data = [];

        $data['empty path'] = [
            '',
            [],
            32,
            false
        ];

        $data['path to nginx'] = [
            'detected for nginx',
            [realpath(__DIR__.'/dummyfilesystem/bin')],
            128,
            true
        ];

        return $data;
    }

    /**
     * @dataProvider dataExecute
     */
    public function testExecute($expected, $path, $verbosity, $setPath)
    {
        $homePath = getenv('PATH');
        putenv('PATH=');

        if ($setPath) {
            putenv('PATH='.$this->bin);
        }

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

        putenv('PATH='.$homePath);
    }
}
