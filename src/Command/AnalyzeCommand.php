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
use Symfony\Component\Console\Input\InputArgument;
use JamesRezo\WebHelper\Factory;
use JamesRezo\WebHelper\WebServer\NullWebServer;

/**
 * Analyze a webserver configuration.
 */
class AnalyzeCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('analyze')
            ->setDescription('Analyze a webserver configuration')
            ->setHelp('The <info>analyze</info> command parses the configuration of a webserver.')
            ->addArgument('webserver', InputArgument::REQUIRED, 'a webserver name.')
            ->addArgument('configfile', InputArgument::REQUIRED, 'a configuration file.')
        ;
    }

    /**
     * Execute the command.
     *
     * {@inheritdoc}
     *
     * @param InputInterface  $input  the input interface
     * @param OutputInterface $output the output interface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
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
            return 1;
        }

        $configfile = $input->getArgument('configfile');
        if (!is_readable($configfile)) {
            $output->writeln('<error>Configuration file "'.$configfile.'" does not exist.</error>');
            return 1;            
        }

        //For now, just outputs a cleaned list of directives
        $output->write($webserver->getActiveConfig($configfile));
    }
}
