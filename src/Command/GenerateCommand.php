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
//use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JamesRezo\WebHelper\WebServer\WebServerFactory;
use JamesRezo\WebHelper\Project\ProjectFactory;
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

        $name = $input->getArgument('webserver');
        if (preg_match(',([^\.\d]+)([\.\d]+)$,', $name, $matches)) {
            $version = $matches[2];
            $name = $matches[1];
        }

        $directives = $input->getArgument('directive');

        $wsFactory = new WebServerFactory();
        $webserver = $wsFactory->create($name, $version);

        $helper = new WebHelper();
        $helper->setWebServer($webserver);

        $statements = array();
        foreach ($directives as $directive) {
            $statements[] = $helper->findDirective($directive);
        }

        $projectName = $this->getComposer()->getPackage()->getName();
        $extra = $this->getComposer()->getPackage()->getExtra();
        $alias = $vhost = preg_replace(',^[^\/]+\/,', '', $projectName);

        $pjFactory = new ProjectFactory();
        $thisProject = $pjFactory->create('symfony', null);
        $thisProject->setData('documentroot', getcwd().'/'.$extra['webhelper']['webdir']);
        $thisProject->setData('aliasname', $extra['webhelper']['aliasname'] ?: $alias);
        $thisProject->setData('vhostname', isset($extra['webhelper']['vhostname']) ?: $vhost.$extra['webhelper']['vhostdomain']);

        $output->writeln($helper->render($thisProject->getDatas(), $statements));
    }
}
