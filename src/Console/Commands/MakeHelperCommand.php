<?php

namespace Fuguevit\Support\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Fuguevit\Support\Console\Commands\Creators\HelperCreator;

/**
 * Class MakeHelperCommand
 *
 * @package Fuguevit\Support\Helpers\Console\Commands
 */
class MakeHelperCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'make:helper';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a new helper class';

    /**
     * @var HelperCreator
     */
    protected $creator;

    /**
     * @var
     */
    protected $composer;

    /**
     * @param HelperCreator $creator
     */
    public function __construct(HelperCreator $creator)
    {
        parent::__construct();
        // Set the creator.
        $this->creator = $creator;
        // Set composer.
        $this->composer = app()['composer'];
    }

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        // Get arguments.
        $arguments = $this->argument();
        // Get options.
        $options = $this->option();
        // Create new helper.
        $this->createHelper($arguments, $options);
        // Run composer dump-autoload.
        $this->composer->dumpAutoloads();
    }

    /**
     * @param $arguments
     * @param $options
     */
    protected function createHelper($arguments, $options)
    {
        // Set helper.
        $helper = $arguments['helper'];
        // Set base.
        $base = $options['base'];
        // Create the helper.
        if ($this->creator->create($helper, $base)) {
            $this->info("Create helper class success!");
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return [
            ['helper', InputArgument::REQUIRED, 'Helper name.']
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getOptions()
    {
        return [
            ['base', null, InputOption::VALUE_OPTIONAL, 'Base class name.', null]
        ];
    }

}