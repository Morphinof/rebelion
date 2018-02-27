<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 13/12/2017
 * Time: 20:16
 */

namespace Rebelion\Interfaces;

use Symfony\Component\Console\Output\OutputInterface;

interface CommandInterface
{
    /**
     * Set progress bar items
     *
     * @param array $items
     *
     * @return self
     */
    function setItems(array $items): self;

    /**
     * Init progress bar
     *
     * @param OutputInterface $output
     */
    function initProgressBar(OutputInterface $output): void;

    /**
     * Update elapsed time
     *
     * @return self
     */
    function updateElapsed(): self;

    /**
     * Start the command
     */
    function start(): void;

    /**
     * Execute the command logic
     */
    function command(): bool;

    /**
     * End the command
     */
    public function end(): void;
}