<?php

/**
 * This file is, guess what, part of WebHelper.
 *
 * (c) James <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JamesRezo\WebHelper\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JamesRezo\WebHelper\Factory;
use JamesRezo\WebHelper\WebServer\NullWebServer;

/**
 * Base WebHelper command.
 */
class BaseCommand extends Command
{
    protected function getPath(InputInterface $input)
    {
        $path = $input->getArgument('path');

        if (empty($path)) {
            $path = explode(PATH_SEPARATOR, getenv('PATH'));
        }

        return $path;
    }

    protected function getWebServer(InputInterface $input, OutputInterface $output)
    {
        $webservername = $input->getArgument('webserver');
        $version = 0;
        if (preg_match(',([^:]+)(:([\.\d]+))$,', $webservername, $matches)) {
            $version = $matches[3];
            $webservername = $matches[1];
        }

        $factory = new Factory();

        $webserver = $factory->createWebServer($webservername, $version);
        if ($webserver instanceof NullWebServer) {
            $output->writeln('<error>Web Server "'.$webservername.'" unknown.</error>');
            $output->writeln(
                '<comment>Known Web Servers are '.
                implode(' or ', $factory->getKnownWebServers()).
                '.</comment>'
            );
        }

        return $webserver;
    }
}
