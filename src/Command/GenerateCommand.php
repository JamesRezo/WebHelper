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

class GenerateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Output statements for a webserver')
            ->setHelp('The <info>generate</info> command creates one or many statements for the specified webserver.')
            ->addArgument('directives', InputArgument::IS_ARRAY, 'List of directives to generate.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directives = $input->getArgument('directives');
        $webhelper = new WebHelper();
        $webhelper->setRepository(__DIR__.'/../../res');

        if ($webhelper->getRepository()->okGo()) {
            $webhelper->setServer('apache', '2.4.18');
            foreach ($directives as $directive) {
                $twigFile = $webhelper->find($directive);
                $output->write($webhelper->render($twigFile, [
                    'project' => [
                        'aliasname' => 'webhelper',
                        'documentroot' => realpath(__DIR__.'/../../')
                    ]
                ]));
            }
        }
    }
}
