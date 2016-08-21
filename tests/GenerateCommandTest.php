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
use JamesRezo\WebHelper\Command\GenerateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateCommandTest extends PHPUnit_Framework_TestCase
{
    public function dataExecute()
    {
        $data = [];

        $data['Unknown Web Server'] = [
            'Web Server "test" unknown.
Known Web Servers are apache or nginx.
',
            'test',
            ['alias', 'directory'],
        ];

        $data['No knwon directives'] = [
            '',
            'apache:2.4',
            ['test1', 'test2'],
        ];

        $data['Apache 2.4.x'] = [
            'Alias webhelper/ "'.getcwd().'"
<Directory "'.getcwd().'">
    Options Indexes FollowSymLinks MultiViews
    Require all granted
</Directory>
',
            'apache:2.4',
            ['alias', 'directory'],
        ];

        return $data;
    }

    /**
     * @dataProvider dataExecute
     */
    public function testExecute($expected, $webservername, $directives)
    {
        $application = new Application();
        $application->add(new GenerateCommand());

        $command = $application->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'webserver' => $webservername,
            'directives' => $directives,
        ));

        $output = $commandTester->getDisplay();
        $this->assertEquals($expected, $output);
    }
}
