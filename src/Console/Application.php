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

use Symfony\Component\Console\Application as BaseApplication;
use JamesRezo\WebHelper\Command\GenerateCommand;

class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct();
        $this->setName('WebHelper');
        $this->setVersion('0.2');
        $this->add(new GenerateCommand());
    }
}
