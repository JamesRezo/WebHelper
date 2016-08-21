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
use JamesRezo\WebHelper\Factory;
use JamesRezo\WebHelper\WebServer\WebServerInterface;

/**
 * Detects webservers.
 */
class DetectCommand extends BaseCommand
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
        $path = $this->getPath($input);

        $factory = new Factory();
        $files = false;
        foreach ($factory->getKnownWebServers() as $webservername) {
            $webserver = $factory->createWebServer($webservername);
            $file = $this->searchBinary($path, $webserver, $output);
            $files = $files || (strlen($file) > 0);
        }
        if (!$files) {
            $output->writeln('<error>No Web Server Found.</error>');

            return 1;
        }
    }

    /**
     * Finds the first binary available to manage a webserver.
     *
     * Validity of a binary is to retrieve a version and and default config file
     *
     * @param  array              $path      a list of directories to scan
     * @param  WebServerInterface $webserver the webserver to detect
     * @param  OutputInterface    $output    the output interface
     *
     * @return string the absolute path of the binary if found and valid, empty string elsewhere
     */
    private function searchBinary(array $path, WebServerInterface $webserver, OutputInterface $output)
    {
        $file = '';

        foreach ($webserver->getBinaries() as $binary) {
            $file = $this->which($binary, $path);
            if ($file) {
                $output->writeln('<comment>'.$file.' detected for '.$webserver->getName().'.</comment>');
                $settings = $webserver->getSettings($file);

                $version = $webserver->extractVersion($settings);
                if (!$version) {
                    $output->writeln('<error>No version found for "'.$file.'".</error>');
                    $file = '';
                    continue;
                }

                $configFile = $webserver->extractRootConfigurationFile($settings);
                if (!$configFile) {
                    $output->writeln('<error>No conf. file found for "'.$file.'".</error>');
                    $file = '';
                    continue;
                }

                if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
                    $output->writeln('<comment>That says:</comment>');
                    $output->write($settings);
                }

                $output->writeln('<info>Detected version:</info>'.$version);
                $output->writeln('<info>Detected conf. file:</info>'.$configFile);

                if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                    $output->writeln(
                        '<comment>You should analyze this with </comment>'.
                        'wh analyze '.$webserver->getName().':'.$version.' '.$configFile
                    );
                }
                break;
            }
        }

        return $file;
    }

    /**
     * A `which`-like function.
     *
     * Finds the absolute path of a binary in a list of directories
     *
     * @param string $binary the binary to find in a list of directories
     * @param array  $paths  a list of directories to scan
     *
     * @return string the absolute path of the binary if found, empty string elsewhere
     */
    private function which($binary, array $paths = array())
    {
        $file = '';

        foreach ($paths as $path) {
            $path = preg_replace('#('.preg_quote(DIRECTORY_SEPARATOR).')*$#', '', $path).DIRECTORY_SEPARATOR;
            if (is_executable($path.$binary) && !is_dir($path.$binary)) {
                $file = $path.$binary;
            }
        }

        return $file;
    }
}
