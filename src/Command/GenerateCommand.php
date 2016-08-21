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
use JamesRezo\WebHelper\WebHelper;
use JamesRezo\WebHelper\WebServer\NullWebServer;

/**
 * Generates Configuration Statements given a webserver and known directives.
 */
class GenerateCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Output statements for a webserver')
            ->setHelp('The <info>generate</info> command creates one or many statements for the specified webserver.')
            ->addArgument('webserver', InputArgument::REQUIRED, 'a webserver name.')
            ->addArgument('directives', InputArgument::IS_ARRAY, 'List of directives to generate.')
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

        $webservername = $webserver->getName();
        $version = $webserver->getVersion();

        $directives = $input->getArgument('directives');

        $webhelper = new WebHelper();
        $webhelper->setRepository(__DIR__.'/../../res');
        $webhelper->setProject('webhelper', '0.2');
        $webhelper->setServer($webservername, $version);

        if ($webhelper->getRepository()->okGo()) {
            foreach ($directives as $directive) {
                $twigFile = $webhelper->find($directive);
                $output->write($webhelper->render($twigFile, $webhelper->getProject()->getParameters()));
            }
        }
    }
}
