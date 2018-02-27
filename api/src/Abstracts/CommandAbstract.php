<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 13/12/2017
 * Time: 20:03
 */

namespace Rebelion\Abstracts;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CommandAbstract extends ContainerAwareCommand
{
    const NAME = 'rebelion:abstract';
    const BAR_WIDTH = 50;
    const REDRAW_FREQUENCY = 1;
    const EMPTY_BAR_CHARACTER = ' ';

    /** @var InputInterface */
    protected $input;

    /** @var OutputInterface */
    protected $output;

    /** @var boolean */
    protected $debug = false;

    /** @var integer */
    protected $start = 0;

    /** @var integer */
    protected $elapsed = 0;

    /** @var array */
    protected $items = [];

    /**
     * Default configuration
     */
    protected function configure()
    {
        $this->setName(static::NAME)
            ->setDescription('Abstract command')
            ->setHelp('This is an empty abstract command.')
            ->addOption(
                'debug',
                'd',
                InputOption::VALUE_NONE,
                'Debug mode activation (verbose command)'
            );
    }

    /**
     * Set progress bar items
     *
     * @param array $items
     *
     * @return $this
     */
    protected function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Init progress bar
     *
     * @param OutputInterface $output
     */
    protected function initProgressBar(OutputInterface $output): void
    {
        $progress = new ProgressBar($output, count($this->items));
        $progress->setEmptyBarCharacter(self::EMPTY_BAR_CHARACTER);
        $progress->setRedrawFrequency(self::REDRAW_FREQUENCY);
        $progress->setBarWidth(self::BAR_WIDTH);
    }

    /**
     * Update elapsed time
     *
     * @return $this
     */
    protected function updateElapsed(): self
    {
        $this->elapsed = round(microtime(true) - $this->start, 2);

        return $this;
    }

    /**
     * Start the command
     */
    protected function start(): void
    {
        $this->start = microtime(true);

        if ($this->input->getOption('debug')) {
            $this->debug = true;
        }

        if ($this->debug) {
            echo sprintf("Start command %s\n", static::NAME);
        }
    }

    /**
     * Execute the command logic
     *
     * @return bool
     */
    protected function command(): bool
    {
        echo sprintf("Executing command %s logic...\n", static::NAME);

        return true;
    }

    /**
     * End the command
     */
    protected function end(): void
    {
        $this->updateElapsed();

        if ($this->debug) {
            echo sprintf("End command %s\n", static::NAME);
        }
    }

    /**
     * Execute the command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->input  = $input;
        $this->output = $output;
        $this->start();
        $this->command();
        $this->end();
    }
}
