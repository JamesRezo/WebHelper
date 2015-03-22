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
                new InputOption('repository', false, InputOption::VALUE_REQUIRED, 'Write the archive to this directory', null),
            ))
            ->setHelp(<<<EOT
The <info>web:generate</info> command creates one or many statements for the specified webserver.
EOT
            )
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $version = null;
        $name = $input->getArgument('webserver');
        if (preg_match(',([^\.\d]+)([\.\d]+)$,', $name, $matches)) {
            $version = $matches[2];
            $name = $matches[1];
        }
        $wsFactory = new WebServerFactory();
        $webserver = $wsFactory->create($name, $version);

        $pjFactory = new WebProjectFactory();
        $project = $pjFactory->create($this->getComposer()->getPackage());

        $helper = new WebHelper($this->getComposer(), $this->getIO());
        $helper
            ->setWebServer($webserver)
            ->setWebProject($project)
            ->setRepository($input->getOption('repository'))
            ->setTwigEnvironment();

        $directives = $input->getArgument('directive');
        $statements = array();
        foreach ($directives as $directive) {
            $statements[] = $helper->findDirective($directive);
        }

        $output->writeln($helper->render($statements));
    }
}
