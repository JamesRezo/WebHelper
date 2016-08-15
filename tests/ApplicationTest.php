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
use Symfony\Component\Console\Tester\ApplicationTester;
use JamesRezo\WebHelper\Console\Application;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $applicationTest = new Application();
        $applicationTest->setAutoExit(false);
        $application = new ApplicationTester($applicationTest);
        $application->run([]);
        $this->assertContains('generate', $application->getDisplay([]));
    }
}
