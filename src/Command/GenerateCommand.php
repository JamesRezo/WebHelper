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
use Composer\Config;
use Composer\Config\JsonConfigSource;
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

        $io = $this->getApplication()->getIO();
        $io->loadConfiguration($this->getConfiguration());
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

    /**
     * @return Config
     */
    private function getConfiguration()
    {
        $config = new Config();

        // add dir to the config
        $config->merge(array('config' => array('home' => $this->getComposerHome())));

        // load global auth file
        $file = new JsonFile($config->get('home').'/auth.json');
        if ($file->exists()) {
            $config->merge(array('config' => $file->read()));
        }
        $config->setAuthConfigSource(new JsonConfigSource($file, true));

        return $config;
    }

    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    private function getComposerHome()
    {
        $home = getenv('COMPOSER_HOME');
        if (!$home) {
            if (defined('PHP_WINDOWS_VERSION_MAJOR')) {
                if (!getenv('APPDATA')) {
                    throw new \RuntimeException(
                        'The APPDATA or COMPOSER_HOME environment variable must be set for composer to run correctly'
                    );
                }
                $home = strtr(getenv('APPDATA'), '\\', '/').'/Composer';
            } else {
                if (!getenv('HOME')) {
                    throw new \RuntimeException(
                        'The HOME or COMPOSER_HOME environment variable must be set for composer to run correctly'
                    );
                }
                $home = rtrim(getenv('HOME'), '/').'/.composer';
            }
        }

        return $home;
    }
}
