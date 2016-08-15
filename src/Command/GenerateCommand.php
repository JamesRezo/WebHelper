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
use JamesRezo\WebHelper\WebHelper;
use JamesRezo\WebHelper\WebServer\NullWebServer;

/**
 * Generates Configuration Statements given a webserver and known directives.
 */
class GenerateCommand extends Command
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
        $webserver = $input->getArgument('webserver');
        $version = 0;
        if (preg_match(',([^:]+):([\.\d]+)$,', $webserver, $matches)) {
            $version = $matches[2];
            $webserver = $matches[1];
        }

        $directives = $input->getArgument('directives');

        $webhelper = new WebHelper();
        $webhelper->setRepository(__DIR__.'/../../res');

        if ($webhelper->getRepository()->okGo()) {
            $webhelper->setServer($webserver, $version);
            if (!($webhelper->getServer() instanceof NullWebServer)) {
                foreach ($directives as $directive) {
                    $twigFile = $webhelper->find($directive);
                    $output->write($webhelper->render($twigFile, [
                        'project' => [
                            'aliasname' => 'webhelper',
                            'documentroot' => realpath(__DIR__.'/../../'),
                            'vhostname' => 'webhelper',
                            'portnumber' => 80
                        ]
                    ]));
                }
            }
        }
    }
}
