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
    public function testExecute()
    {
        $application = new Application();
        $application->add(new GenerateCommand());

        $command = $application->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),

            'directives' => ['alias', 'directory'],
        ));

        $output = $commandTester->getDisplay();
        $this->assertEquals('Alias webhelper/ "'.realpath(__DIR__ . '/../').'"
<Directory "'.realpath(__DIR__ . '/../').'">
    Options Indexes FollowSymLinks MultiViews
    Require all granted
</Directory>
', $output);
    }
}
