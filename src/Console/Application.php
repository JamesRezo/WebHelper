<?php
/**
 * This file is, guess what, part of WebHelper.
 *
 * (c) James <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JamesRezo\WebHelper\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JamesRezo\WebHelper\Command;
use Composer\IO\ConsoleIO;
use Composer\Factory;
use Composer\Util\ErrorHandler;

/**
 * @author James Hautot <james@rezo.net>
 */
class Application extends BaseApplication
{
    protected $io;
    protected $composer;

    public function __construct()
    {
        parent::__construct('WebHelper', '0.1');
        ErrorHandler::register();
    }

    /**
     * {@inheritDoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->registerCommands();
        
        $styles = Factory::createAdditionalStyles();
        foreach ($styles as $name => $style) {
            $output->getFormatter()->setStyle($name, $style);
        }

        $this->io = new ConsoleIO($input, $output, $this->getHelperSet());

        return parent::doRun($input, $output);
    }

    /**
     * @return Composer
     */
    public function getComposer($required = true, $config = null)
    {
        if (null === $this->composer) {
            try {
                $this->composer = Factory::create($this->io, $config);
            } catch (\InvalidArgumentException $e) {
                $this->io->write($e->getMessage());
                exit(1);
            }
        }

        return $this->composer;
    }

    /**
     * Initializes all the composer commands
     */
    protected function registerCommands()
    {
        $this->add(new Command\GenerateCommand());
    }
}
