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

use Composer\Cache;
use Composer\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JamesRezo\WebHelper\WebServer\WebServerFactory;
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
            ))
            ->setHelp(<<<EOT
The <info>web:generate</info> command creates one or many statements for the specified webserver.

<info>bin/wh web:generate webserver directive1 ... [directiveN]</info>
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
        $io = $this->getIO();

        $name = $input->getArgument('webserver');
        if (preg_match(',([^\.\d]+)([\.\d]+)$,', $name, $matches)) {
            $version = $matches[2];
            $name = $matches[1];
        }

        $directives = $input->getArgument('directive');

        $wsFactory = new WebServerFactory();
        $webserver = $wsFactory->create($name, $version);
        $cacheTwigDir = $this->getComposer()->getConfig()->get('cache-wh-twig-dir');
        $cache = new Cache($io, $cacheTwigDir);
        if (!$cache->isEnabled()) {
            $io->writeError("<info>Cache is not enabled (cache-wh-twig-dir): $cacheTwigDir</info>");
        }

        $helper = new WebHelper(null, $cacheTwigDir);
        $helper->setWebServer($webserver);

        $statements = array();
        foreach ($directives as $directive) {
            $statements[] = $helper->findDirective($directive);
        }

        $projectName = $this->getComposer()->getPackage()->getName();
        $alias = $vhost = preg_replace(',^[^\/]+\/,', '', $projectName);
        $project = array(
            'project' => array(
                'documentroot' => getcwd(),
                'aliasname' => $alias,
                'vhostname' => $vhost.'.domain.tld',
            ),
        );

        $output->writeln($helper->render($project, $statements));
    }
}
