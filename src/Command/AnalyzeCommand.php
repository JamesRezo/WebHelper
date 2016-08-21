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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use JamesRezo\WebHelper\WebServer\NullWebServer;

/**
 * Analyze a webserver configuration.
 */
class AnalyzeCommand extends BaseCommand
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
        $webserver = $this->getWebServer($input, $output);
        if ($webserver instanceof NullWebServer) {
            return 1;
        }

        $configfile = $input->getArgument('configfile');
        if (!is_readable($configfile)) {
            $output->writeln('<error>Configuration file "'.$configfile.'" does not exist.</error>');

            return 1;
        }

        //For now, just outputs a cleaned list of directives
        $activeConfig = $webserver->getActiveConfig($configfile);
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
            $output->write(implode(PHP_EOL, $activeConfig));
        }

        $parsedActiveConfig = $webserver->parseActiveConfig($activeConfig);
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $output->writeln(var_export($parsedActiveConfig, true));
        }
    }
}
