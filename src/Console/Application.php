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

use Composer\Console\Application as BaseApplication;
use JamesRezo\WebHelper\Command;
use Symfony\Component\Console\Command as DefaultCommand;

/**
 * @author James Hautot <james@rezo.net>
 */
class Application extends BaseApplication
{

    public function __construct()
    {
        parent::__construct();
        $this->setName('WebHelper');
        $this->setVersion('0.1');
    }

    protected function getDefaultCommands()
    {
        $commands = array(new DefaultCommand\HelpCommand(), new DefaultCommand\ListCommand());
        $commands[] = new Command\GenerateCommand();

        return $commands;
    }
}
