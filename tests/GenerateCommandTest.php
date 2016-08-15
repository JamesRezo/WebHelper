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

            // pass arguments to the helper
            //'username' => 'Wouter',

            // prefix the key with a double slash when passing options,
            // e.g: '--some-option' => 'option_value',
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertEquals('Alias webhelper/ "/Users/klike/Sites/JamesRezo/WebHelper"
<Directory "/Users/klike/Sites/JamesRezo/WebHelper">
    Options Indexes FollowSymLinks MultiViews
    Require all granted
</Directory>
', $output);
    }
}
