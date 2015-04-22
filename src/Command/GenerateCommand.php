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

use Composer\Command\Command;
use Composer\Json\JsonFile;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use JamesRezo\WebHelper\WebServer\WebServerFactory;
use JamesRezo\WebHelper\WebProject\WebProjectFactory;
use JamesRezo\WebHelper\WebHelper;

/**
 * Output a statement for a webserver.
 *
 * @author James <james@rezo.net>
 */
class GenerateCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('web:generate')
            ->setDescription('Output a statement for a webserver')
            ->setDefinition(array(
                new InputArgument('webserver', InputArgument::REQUIRED, 'The webserver to configure'),
                new InputArgument(
                    'directive',
                    InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                    'Directives to generate'
                ),
                new InputOption(
                    'repository',
                    'r',
                    InputOption::VALUE_REQUIRED,
                    'Directory or url of a WebHelper Repository',
                    null
                ),
                new InputOption('url', 'u', InputOption::VALUE_REQUIRED, 'The target url', null),
            ))
            ->setHelp(
<<<EOT
The <info>web:generate</info> command creates one or many statements for the specified webserver.
EOT
            )
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
        $version = '';
        $name = $input->getArgument('webserver');
        if (preg_match(',([^\.\d]+)([\.\d]+)$,', $name, $matches)) {
            $version = $matches[2];
            $name = $matches[1];
        }

        $io = $this->getIO();
        $file = new JsonFile('./composer.json');
        if (!$file->exists()) {
            $output->writeln('<error>File not found: '.$file.'</error>');

            return 1;
        }
        $config = $file->read();
        $composer = $this->getApplication()->getComposer(true, $config);

        $wsFactory = new WebServerFactory();
        $webserver = $wsFactory->create($name, $version);
        if (is_null($webserver)) {
            $output->writeln('<error>Web Server "'.$webserver.'" unknown.</error>');

            return 1;
        }

        $pjFactory = new WebProjectFactory();
        $project = $pjFactory->create($composer->getPackage(), $input->getOption('url'));

        try {
            $helper = new WebHelper($composer, $io);
            $helper
                ->setWebServer($webserver)
                ->setWebProject($project)
                ->setRepository($input->getOption('repository'))
                ->setTwigEnvironment();
        } catch (\Exception $e) {
            $output->writeln('<error>Error while processing :'.$e->getMessage().'</error>');

            return 1;
        }

        $directives = $input->getArgument('directive');
        $statements = array();
        foreach ($directives as $directive) {
            $statements[] = $helper->findDirective($directive);
        }

        $output->writeln($helper->render($statements));
    }
}
