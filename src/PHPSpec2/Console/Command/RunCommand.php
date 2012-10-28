<?php

namespace PHPSpec2\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\EventDispatcher\EventDispatcher;

use PHPSpec2\Event;
use PHPSpec2\Console;
use PHPSpec2\Formatter;

class RunCommand extends Command
{
    /**
     * Initializes command.
     */
    public function __construct()
    {
        parent::__construct('run');

        $this->setDefinition(array(
            new InputArgument('spec', InputArgument::OPTIONAL, 'Specs to run', 'spec'),
            new InputOption('format', 'f', InputOption::VALUE_REQUIRED, 'Formatter', 'progress'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->setFormatter(new Console\Formatter($output->isDecorated()));
        $app = $this->getApplication();

        $app['console.input']   = $input;
        $app['console.output']  = $output;
        $app['console.helpers'] = $this->getHelperSet();

        $specs = $app['locator']->getSpecifications($input->getArgument('spec'));
        $app['event_dispatcher']->dispatch('beforeSuite', new Event\SuiteEvent);

        $result = 0;
        $startTime = microtime(true);
        foreach ($specs as $spec) {
            $result = max($result, $app['runner']->runSpecification($spec));
        }

        $app['event_dispatcher']->dispatch('afterSuite', new Event\SuiteEvent(
            microtime(true) - $startTime, $result
        ));

        return intval(Event\ExampleEvent::PASSED !== $result);
    }
}
