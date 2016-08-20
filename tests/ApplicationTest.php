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
        $application = new Application();
        $application->setAutoExit(false);
        $applicationTester = new ApplicationTester($application);
        $applicationTester->run([]);

        $this->assertContains('WebHelper version 0.2', $applicationTester->getDisplay([]));
    }
}
