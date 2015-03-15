<?php
/**
 * This file is, guess what, part of WebHelper.
 *
 * (c) James <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JamesRezo\WebHelper\Console;

//use Symfony\Component\Console\Application as BaseApplication;
use Composer\Console\Application as BaseApplication;
use JamesRezo\WebHelper\Command;

/**
 *
 */
class Application extends BaseApplication
{
    /**
     * Initializes all the WebHelper commands.
     */
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new Command\GenerateCommand();

        return $commands;
    }
}
