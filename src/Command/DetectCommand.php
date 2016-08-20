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

/**
 * Detects webservers.
 */
class DetectCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('detect')
            ->setDescription('Detect webservers on this platform.')
            ->setHelp('The <info>detect</info> command finds webserver binaries in the PATH'.
                ' or in the path passed in argument.')
            ->addArgument('path', InputArgument::IS_ARRAY, 'path list to look into for a binary instead of PATH.')
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
        $path = $input->getArgument('path');
        if (empty($path)) {
            $path = explode(PATH_SEPARATOR, getenv('PATH'));
        }

        $factory = new Factory();
        foreach ($factory->getKnownWebServers() as $webserver) {
            $wsObject = $factory->createWebServer($webserver);
            foreach ($wsObject->getBinaries() as $binary) {
                $file = $this->getFromPath($binary, $path);
                if ($file) {
                    $output->writeln('<comment>'.$file.' detected for '.$webserver.'.</comment>');
                    $settings = $wsObject->getSettings($file);
                    $version = $wsObject->extractVersion($settings);
                    $configFile = $wsObject->extractRootConfigurationFile($settings);
                    if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
                        $output->write($settings);
                    }
                    $output->writeln('<info>Detected version:</info>'.$version);
                    $output->writeln('<info>Detected config file:</info>'.$configFile);
                    if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                        $output->writeln(
                            '<comment>You should analyze this with </comment>'.
                            'bin/wh analyze '.$webserver.':'.$version.' '.$configFile
                        );
                    }
                    break;
                }
            }
        }
    }

    private function getFromPath($binary, array $path = array())
    {
        $file = '';

        foreach ($path as $loop) {
            $loop = preg_replace('#('.preg_quote(DIRECTORY_SEPARATOR).')*$#', '', $loop).DIRECTORY_SEPARATOR;
            if (is_executable($loop.$binary)) {
                $file = $loop.$binary;
            }
        }

        return $file;
    }
}
