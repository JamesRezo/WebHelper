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
use JamesRezo\WebHelper\Command\AnalyzeCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class AnalyzeCommandTest extends PHPUnit_Framework_TestCase
{
    public function dataExecute()
    {
        $data = [];

        $data['Null'] = [
            'Web Server "test" unknown.'."\n",
            'test',
            ''
        ];

        $data['config file does not exist'] = [
            'Configuration file "nginx.conf" does not exist.'."\n",
            'nginx',
            'nginx.conf'
        ];

        $data['nginx'] = [
            '',
            'nginx',
            realpath(__DIR__.'/dummyfilesystem/etc/nginx.conf')
        ];

        $data['Apache 2.4'] = [
            '',
            'apache:2.4.18',
            realpath(__DIR__.'/dummyfilesystem/etc/httpd.conf')
        ];

        return $data;
    }

    /**
     * @dataProvider dataExecute
     */
    public function testExecute($expected, $webserver, $configfile)
    {
        $application = new Application();
        $application->add(new AnalyzeCommand());

        $command = $application->find('analyze');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            'webserver' => $webserver,
            'configfile' => $configfile
        ));

        $output = $commandTester->getDisplay();
        $this->assertEquals($expected, $output);
    }
}
